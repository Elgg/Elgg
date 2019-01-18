<?php

use Elgg\Database;
use Elgg\Application;
use Elgg\Config;
use Elgg\Database\DbConfig;
use Elgg\Project\Paths;
use Elgg\Di\ServiceProvider;
use Elgg\Http\Request;

/**
 * Elgg Installer.
 * Controller for installing Elgg. Supports both web-based on CLI installation.
 *
 * This controller steps the user through the install process. The method for
 * each step handles both the GET and POST requests. There is no XSS/CSRF protection
 * on the POST processing since the installer is only run once by the administrator.
 *
 * The installation process can be resumed by hitting the first page. The installer
 * will try to figure out where to pick up again.
 *
 * All the logic for the installation process is in this class, but it depends on
 * the core libraries. To do this, we selectively load a subset of the core libraries
 * for the first few steps and then load the entire engine once the database and
 * site settings are configured. In addition, this controller does its own session
 * handling until the database is setup.
 *
 * There is an aborted attempt in the code at creating the data directory for
 * users as a subdirectory of Elgg's root. The idea was to protect this directory
 * through a .htaccess file. The problem is that a malicious user can upload a
 * .htaccess of his own that overrides the protection for his user directory. The
 * best solution is server level configuration that turns off AllowOverride for the
 * data directory. See ticket #3453 for discussion on this.
 */
class ElggInstaller {

	private $steps = [
		'welcome',
		'requirements',
		'database',
		'settings',
		'admin',
		'complete',
	];

	private $has_completed = [
		'config' => false,
		'database' => false,
		'settings' => false,
		'admin' => false,
	];

	private $is_action = false;

	private $autoLogin = true;

	/**
	 * @var Application
	 */
	private $app;

	/**
	 * Dispatches a request to one of the step controllers
	 *
	 * @return \Elgg\Http\ResponseBuilder
	 * @throws InstallationException
	 */
	public function run() {
		$app = $this->getApp();

		$this->is_action = $app->_services->request->getMethod() === 'POST';

		$step = get_input('step', 'welcome');

		if (!in_array($step, $this->getSteps())) {
			$step = 'welcome';
		}

		$this->determineInstallStatus();

		$response = $this->checkInstallCompletion($step);
		if ($response) {
			return $response;
		}

		// check if this is an install being resumed
		$response = $this->resumeInstall($step);
		if ($response) {
			return $response;
		}

		$this->finishBootstrapping($step);

		$params = $app->_services->request->request->all();

		$method = "run" . ucwords($step);

		return $this->$method($params);
	}

	/**
	 * Build the application needed by the installer
	 *
	 * @return Application
	 * @throws InstallationException
	 */
	protected function getApp() {
		if ($this->app) {
			return $this->app;
		}

		try {
			$config = new Config();
			$config->elgg_config_locks = false;
			$config->installer_running = true;
			$config->dbencoding = 'utf8mb4';
			$config->boot_cache_ttl = 0;
			$config->system_cache_enabled = false;
			$config->simplecache_enabled = false;
			$config->debug = \Psr\Log\LogLevel::WARNING;
			$config->cacheroot = Paths::sanitize(sys_get_temp_dir()) . 'elgginstaller/caches/';
			$config->assetroot = Paths::sanitize(sys_get_temp_dir()) . 'elgginstaller/assets/';

			$services = new ServiceProvider($config);

			$app = Application::factory([
				'service_provider' => $services,
				'handle_exceptions' => false,
				'handle_shutdown' => false,
			]);

			// Don't set global $CONFIG, because loading the settings file may require it to write to
			// it, and it can have array sets (e.g. cookie config) that fail when using a proxy for
			// the config service.
			//$app->setGlobalConfig();

			Application::setInstance($app);
			$app->loadCore();
			$this->app = $app;

			$app->_services->boot->getCache()->disable();
			$app->_services->plugins->getCache()->disable();
			$app->_services->sessionCache->disable();
			$app->_services->dic_cache->getCache()->disable();
			$app->_services->dataCache->disable();
			$app->_services->autoloadManager->getCache()->disable();

			$app->_services->setValue('session', \ElggSession::getMock());
			$app->_services->views->setViewtype('installation');
			$app->_services->views->registerViewtypeFallback('installation');
			$app->_services->views->registerPluginViews(Paths::elgg());
			$app->_services->translator->registerTranslations(Paths::elgg() . "install/languages/", true);

			return $this->app;
		} catch (ConfigurationException $ex) {
			throw new InstallationException($ex->getMessage());
		}
	}

	/**
	 * Set the auto login flag
	 *
	 * @param bool $flag Auto login
	 *
	 * @return void
	 */
	public function setAutoLogin($flag) {
		$this->autoLogin = (bool) $flag;
	}

	/**
	 * A batch install of Elgg
	 *
	 * All required parameters must be passed in as an associative array. See
	 * $requiredParams for a list of them. This creates the necessary files,
	 * loads the database, configures the site settings, and creates the admin
	 * account. If it fails, an exception is thrown. It does not check any of
	 * the requirements as the multiple step web installer does.
	 *
	 * @param array $params          Array of key value pairs
	 * @param bool  $create_htaccess Should .htaccess be created
	 *
	 * @return void
	 * @throws InstallationException
	 */
	public function batchInstall(array $params, $create_htaccess = false) {
		$app = $this->getApp();

		$defaults = [
			'dbhost' => 'localhost',
			'dbprefix' => 'elgg_',
			'language' => 'en',
			'siteaccess' => ACCESS_PUBLIC,
		];
		$params = array_merge($defaults, $params);

		$required_params = [
			'dbuser',
			'dbpassword',
			'dbname',
			'sitename',
			'wwwroot',
			'dataroot',
			'displayname',
			'email',
			'username',
			'password',
		];
		foreach ($required_params as $key) {
			if (empty($params[$key])) {
				$msg = elgg_echo('install:error:requiredfield', [$key]);
				throw new InstallationException($msg);
			}
		}

		// password is passed in once
		$params['password1'] = $params['password2'] = $params['password'];

		if ($create_htaccess) {
			$rewrite_tester = new ElggRewriteTester();
			if (!$rewrite_tester->createHtaccess($params['wwwroot'])) {
				throw new InstallationException(elgg_echo('install:error:htaccess'));
			}
		}

		if (!_elgg_sane_validate_url($params['wwwroot'])) {
			throw new InstallationException(elgg_echo('install:error:wwwroot', [$params['wwwroot']]));
		}

		// sanitize dataroot path
		$params['dataroot'] = Paths::sanitize($params['dataroot']);

		$this->determineInstallStatus();

		if (!$this->has_completed['config']) {
			if (!$this->createSettingsFile($params)) {
				throw new InstallationException(elgg_echo('install:error:settings'));
			}
		}

		$this->loadSettingsFile();

		// Make sure settings file matches parameters
		$config = $app->_services->config;
		$config_keys = [
			// param key => config key
			'dbhost' => 'dbhost',
			'dbuser' => 'dbuser',
			'dbpassword' => 'dbpass',
			'dbname' => 'dbname',
			'dataroot' => 'dataroot',
			'dbprefix' => 'dbprefix',
		];
		foreach ($config_keys as $params_key => $config_key) {
			if ($params[$params_key] !== $config->$config_key) {
				throw new InstallationException(elgg_echo('install:error:settings_mismatch', [$config_key]));
			}
		}

		if (!$this->connectToDatabase()) {
			throw new InstallationException(elgg_echo('install:error:databasesettings'));
		}

		if (!$this->has_completed['database']) {
			if (!$this->installDatabase()) {
				throw new InstallationException(elgg_echo('install:error:cannotloadtables'));
			}
		}

		// load remaining core libraries
		$this->finishBootstrapping('settings');

		if (!$this->saveSiteSettings($params)) {
			throw new InstallationException(elgg_echo('install:error:savesitesettings'));
		}

		if (!$this->createAdminAccount($params)) {
			throw new InstallationException(elgg_echo('install:admin:cannot_create'));
		}
	}

	/**
	 * Renders the data passed by a controller
	 *
	 * @param string $step The current step
	 * @param array  $vars Array of vars to pass to the view
	 *
	 * @return \Elgg\Http\OkResponse
	 */
	protected function render($step, $vars = []) {
		$vars['next_step'] = $this->getNextStep($step);

		$title = elgg_echo("install:$step");
		$body = elgg_view("install/pages/$step", $vars);

		$output = elgg_view_page(
			$title,
			$body,
			'default',
			[
				'step' => $step,
				'steps' => $this->getSteps(),
			]
		);

		return new \Elgg\Http\OkResponse($output);
	}

	/**
	 * Step controllers
	 */

	/**
	 * Welcome controller
	 *
	 * @param array $vars Not used
	 *
	 * @return \Elgg\Http\ResponseBuilder
	 */
	protected function runWelcome($vars) {
		return $this->render('welcome');
	}

	/**
	 * Requirements controller
	 *
	 * Checks version of php, libraries, permissions, and rewrite rules
	 *
	 * @param array $vars Vars
	 *
	 * @return \Elgg\Http\ResponseBuilder
	 * @throws InstallationException
	 */
	protected function runRequirements($vars) {

		$report = [];

		// check PHP parameters and libraries
		$this->checkPHP($report);

		// check URL rewriting
		$this->checkRewriteRules($report);

		// check for existence of settings file
		if ($this->checkSettingsFile($report) != true) {
			// no file, so check permissions on engine directory
			$this->isInstallDirWritable($report);
		}

		// check the database later
		$report['database'] = [
			[
				'severity' => 'notice',
				'message' => elgg_echo('install:check:database')
			]
		];

		// any failures?
		$numFailures = $this->countNumConditions($report, 'error');

		// any warnings
		$numWarnings = $this->countNumConditions($report, 'warning');


		$params = [
			'report' => $report,
			'num_failures' => $numFailures,
			'num_warnings' => $numWarnings,
		];

		return $this->render('requirements', $params);
	}

	/**
	 * Database set up controller
	 *
	 * Creates the settings.php file and creates the database tables
	 *
	 * @param array $submissionVars Submitted form variables
	 *
	 * @return \Elgg\Http\ResponseBuilder
	 * @throws ConfigurationException
	 */
	protected function runDatabase($submissionVars) {

		$app = $this->getApp();

		$formVars = [
			'dbuser' => [
				'type' => 'text',
				'value' => '',
				'required' => true,
			],
			'dbpassword' => [
				'type' => 'password',
				'value' => '',
				'required' => false,
			],
			'dbname' => [
				'type' => 'text',
				'value' => '',
				'required' => true,
			],
			'dbhost' => [
				'type' => 'text',
				'value' => 'localhost',
				'required' => true,
			],
			'dbprefix' => [
				'type' => 'text',
				'value' => 'elgg_',
				'required' => true,
			],
			'dataroot' => [
				'type' => 'text',
				'value' => '',
				'required' => true,
			],
			'wwwroot' => [
				'type' => 'url',
				'value' => $app->_services->config->wwwroot,
				'required' => true,
			],
			'timezone' => [
				'type' => 'dropdown',
				'value' => 'UTC',
				'options' => \DateTimeZone::listIdentifiers(),
				'required' => true
			]
		];

		if ($this->checkSettingsFile()) {
			// user manually created settings file so we fake out action test
			$this->is_action = true;
		}

		if ($this->is_action) {
			$getResponse = function () use ($app, $submissionVars, $formVars) {
				// only create settings file if it doesn't exist
				if (!$this->checkSettingsFile()) {
					if (!$this->validateDatabaseVars($submissionVars, $formVars)) {
						// error so we break out of action and serve same page
						return;
					}

					if (!$this->createSettingsFile($submissionVars)) {
						return;
					}
				}

				// check db version and connect
				if (!$this->connectToDatabase()) {
					return;
				}

				if (!$this->installDatabase()) {
					return;
				}

				$app->_services->systemMessages->addSuccessMessage(elgg_echo('install:success:database'));

				return $this->continueToNextStep('database');
			};

			$response = $getResponse();
			if ($response) {
				return $response;
			}
		}

		$formVars = $this->makeFormSticky($formVars, $submissionVars);

		$params = ['variables' => $formVars,];

		if ($this->checkSettingsFile()) {
			// settings file exists and we're here so failed to create database
			$params['failure'] = true;
		}

		return $this->render('database', $params);
	}

	/**
	 * Site settings controller
	 *
	 * Sets the site name, URL, data directory, etc.
	 *
	 * @param array $submissionVars Submitted vars
	 *
	 * @return \Elgg\Http\ResponseBuilder
	 */
	protected function runSettings($submissionVars) {

		$app = $this->getApp();

		$formVars = [
			'sitename' => [
				'type' => 'text',
				'value' => 'My New Community',
				'required' => true,
			],
			'siteemail' => [
				'type' => 'email',
				'value' => '',
				'required' => false,
			],
			'siteaccess' => [
				'type' => 'access',
				'value' => ACCESS_PUBLIC,
				'required' => true,
			],
		];

		if ($this->is_action) {
			$getResponse = function () use ($app, $submissionVars, $formVars) {

				if (!$this->validateSettingsVars($submissionVars, $formVars)) {
					return;
				}

				if (!$this->saveSiteSettings($submissionVars)) {
					return;
				}

				$app->_services->systemMessages->addSuccessMessage(elgg_echo('install:success:settings'));

				return $this->continueToNextStep('settings');
			};

			$response = $getResponse();
			if ($response) {
				return $response;
			}
		}

		$formVars = $this->makeFormSticky($formVars, $submissionVars);

		return $this->render('settings', ['variables' => $formVars]);
	}

	/**
	 * Admin account controller
	 *
	 * Creates an admin user account
	 *
	 * @param array $submissionVars Submitted vars
	 *
	 * @return \Elgg\Http\ResponseBuilder
	 * @throws InstallationException
	 */
	protected function runAdmin($submissionVars) {
		$app = $this->getApp();

		$formVars = [
			'displayname' => [
				'type' => 'text',
				'value' => '',
				'required' => true,
			],
			'email' => [
				'type' => 'email',
				'value' => '',
				'required' => true,
			],
			'username' => [
				'type' => 'text',
				'value' => '',
				'required' => true,
			],
			'password1' => [
				'type' => 'password',
				'value' => '',
				'required' => true,
				'pattern' => '.{6,}',
			],
			'password2' => [
				'type' => 'password',
				'value' => '',
				'required' => true,
			],
		];

		if ($this->is_action) {
			$getResponse = function () use ($app, $submissionVars, $formVars) {
				if (!$this->validateAdminVars($submissionVars, $formVars)) {
					return;
				}

				if (!$this->createAdminAccount($submissionVars, $this->autoLogin)) {
					return;
				}

				$app->_services->systemMessages->addSuccessMessage(elgg_echo('install:success:admin'));

				return $this->continueToNextStep('admin');
			};

			$response = $getResponse();
			if ($response) {
				return $response;
			}
		}

		// Bit of a hack to get the password help to show right number of characters
		// We burn the value into the stored translation.
		$app = $this->getApp();
		$lang = $app->_services->translator->getCurrentLanguage();
		$translations = $app->_services->translator->getLoadedTranslations();
		$app->_services->translator->addTranslation($lang, [
			'install:admin:help:password1' => sprintf(
				$translations[$lang]['install:admin:help:password1'],
				$app->_services->config->min_password_length
			),
		]);

		$formVars = $this->makeFormSticky($formVars, $submissionVars);

		return $this->render('admin', ['variables' => $formVars]);
	}

	/**
	 * Controller for last step
	 *
	 * @return \Elgg\Http\ResponseBuilder
	 */
	protected function runComplete() {

		// nudge to check out settings
		$link = elgg_format_element([
			'#tag_name' => 'a',
			'#text' => elgg_echo('install:complete:admin_notice:link_text'),
			'href' => elgg_normalize_url('admin/settings/basic'),
		]);
		$notice = elgg_echo('install:complete:admin_notice', [$link]);
		elgg_add_admin_notice('fresh_install', $notice);

		$result = $this->render('complete');

		_elgg_rmdir(Paths::sanitize(sys_get_temp_dir()) . 'elgginstaller/');

		return $result;
	}

	/**
	 * Step management
	 */

	/**
	 * Get an array of steps
	 *
	 * @return array
	 */
	protected function getSteps() {
		return $this->steps;
	}

	/**
	 * Forwards the browser to the next step
	 *
	 * @param string $currentStep Current installation step
	 *
	 * @return \Elgg\Http\RedirectResponse
	 * @throws InstallationException
	 */
	protected function continueToNextStep($currentStep) {
		$this->is_action = false;

		return new \Elgg\Http\RedirectResponse($this->getNextStepUrl($currentStep));
	}

	/**
	 * Get the next step as a string
	 *
	 * @param string $currentStep Current installation step
	 *
	 * @return string
	 */
	protected function getNextStep($currentStep) {
		$index = 1 + array_search($currentStep, $this->steps);
		if (isset($this->steps[$index])) {
			return $this->steps[$index];
		} else {
			return null;
		}
	}

	/**
	 * Get the URL of the next step
	 *
	 * @param string $currentStep Current installation step
	 *
	 * @return string
	 * @throws InstallationException
	 */
	protected function getNextStepUrl($currentStep) {
		$app = $this->getApp();
		$nextStep = $this->getNextStep($currentStep);

		return $app->_services->config->wwwroot . "install.php?step=$nextStep";
	}

	/**
	 * Updates $this->has_completed according to the current installation
	 *
	 * @return void
	 * @throws InstallationException
	 */
	protected function determineInstallStatus() {
		$app = $this->getApp();

		$path = Config::resolvePath();
		if (!is_file($path) || !is_readable($path)) {
			return;
		}

		$this->loadSettingsFile();

		$this->has_completed['config'] = true;

		// must be able to connect to database to jump install steps
		$dbSettingsPass = $this->checkDatabaseSettings(
			$app->_services->config->dbuser,
			$app->_services->config->dbpass,
			$app->_services->config->dbname,
			$app->_services->config->dbhost
		);

		if (!$dbSettingsPass) {
			return;
		}

		$db = $app->_services->db;

		try {
			// check that the config table has been created
			$result = $db->getData("SHOW TABLES");
			if (!$result) {
				return;
			}
			foreach ($result as $table) {
				$table = (array) $table;
				if (in_array("{$db->prefix}config", $table)) {
					$this->has_completed['database'] = true;
				}
			}
			if ($this->has_completed['database'] == false) {
				return;
			}

			// check that the config table has entries
			$qb = \Elgg\Database\Select::fromTable('config');
			$qb->select('COUNT(*) AS total');

			$result = $db->getData($qb);
			if ($result && $result[0]->total > 0) {
				$this->has_completed['settings'] = true;
			} else {
				return;
			}

			// check that the users entity table has an entry
			$qb = \Elgg\Database\Select::fromTable('entities', 'e');
			$qb->select('COUNT(*) AS total')
				->where($qb->compare('type', '=', 'user', ELGG_VALUE_STRING));

			$result = $db->getData($qb);
			if ($result && $result[0]->total > 0) {
				$this->has_completed['admin'] = true;
			} else {
				return;
			}
		} catch (DatabaseException $ex) {
			throw new InstallationException('Elgg can not connect to the database: ' . $ex->getMessage());
		}

		return;
	}

	/**
	 * Security check to ensure the installer cannot be run after installation
	 * has finished. If this is detected, the viewer is sent to the front page.
	 *
	 * @param string $step Installation step to check against
	 *
	 * @return \Elgg\Http\RedirectResponse|null
	 */
	protected function checkInstallCompletion($step) {
		if ($step != 'complete') {
			if (!in_array(false, $this->has_completed)) {
				// install complete but someone is trying to view an install page
				return new \Elgg\Http\RedirectResponse('/');
			}
		}
	}

	/**
	 * Check if this is a case of a install being resumed and figure
	 * out where to continue from. Returns the best guess on the step.
	 *
	 * @param string $step Installation step to resume from
	 *
	 * @return \Elgg\Http\RedirectResponse|null
	 */
	protected function resumeInstall($step) {
		// only do a resume from the first step
		if ($step !== 'welcome') {
			return null;
		}

		if ($this->has_completed['database'] == false) {
			return null;
		}

		if ($this->has_completed['settings'] == false) {
			return new \Elgg\Http\RedirectResponse("install.php?step=settings");
		}

		if ($this->has_completed['admin'] == false) {
			return new \Elgg\Http\RedirectResponse("install.php?step=admin");
		}

		// everything appears to be set up
		return new \Elgg\Http\RedirectResponse("install.php?step=complete");
	}

	/**
	 * Bootstrapping
	 */

	/**
	 * Load remaining engine libraries and complete bootstrapping
	 *
	 * @param string $step Which step to boot strap for. Required because
	 *                     boot strapping is different until the DB is populated.
	 *
	 * @return void
	 * @throws InstallationException
	 */
	protected function finishBootstrapping($step) {

		$app = $this->getApp();

		$index_db = array_search('database', $this->getSteps());
		$index_settings = array_search('settings', $this->getSteps());
		$index_admin = array_search('admin', $this->getSteps());
		$index_complete = array_search('complete', $this->getSteps());
		$index_step = array_search($step, $this->getSteps());

		// To log in the user, we need to use the Elgg core session handling.
		// Otherwise, use default php session handling
		$use_elgg_session = ($index_step == $index_admin && $this->is_action) || ($index_step == $index_complete);
		if (!$use_elgg_session) {
			$this->createSessionFromFile();
		}

		if ($index_step > $index_db) {
			// once the database has been created, load rest of engine

			// dummy site needed to boot
			$app->_services->config->site = new ElggSite();

			$app->bootCore();
		}
	}

	/**
	 * Load settings
	 *
	 * @return void
	 * @throws InstallationException
	 */
	protected function loadSettingsFile() {
		try {
			$app = $this->getApp();

			$config = Config::fromFile(Config::resolvePath());
			$app->_services->setValue('config', $config);

			// in case the DB instance is already captured in services, we re-inject its settings.
			$app->_services->db->resetConnections(DbConfig::fromElggConfig($config));
		} catch (\Exception $e) {
			$msg = elgg_echo('InstallationException:CannotLoadSettings');
			throw new InstallationException($msg, 0, $e);
		}
	}

	/**
	 * Action handling methods
	 */

	/**
	 * If form is reshown, remember previously submitted variables
	 *
	 * @param array $formVars       Vars int he form
	 * @param array $submissionVars Submitted vars
	 *
	 * @return array
	 */
	protected function makeFormSticky($formVars, $submissionVars) {
		foreach ($submissionVars as $field => $value) {
			$formVars[$field]['value'] = $value;
		}

		return $formVars;
	}

	/* Requirement checks support methods */

	/**
	 * Indicates whether the webserver can add settings.php on its own or not.
	 *
	 * @param array $report The requirements report object
	 *
	 * @return bool
	 */
	protected function isInstallDirWritable(&$report) {
		if (!is_writable(Paths::projectConfig())) {
			$msg = elgg_echo('install:check:installdir', [Paths::PATH_TO_CONFIG]);
			$report['settings'] = [
				[
					'severity' => 'error',
					'message' => $msg,
				]
			];

			return false;
		}

		return true;
	}

	/**
	 * Check that the settings file exists
	 *
	 * @param array $report The requirements report array
	 *
	 * @return bool
	 */
	protected function checkSettingsFile(&$report = []) {
		if (!is_file(Config::resolvePath())) {
			return false;
		}

		if (!is_readable(Config::resolvePath())) {
			$report['settings'] = [
				[
					'severity' => 'error',
					'message' => elgg_echo('install:check:readsettings'),
				]
			];
		}

		return true;
	}

	/**
	 * Check version of PHP, extensions, and variables
	 *
	 * @param array $report The requirements report array
	 *
	 * @return void
	 */
	protected function checkPHP(&$report) {
		$phpReport = [];

		$min_php_version = '7.0.0';
		if (version_compare(PHP_VERSION, $min_php_version, '<')) {
			$phpReport[] = [
				'severity' => 'error',
				'message' => elgg_echo('install:check:php:version', [$min_php_version, PHP_VERSION])
			];
		}

		$this->checkPhpExtensions($phpReport);

		$this->checkPhpDirectives($phpReport);

		if (count($phpReport) == 0) {
			$phpReport[] = [
				'severity' => 'success',
				'message' => elgg_echo('install:check:php:success')
			];
		}

		$report['php'] = $phpReport;
	}

	/**
	 * Check the server's PHP extensions
	 *
	 * @param array $phpReport The PHP requirements report array
	 *
	 * @return void
	 */
	protected function checkPhpExtensions(&$phpReport) {
		$extensions = get_loaded_extensions();
		$requiredExtensions = [
			'pdo_mysql',
			'json',
			'xml',
			'gd',
		];
		foreach ($requiredExtensions as $extension) {
			if (!in_array($extension, $extensions)) {
				$phpReport[] = [
					'severity' => 'error',
					'message' => elgg_echo('install:check:php:extension', [$extension])
				];
			}
		}

		$recommendedExtensions = [
			'mbstring',
		];
		foreach ($recommendedExtensions as $extension) {
			if (!in_array($extension, $extensions)) {
				$phpReport[] = [
					'severity' => 'warning',
					'message' => elgg_echo('install:check:php:extension:recommend', [$extension])
				];
			}
		}
	}

	/**
	 * Check PHP parameters
	 *
	 * @param array $phpReport The PHP requirements report array
	 *
	 * @return void
	 */
	protected function checkPhpDirectives(&$phpReport) {
		if (ini_get('open_basedir')) {
			$phpReport[] = [
				'severity' => 'warning',
				'message' => elgg_echo("install:check:php:open_basedir")
			];
		}

		if (ini_get('safe_mode')) {
			$phpReport[] = [
				'severity' => 'warning',
				'message' => elgg_echo("install:check:php:safe_mode")
			];
		}

		if (ini_get('arg_separator.output') !== '&') {
			$separator = htmlspecialchars(ini_get('arg_separator.output'));
			$msg = elgg_echo("install:check:php:arg_separator", [$separator]);
			$phpReport[] = [
				'severity' => 'error',
				'message' => $msg,
			];
		}

		if (ini_get('register_globals')) {
			$phpReport[] = [
				'severity' => 'error',
				'message' => elgg_echo("install:check:php:register_globals")
			];
		}

		if (ini_get('session.auto_start')) {
			$phpReport[] = [
				'severity' => 'error',
				'message' => elgg_echo("install:check:php:session.auto_start")
			];
		}
	}

	/**
	 * Confirm that the rewrite rules are firing
	 *
	 * @param array $report The requirements report array
	 *
	 * @return void
	 * @throws InstallationException
	 */
	protected function checkRewriteRules(&$report) {
		$app = $this->getApp();

		$tester = new ElggRewriteTester();
		$url = $app->_services->config->wwwroot;
		$url .= Request::REWRITE_TEST_TOKEN . '?' . http_build_query([
				Request::REWRITE_TEST_TOKEN => '1',
			]);
		$report['rewrite'] = [$tester->run($url, Paths::project())];
	}

	/**
	 * Count the number of failures in the requirements report
	 *
	 * @param array  $report    The requirements report array
	 * @param string $condition 'failure' or 'warning'
	 *
	 * @return int
	 */
	protected function countNumConditions($report, $condition) {
		$count = 0;
		foreach ($report as $category => $checks) {
			foreach ($checks as $check) {
				if ($check['severity'] === $condition) {
					$count++;
				}
			}
		}

		return $count;
	}


	/**
	 * Database support methods
	 */

	/**
	 * Validate the variables for the database step
	 *
	 * @param array $submissionVars Submitted vars
	 * @param array $formVars       Vars in the form
	 *
	 * @return bool
	 * @throws InstallationException
	 */
	protected function validateDatabaseVars($submissionVars, $formVars) {

		$app = $this->getApp();

		foreach ($formVars as $field => $info) {
			if ($info['required'] == true && !$submissionVars[$field]) {
				$name = elgg_echo("install:database:label:$field");
				$app->_services->systemMessages->addErrorMessage(elgg_echo('install:error:requiredfield', [$name]));

				return false;
			}
		}

		if (!empty($submissionVars['wwwroot']) && !_elgg_sane_validate_url($submissionVars['wwwroot'])) {
			$app->_services->systemMessages->addErrorMessage(elgg_echo('install:error:wwwroot', [$submissionVars['wwwroot']]));

			return false;
		}

		// check that data root is absolute path
		if (stripos(PHP_OS, 'win') === 0) {
			if (strpos($submissionVars['dataroot'], ':') !== 1) {
				$msg = elgg_echo('install:error:relative_path', [$submissionVars['dataroot']]);
				$app->_services->systemMessages->addErrorMessage($msg);

				return false;
			}
		} else {
			if (strpos($submissionVars['dataroot'], '/') !== 0) {
				$msg = elgg_echo('install:error:relative_path', [$submissionVars['dataroot']]);
				$app->_services->systemMessages->addErrorMessage($msg);

				return false;
			}
		}

		// check that data root exists
		if (!is_dir($submissionVars['dataroot'])) {
			$msg = elgg_echo('install:error:datadirectoryexists', [$submissionVars['dataroot']]);
			$app->_services->systemMessages->addErrorMessage($msg);

			return false;
		}

		// check that data root is writable
		if (!is_writable($submissionVars['dataroot'])) {
			$msg = elgg_echo('install:error:writedatadirectory', [$submissionVars['dataroot']]);
			$app->_services->systemMessages->addErrorMessage($msg);

			return false;
		}

		if (!$app->_services->config->data_dir_override) {
			// check that data root is not subdirectory of Elgg root
			if (stripos($submissionVars['dataroot'], $app->_services->config->path) === 0) {
				$msg = elgg_echo('install:error:locationdatadirectory', [$submissionVars['dataroot']]);
				$app->_services->systemMessages->addErrorMessage($msg);

				return false;
			}
		}

		// according to postgres documentation: SQL identifiers and key words must
		// begin with a letter (a-z, but also letters with diacritical marks and
		// non-Latin letters) or an underscore (_). Subsequent characters in an
		// identifier or key word can be letters, underscores, digits (0-9), or dollar signs ($).
		// Refs #4994
		if (!preg_match("/^[a-zA-Z_][\w]*$/", $submissionVars['dbprefix'])) {
			$app->_services->systemMessages->addErrorMessage(elgg_echo('install:error:database_prefix'));

			return false;
		}

		return $this->checkDatabaseSettings(
			$submissionVars['dbuser'],
			$submissionVars['dbpassword'],
			$submissionVars['dbname'],
			$submissionVars['dbhost']
		);
	}

	/**
	 * Confirm the settings for the database
	 *
	 * @param string $user     Username
	 * @param string $password Password
	 * @param string $dbname   Database name
	 * @param string $host     Host
	 *
	 * @return bool
	 */
	protected function checkDatabaseSettings($user, $password, $dbname, $host) {
		$app = $this->getApp();

		$config = new DbConfig((object) [
			'dbhost' => $host,
			'dbuser' => $user,
			'dbpass' => $password,
			'dbname' => $dbname,
			'dbencoding' => 'utf8mb4',
		]);
		$db = new Database($config);

		try {
			$db->getDataRow("SELECT 1");
		} catch (DatabaseException $e) {
			if (0 === strpos($e->getMessage(), "Elgg couldn't connect")) {
				$app->_services->systemMessages->addErrorMessage(elgg_echo('install:error:databasesettings'));
			} else {
				$app->_services->systemMessages->addErrorMessage(elgg_echo('install:error:nodatabase', [$dbname]));
			}

			return false;
		}

		// check MySQL version
		$version = $db->getServerVersion(DbConfig::READ_WRITE);
		if (version_compare($version, '5.5.3', '<')) {
			$app->_services->systemMessages->addErrorMessage(elgg_echo('install:error:oldmysql2', [$version]));

			return false;
		}

		return true;
	}

	/**
	 * Writes the settings file to the engine directory
	 *
	 * @param array $params Array of inputted params from the user
	 *
	 * @return bool
	 * @throws InstallationException
	 */
	protected function createSettingsFile($params) {
		$app = $this->getApp();

		$template = Application::elggDir()->getContents("elgg-config/settings.example.php");
		if (!$template) {
			$app->_services->systemMessages->addErrorMessage(elgg_echo('install:error:readsettingsphp'));

			return false;
		}

		foreach ($params as $k => $v) {
			// do some sanitization
			switch ($k) {
				case 'dataroot':
					$v = Paths::sanitize($v);
					break;
			}

			$template = str_replace("{{" . $k . "}}", $v, $template);
		}

		$result = file_put_contents(Config::resolvePath(), $template);
		if ($result === false) {
			$app->_services->systemMessages->addErrorMessage(elgg_echo('install:error:writesettingphp'));

			return false;
		}

		$config = (object) [
			'dbhost' => elgg_extract('dbhost', $params, 'localhost'),
			'dbuser' => elgg_extract('dbuser', $params),
			'dbpass' => elgg_extract('dbpassword', $params),
			'dbname' => elgg_extract('dbname', $params),
			'dbencoding' => elgg_extract('dbencoding', $params, 'utf8mb4'),
			'dbprefix' => elgg_extract('dbprefix', $params, 'elgg_'),
		];

		$dbConfig = new DbConfig($config);
		$this->getApp()->_services->setValue('dbConfig', $dbConfig);
		$this->getApp()->_services->db->resetConnections($dbConfig);

		return true;
	}

	/**
	 * Bootstrap database connection before entire engine is available
	 *
	 * @return bool
	 * @throws InstallationException
	 */
	protected function connectToDatabase() {
		try {
			$app = $this->getApp();
			$app->_services->db->setupConnections();
		} catch (DatabaseException $e) {
			$app->_services->systemMessages->addErrorMessage($e->getMessage());

			return false;
		}

		return true;
	}

	/**
	 * Create the database tables
	 *
	 * @return bool
	 */
	protected function installDatabase() {
		try {
			return $this->getApp()->migrate();
		} catch (\Exception $e) {
			return false;
		}
	}

	/**
	 * Site settings support methods
	 */

	/**
	 * Create the data directory if requested
	 *
	 * @param array $submissionVars Submitted vars
	 * @param array $formVars       Variables in the form
	 *
	 * @return bool
	 */
	protected function createDataDirectory(&$submissionVars, $formVars) {
		// did the user have option of Elgg creating the data directory
		if ($formVars['dataroot']['type'] != 'combo') {
			return true;
		}

		// did the user select the option
		if ($submissionVars['dataroot'] != 'dataroot-checkbox') {
			return true;
		}

		$dir = \Elgg\Project\Paths::sanitize($submissionVars['path']) . 'data';
		if (file_exists($dir) || mkdir($dir, 0755)) {
			$submissionVars['dataroot'] = $dir;
			if (!file_exists("$dir/.htaccess")) {
				$htaccess = "Order Deny,Allow\nDeny from All\n";
				if (!file_put_contents("$dir/.htaccess", $htaccess)) {
					return false;
				}
			}

			return true;
		}

		return false;
	}

	/**
	 * Validate the site settings form variables
	 *
	 * @param array $submissionVars Submitted vars
	 * @param array $formVars       Vars in the form
	 *
	 * @return bool
	 */
	protected function validateSettingsVars($submissionVars, $formVars) {
		$app = $this->getApp();

		foreach ($formVars as $field => $info) {
			$submissionVars[$field] = trim($submissionVars[$field]);
			if ($info['required'] == true && $submissionVars[$field] === '') {
				$name = elgg_echo("install:settings:label:$field");
				$app->_services->systemMessages->addErrorMessage(elgg_echo('install:error:requiredfield', [$name]));

				return false;
			}
		}

		// check that email address is email address
		if ($submissionVars['siteemail'] && !is_email_address($submissionVars['siteemail'])) {
			$msg = elgg_echo('install:error:emailaddress', [$submissionVars['siteemail']]);
			$app->_services->systemMessages->addErrorMessage($msg);

			return false;
		}

		return true;
	}

	/**
	 * Initialize the site including site entity, plugins, and configuration
	 *
	 * @param array $submissionVars Submitted vars
	 *
	 * @return bool
	 * @throws InstallationException
	 */
	protected function saveSiteSettings($submissionVars) {
		$app = $this->getApp();

		$site = elgg_get_site_entity();

		if (!$site->guid) {
			$site = new ElggSite();
			$site->name = strip_tags($submissionVars['sitename']);
			$site->access_id = ACCESS_PUBLIC;
			$site->email = $submissionVars['siteemail'];
			$site->save();
		}

		if ($site->guid !== 1) {
			$app->_services->systemMessages->addErrorMessage(elgg_echo('install:error:createsite'));

			return false;
		}

		$app->_services->config->site = $site;

		// new installations have run all the upgrades
		$upgrades = elgg_get_upgrade_files(Paths::elgg() . "engine/lib/upgrades/");

		$sets = [
			'installed' => time(),
			'version' => elgg_get_version(),
			'simplecache_enabled' => 1,
			'system_cache_enabled' => 1,
			'simplecache_lastupdate' => time(),
			'processed_upgrades' => $upgrades,
			'language' => 'en',
			'default_access' => $submissionVars['siteaccess'],
			'allow_registration' => false,
			'walled_garden' => false,
			'allow_user_default_access' => '',
			'default_limit' => 10,
			'security_protect_upgrade' => true,
			'security_notify_admins' => true,
			'security_notify_user_password' => true,
			'security_email_require_password' => true,
		];

		foreach ($sets as $key => $value) {
			elgg_save_config($key, $value);
		}

		try {
			// Plugins hold reference to non-existing DB
			$app->_services->reset('plugins');

			_elgg_generate_plugin_entities();

			$plugins = $app->_services->plugins->find('any');

			foreach ($plugins as $plugin) {
				$manifest = $plugin->getManifest();
				if (!$manifest instanceof ElggPluginManifest) {
					continue;
				}

				if (!$manifest->getActivateOnInstall()) {
					continue;
				}

				$plugin->activate();
			}

			// Wo don't need to run upgrades on new installations
			$app->_services->events->unregisterHandler('create', 'object', '_elgg_create_notice_of_pending_upgrade');
			$upgrades = $app->_services->upgradeLocator->locate();
			foreach ($upgrades as $upgrade) {
				$upgrade->setCompleted();
			}
		} catch (Exception $e) {
			$app->_services->logger->log(\Psr\Log\LogLevel::ERROR, $e);
		}

		return true;
	}

	/**
	 * Validate account form variables
	 *
	 * @param array $submissionVars Submitted vars
	 * @param array $formVars       Form vars
	 *
	 * @return bool
	 * @throws InstallationException
	 */
	protected function validateAdminVars($submissionVars, $formVars) {

		$app = $this->getApp();

		foreach ($formVars as $field => $info) {
			if ($info['required'] == true && !$submissionVars[$field]) {
				$name = elgg_echo("install:admin:label:$field");
				$app->_services->systemMessages->addErrorMessage(elgg_echo('install:error:requiredfield', [$name]));

				return false;
			}
		}

		if ($submissionVars['password1'] !== $submissionVars['password2']) {
			$app->_services->systemMessages->addErrorMessage(elgg_echo('install:admin:password:mismatch'));

			return false;
		}

		if (trim($submissionVars['password1']) == "") {
			$app->_services->systemMessages->addErrorMessage(elgg_echo('install:admin:password:empty'));

			return false;
		}

		$minLength = $app->_services->configTable->get('min_password_length');
		if (strlen($submissionVars['password1']) < $minLength) {
			$app->_services->systemMessages->addErrorMessage(elgg_echo('install:admin:password:tooshort'));

			return false;
		}

		// check that email address is email address
		if ($submissionVars['email'] && !is_email_address($submissionVars['email'])) {
			$msg = elgg_echo('install:error:emailaddress', [$submissionVars['email']]);
			$app->_services->systemMessages->addErrorMessage($msg);

			return false;
		}

		return true;
	}

	/**
	 * Create a user account for the admin
	 *
	 * @param array $submissionVars Submitted vars
	 * @param bool  $login          Login in the admin user?
	 *
	 * @return bool
	 * @throws InstallationException
	 */
	protected function createAdminAccount($submissionVars, $login = false) {
		$app = $this->getApp();

		try {
			$guid = register_user(
				$submissionVars['username'],
				$submissionVars['password1'],
				$submissionVars['displayname'],
				$submissionVars['email']
			);
		} catch (RegistrationException $e) {
			$app->_services->systemMessages->addErrorMessage($e->getMessage());

			return false;
		}

		if (!$guid) {
			$app->_services->systemMessages->addErrorMessage(elgg_echo('install:admin:cannot_create'));

			return false;
		}

		$user = get_entity($guid);

		if (!$user instanceof ElggUser) {
			$app->_services->systemMessages->addErrorMessage(elgg_echo('install:error:loadadmin'));

			return false;
		}

		$app = $this->getApp();

		$ia = $app->_services->session->setIgnoreAccess(true);
		if ($user->makeAdmin() == false) {
			$app->_services->systemMessages->addErrorMessage(elgg_echo('install:error:adminaccess'));
		} else {
			$app->_services->configTable->set('admin_registered', 1);
		}
		$app->_services->session->setIgnoreAccess($ia);

		// add validation data to satisfy user validation plugins
		$user->validated = 1;
		$user->validated_method = 'admin_user';

		if (!$login) {
			return true;
		}

		$this->createSessionFromDatabase();
		try {
			if (login($user) == false) {
				$app->_services->systemMessages->addErrorMessage(elgg_echo('install:error:adminlogin'));
			}
		} catch (LoginException $ex) {
			return false;
		}

		return true;
	}

	/**
	 * Setup session
	 *
	 * @return void
	 * @throws InstallationException
	 */
	protected function createSessionFromFile() {
		$app = $this->getApp();
		$session = ElggSession::fromFiles($app->_services->config);
		$session->setName('Elgg_install');
		$app->_services->setValue('session', $session);
	}

	/**
	 * Setup session
	 *
	 * @return void
	 * @throws InstallationException
	 */
	protected function createSessionFromDatabase() {
		$app = $this->getApp();
		$session = ElggSession::fromDatabase($app->_services->config, $app->_services->db);
		$session->start();
		$app->_services->setValue('session', $session);
	}
}
