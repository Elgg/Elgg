<?php

use Elgg\Application;
use Elgg\Config;
use Elgg\Database;
use Elgg\Database\DbConfig;
use Elgg\Exceptions\ConfigurationException;
use Elgg\Exceptions\Configuration\InstallationException;
use Elgg\Exceptions\Configuration\RegistrationException;
use Elgg\Exceptions\DatabaseException;
use Elgg\Exceptions\LoginException;
use Elgg\Exceptions\PluginException;
use Elgg\Http\Request;
use Elgg\Project\Paths;
use Elgg\Router\RewriteTester;

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
	
	public const MARIADB_MINIMAL_VERSION = '10.6';
	public const MYSQL_MINIMAL_VERSION = '8.0';
	public const PHP_MINIMAL_VERSION = '8.1.0';
	
	protected array $steps = [
		'welcome',
		'requirements',
		'database',
		'settings',
		'admin',
		'complete',
	];

	protected array $has_completed = [
		'config' => false,
		'database' => false,
		'settings' => false,
		'admin' => false,
	];

	protected bool $is_action = false;

	/**
	 * @var Application
	 */
	protected $app;

	/**
	 * Dispatches a request to one of the step controllers
	 *
	 * @return \Elgg\Http\ResponseBuilder
	 */
	public function run(): \Elgg\Http\ResponseBuilder {
		$app = $this->getApp();

		$this->is_action = $app->internal_services->request->getMethod() === 'POST';

		$step = $this->getCurrentStep();

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

		$params = $app->internal_services->request->request->all();

		$method = 'run' . ucwords($step);

		return $this->$method($params);
	}

	/**
	 * Build the application needed by the installer
	 *
	 * @return Application
	 * @throws InstallationException
	 */
	protected function getApp(): Application {
		if ($this->app) {
			return $this->app;
		}

		try {
			$config = new Config();
			$config->installer_running = true;
			$config->dbencoding = 'utf8mb4';
			$config->boot_cache_ttl = 0;
			$config->system_cache_enabled = false;
			$config->simplecache_enabled = false;
			$config->debug = \Psr\Log\LogLevel::WARNING;
			$config->cacheroot = sys_get_temp_dir() . 'elgginstaller/caches';
			$config->assetroot = sys_get_temp_dir() . 'elgginstaller/assets';

			$app = Application::factory([
				'config' => $config,
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

			$app->internal_services->bootCache->disable();
			$app->internal_services->pluginsCache->disable();
			$app->internal_services->accessCache->disable();
			$app->internal_services->metadataCache->disable();
			$app->internal_services->serverCache->disable();

			$current_step = $this->getCurrentStep();
			$index_admin = array_search('admin', $this->getSteps());
			$index_complete = array_search('complete', $this->getSteps());
			$index_step = array_search($current_step, $this->getSteps());
			
			// For the admin creation action and the complete step we use the Elgg core session handling.
			// Otherwise, use default php session handling
			$use_elgg_session = ($index_step == $index_admin) || ($index_step == $index_complete);
			if (!$use_elgg_session) {
				$session = \ElggSession::fromFiles($app->internal_services->config);
				$session->setName('Elgg_install');
				$app->internal_services->set('session', $session);
			}

			$app->internal_services->views->setViewtype('installation');
			$app->internal_services->views->registerViewtypeFallback('installation');
			$app->internal_services->views->registerViewsFromPath(Paths::elgg());
			$app->internal_services->translator->registerTranslations(Paths::elgg() . 'install/languages/', true);

			return $this->app;
		} catch (ConfigurationException $ex) {
			throw new InstallationException($ex->getMessage());
		}
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
	public function batchInstall(array $params, bool $create_htaccess = false): void {
		$app = $this->getApp();

		$defaults = [
			'dbhost' => 'localhost',
			'dbport' => '3306',
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
				throw new InstallationException(elgg_echo('install:error:requiredfield', [$key]));
			}
		}

		// password is passed in once
		$params['password1'] = $params['password'];
		$params['password2'] = $params['password'];

		if ($create_htaccess) {
			$rewrite_tester = new RewriteTester();
			if (!$rewrite_tester->createHtaccess($params['wwwroot'])) {
				throw new InstallationException(elgg_echo('install:error:htaccess'));
			}
		}
		
		if (!\Elgg\Http\Urls::isValidMultiByteUrl($params['wwwroot'])) {
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
		$config = $app->internal_services->config;
		if ($params['dataroot'] !== $config->dataroot) {
			throw new InstallationException(elgg_echo('install:error:settings_mismatch', ['dataroot', $params['dataroot'], $config->dataroot]));
		}
		
		$db_config = $app->internal_services->dbConfig->getConnectionConfig();
		$db_config_keys = [
			// param key => db config key
			'dbhost' => 'host',
			'dbport' => 'port',
			'dbuser' => 'user',
			'dbpassword' => 'password',
			'dbname' => 'database',
			'dbprefix' => 'prefix',
		];
		foreach ($db_config_keys as $params_key => $db_config_key) {
			if ($params[$params_key] !== (string) $db_config[$db_config_key]) {
				throw new InstallationException(elgg_echo('install:error:settings_mismatch', [$db_config_key, $params[$params_key], $db_config[$db_config_key]]));
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
	protected function render(string $step, array $vars = []): \Elgg\Http\OkResponse {
		$vars['next_step'] = $this->getNextStep($step);

		$title = elgg_echo("install:{$step}");
		$body = elgg_view("install/pages/{$step}", $vars);

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
	 * @return \Elgg\Http\OkResponse
	 */
	protected function runWelcome(): \Elgg\Http\OkResponse {
		return $this->render('welcome');
	}

	/**
	 * Requirements controller
	 *
	 * Checks version of php, libraries, permissions, and rewrite rules
	 *
	 * @param array $vars Vars
	 *
	 * @return \Elgg\Http\OkResponse
	 */
	protected function runRequirements(array $vars = []): \Elgg\Http\OkResponse {

		$report = [];

		// check PHP parameters and libraries
		$this->checkPHP($report);

		// check URL rewriting
		$this->checkRewriteRules($report);

		// check for existence of settings file
		if ($this->checkSettingsFile($report) !== true) {
			// no file, so check permissions on engine directory
			$this->isInstallDirWritable($report);
		}

		// check the database later
		$report['database'] = [
			[
				'severity' => 'notice',
				'message' => elgg_echo('install:check:database'),
			],
		];

		return $this->render('requirements', [
			'report' => $report,
			'num_failures' => $this->countNumConditions($report, 'error'),
			'num_warnings' => $this->countNumConditions($report, 'warning'),
		]);
	}

	/**
	 * Database set up controller
	 *
	 * Creates the settings.php file and creates the database tables
	 *
	 * @param array $submissionVars Submitted form variables
	 *
	 * @return \Elgg\Http\ResponseBuilder
	 */
	protected function runDatabase(array $submissionVars = []): \Elgg\Http\ResponseBuilder {

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
			'dbport' => [
				'type' => 'number',
				'value' => 3306,
				'required' => true,
				'min' => 0,
				'max' => 65535,
			],
			'dbprefix' => [
				'type' => 'text',
				'value' => 'elgg_',
				'required' => false,
			],
			'dataroot' => [
				'type' => 'text',
				'value' => '',
				'required' => true,
			],
			'wwwroot' => [
				'type' => 'url',
				'value' => $app->internal_services->config->wwwroot,
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

				$app->internal_services->system_messages->addSuccessMessage(elgg_echo('install:success:database'));

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
	protected function runSettings(array $submissionVars = []): \Elgg\Http\ResponseBuilder {

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

				$app->internal_services->system_messages->addSuccessMessage(elgg_echo('install:success:settings'));

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
	 */
	protected function runAdmin(array $submissionVars = []): \Elgg\Http\ResponseBuilder {
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

				if (!$this->createAdminAccount($submissionVars, true)) {
					return;
				}

				$app->internal_services->system_messages->addSuccessMessage(elgg_echo('install:success:admin'));

				return $this->continueToNextStep('admin');
			};

			$response = $getResponse();
			if ($response) {
				return $response;
			}
		}

		// Bit of a hack to get the password help to show right number of characters
		// We burn the value into the stored translation.

		$lang = $app->internal_services->translator->getCurrentLanguage();
		$translations = $app->internal_services->translator->getLoadedTranslations();
		
		$app->internal_services->translator->addTranslation($lang, [
			'install:admin:help:password1' => sprintf(
				$translations[$lang]['install:admin:help:password1'],
				$app->internal_services->config->min_password_length
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
	protected function runComplete(): \Elgg\Http\ResponseBuilder {

		// nudge to check out settings
		$link = elgg_view_url(elgg_normalize_url('admin/site_settings'), elgg_echo('install:complete:admin_notice:link_text'));
		$notice = elgg_format_element('p', [], elgg_echo('install:complete:admin_notice', [$link]));

		$custom_index_link = elgg_view_url(elgg_normalize_url('admin/plugin_settings/custom_index'), elgg_echo('admin:plugin_settings'));
		$notice .= elgg_format_element('p', [], elgg_echo('install:complete:admin_notice:custom_index', [$custom_index_link]));

		elgg_add_admin_notice('fresh_install', $notice);

		$result = $this->render('complete');

		elgg_delete_directory(Paths::sanitize(sys_get_temp_dir()) . 'elgginstaller/');

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
	protected function getSteps(): array {
		return $this->steps;
	}

	/**
	 * Returns current step
	 *
	 * @return string
	 */
	protected function getCurrentStep(): string {
		$step = get_input('step', 'welcome');
		
		return in_array($step, $this->getSteps()) ? $step : 'welcome';
	}

	/**
	 * Forwards the browser to the next step
	 *
	 * @param string $currentStep Current installation step
	 *
	 * @return \Elgg\Http\RedirectResponse
	 */
	protected function continueToNextStep(string $currentStep): \Elgg\Http\RedirectResponse {
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
	protected function getNextStep(string $currentStep): string {
		$index = 1 + array_search($currentStep, $this->steps);
		
		return $this->steps[$index] ?? '';
	}

	/**
	 * Get the URL of the next step
	 *
	 * @param string $currentStep Current installation step
	 *
	 * @return string
	 */
	protected function getNextStepUrl(string $currentStep): string {
		$app = $this->getApp();
		$nextStep = $this->getNextStep($currentStep);

		return $app->internal_services->config->wwwroot . "install.php?step={$nextStep}";
	}

	/**
	 * Updates $this->has_completed according to the current installation
	 *
	 * @return void
	 * @throws InstallationException
	 */
	protected function determineInstallStatus(): void {
		$app = $this->getApp();

		$path = Config::resolvePath();
		if (!is_file($path) || !is_readable($path)) {
			return;
		}

		$this->loadSettingsFile();

		$this->has_completed['config'] = true;

		// must be able to connect to database to jump install steps
		$dbSettingsPass = $this->checkDatabaseSettings($app->internal_services->dbConfig);

		if (!$dbSettingsPass) {
			return;
		}

		$db = $app->internal_services->db;

		try {
			// check that the config table has been created
			$result = $db->getConnection('read')->executeQuery('SHOW TABLES');
			if (empty($result)) {
				return;
			}
			
			foreach ($result->fetchAllAssociative() as $table) {
				if (in_array("{$db->prefix}config", $table)) {
					$this->has_completed['database'] = true;
				}
			}
			
			if ($this->has_completed['database'] === false) {
				return;
			}

			// check that the config table has entries
			$qb = \Elgg\Database\Select::fromTable(\Elgg\Database\ConfigTable::TABLE_NAME);
			$qb->select('COUNT(*) AS total');

			$result = $db->getDataRow($qb);
			if (!empty($result) && $result->total > 0) {
				$this->has_completed['settings'] = true;
			} else {
				return;
			}

			// check that the users entity table has an entry
			$qb = \Elgg\Database\Select::fromTable(\Elgg\Database\EntityTable::TABLE_NAME, \Elgg\Database\EntityTable::DEFAULT_JOIN_ALIAS);
			$qb->select('COUNT(*) AS total')
				->where($qb->compare('type', '=', 'user', ELGG_VALUE_STRING));

			$result = $db->getDataRow($qb);
			if (!empty($result) && $result->total > 0) {
				$this->has_completed['admin'] = true;
			} else {
				return;
			}
		} catch (DatabaseException $ex) {
			throw new InstallationException('Elgg can not connect to the database: ' . $ex->getMessage(), $ex->getCode(), $ex);
		}
	}

	/**
	 * Security check to ensure the installer cannot be run after installation
	 * has finished. If this is detected, the viewer is sent to the front page.
	 *
	 * @param string $step Installation step to check against
	 *
	 * @return \Elgg\Http\RedirectResponse|null
	 */
	protected function checkInstallCompletion(string $step): ?\Elgg\Http\RedirectResponse {
		if ($step === 'complete') {
			return null;
		}
		
		if (!in_array(false, $this->has_completed)) {
			// install complete but someone is trying to view an install page
			return new \Elgg\Http\RedirectResponse('/');
		}
		
		return null;
	}

	/**
	 * Check if this is a case of a install being resumed and figure
	 * out where to continue from. Returns the best guess on the step.
	 *
	 * @param string $step Installation step to resume from
	 *
	 * @return \Elgg\Http\RedirectResponse|null
	 */
	protected function resumeInstall(string $step): ?\Elgg\Http\RedirectResponse {
		// only do a resume from the first step
		if ($step !== 'welcome') {
			return null;
		}

		if ($this->has_completed['database'] === false) {
			return null;
		}

		if ($this->has_completed['settings'] === false) {
			return new \Elgg\Http\RedirectResponse('install.php?step=settings');
		}

		if ($this->has_completed['admin'] === false) {
			return new \Elgg\Http\RedirectResponse('install.php?step=admin');
		}

		// everything appears to be set up
		return new \Elgg\Http\RedirectResponse('install.php?step=complete');
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
	 */
	protected function finishBootstrapping(string $step): void {

		$app = $this->getApp();

		$index_db = array_search('database', $this->getSteps());
		$index_step = array_search($step, $this->getSteps());

		if ($index_step > $index_db) {
			// once the database has been created, load rest of engine

			// dummy site needed to boot
			$app->internal_services->config->site = new \ElggSite();

			$app->bootCore();
		}
	}

	/**
	 * Load settings
	 *
	 * @return void
	 * @throws InstallationException
	 */
	protected function loadSettingsFile(): void {
		try {
			$app = $this->getApp();

			$config = Config::factory();
			$app->internal_services->set('config', $app->internal_services->initConfig($config));

			// in case the DB instance is already captured in services, we re-inject its settings.
			$app->internal_services->db->resetConnections($app->internal_services->dbConfig);
		} catch (\Exception $e) {
			throw new InstallationException(elgg_echo('InstallationException:CannotLoadSettings'), 0, $e);
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
	protected function makeFormSticky(array $formVars = [], array $submissionVars = []): array {
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
	protected function isInstallDirWritable(array &$report): bool {
		if (!is_writable(Paths::projectConfig())) {
			$report['settings'] = [
				[
					'severity' => 'error',
					'message' => elgg_echo('install:check:installdir', [Paths::PATH_TO_CONFIG]),
				],
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
	protected function checkSettingsFile(array &$report = []): bool {
		if (!is_file(Config::resolvePath())) {
			return false;
		}

		if (!is_readable(Config::resolvePath())) {
			$report['settings'] = [
				[
					'severity' => 'error',
					'message' => elgg_echo('install:check:readsettings'),
				],
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
	protected function checkPHP(array &$report): void {
		$phpReport = [];

		if (version_compare(PHP_VERSION, self::PHP_MINIMAL_VERSION, '<')) {
			$phpReport[] = [
				'severity' => 'error',
				'message' => elgg_echo('install:check:php:version', [self::PHP_MINIMAL_VERSION, PHP_VERSION]),
			];
		}

		$this->checkPhpExtensions($phpReport);

		$this->checkPhpDirectives($phpReport);

		if (count($phpReport) == 0) {
			$phpReport[] = [
				'severity' => 'success',
				'message' => elgg_echo('install:check:php:success'),
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
	protected function checkPhpExtensions(array &$phpReport): void {
		$extensions = get_loaded_extensions();
		$requiredExtensions = [
			'pdo_mysql',
			'json',
			'xml',
			'gd',
			'intl',
		];
		foreach ($requiredExtensions as $extension) {
			if (!in_array($extension, $extensions)) {
				$phpReport[] = [
					'severity' => 'error',
					'message' => elgg_echo('install:check:php:extension', [$extension]),
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
					'message' => elgg_echo('install:check:php:extension:recommend', [$extension]),
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
	protected function checkPhpDirectives(array &$phpReport): void {
		if (ini_get('open_basedir')) {
			$phpReport[] = [
				'severity' => 'warning',
				'message' => elgg_echo('install:check:php:open_basedir'),
			];
		}

		if (ini_get('safe_mode')) {
			$phpReport[] = [
				'severity' => 'warning',
				'message' => elgg_echo('install:check:php:safe_mode'),
			];
		}

		if (ini_get('arg_separator.output') !== '&') {
			$separator = htmlspecialchars(ini_get('arg_separator.output'));
			$phpReport[] = [
				'severity' => 'error',
				'message' => elgg_echo('install:check:php:arg_separator', [$separator]),
			];
		}

		if (ini_get('register_globals')) {
			$phpReport[] = [
				'severity' => 'error',
				'message' => elgg_echo('install:check:php:register_globals'),
			];
		}

		if (ini_get('session.auto_start')) {
			$phpReport[] = [
				'severity' => 'error',
				'message' => elgg_echo('install:check:php:session.auto_start'),
			];
		}
	}

	/**
	 * Confirm that the rewrite rules are firing
	 *
	 * @param array $report The requirements report array
	 *
	 * @return void
	 */
	protected function checkRewriteRules(array &$report): void {
		$tester = new RewriteTester();
		
		$url = $this->getApp()->internal_services->config->wwwroot;
		$url .= Request::REWRITE_TEST_TOKEN . '?' . http_build_query([Request::REWRITE_TEST_TOKEN => '1']);
		
		$report['rewrite'] = [$tester->run($url)];
	}

	/**
	 * Count the number of failures in the requirements report
	 *
	 * @param array  $report    The requirements report array
	 * @param string $condition 'failure' or 'warning'
	 *
	 * @return int
	 */
	protected function countNumConditions(array $report, string $condition): int {
		$count = 0;
		foreach ($report as $checks) {
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
	 */
	protected function validateDatabaseVars(array $submissionVars, array $formVars): bool {

		$app = $this->getApp();

		foreach ($formVars as $field => $info) {
			if ($info['required'] === true && !$submissionVars[$field]) {
				$name = elgg_echo("install:database:label:{$field}");
				$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:error:requiredfield', [$name]));

				return false;
			}
		}

		if (!empty($submissionVars['wwwroot']) && !\Elgg\Http\Urls::isValidMultiByteUrl($submissionVars['wwwroot'])) {
			$save_value = $this->sanitizeInputValue($submissionVars['wwwroot']);
			$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:error:wwwroot', [$save_value]));

			return false;
		}

		// check that data root is absolute path
		if (stripos(PHP_OS, 'win') === 0) {
			if (strpos($submissionVars['dataroot'], ':') !== 1) {
				$save_value = $this->sanitizeInputValue($submissionVars['dataroot']);
				$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:error:relative_path', [$save_value]));

				return false;
			}
		} else {
			if (!str_starts_with($submissionVars['dataroot'], '/')) {
				$save_value = $this->sanitizeInputValue($submissionVars['dataroot']);
				$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:error:relative_path', [$save_value]));

				return false;
			}
		}

		// check that data root exists
		if (!is_dir($submissionVars['dataroot'])) {
			$save_value = $this->sanitizeInputValue($submissionVars['dataroot']);
			$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:error:datadirectoryexists', [$save_value]));

			return false;
		}

		// check that data root is writable
		if (!is_writable($submissionVars['dataroot'])) {
			$save_value = $this->sanitizeInputValue($submissionVars['dataroot']);
			$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:error:writedatadirectory', [$save_value]));

			return false;
		}

		// check that data root is not subdirectory of Elgg root
		if (stripos($submissionVars['dataroot'], Paths::project()) === 0) {
			$save_value = $this->sanitizeInputValue($submissionVars['dataroot']);
			$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:error:locationdatadirectory', [$save_value]));

			return false;
		}

		// according to postgres documentation: SQL identifiers and key words must
		// begin with a letter (a-z, but also letters with diacritical marks and
		// non-Latin letters) or an underscore (_). Subsequent characters in an
		// identifier or key word can be letters, underscores, digits (0-9), or dollar signs ($).
		// Refs #4994
		if (!empty($submissionVars['dbprefix']) && !preg_match('/^[a-zA-Z_][\w]*$/', $submissionVars['dbprefix'])) {
			$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:error:database_prefix'));

			return false;
		}
		
		$config = new DbConfig((object) [
			'dbhost' => $submissionVars['dbhost'],
			'dbport' => $submissionVars['dbport'],
			'dbuser' => $submissionVars['dbuser'],
			'dbpass' => $submissionVars['dbpassword'],
			'dbname' => $submissionVars['dbname'],
			'dbencoding' => 'utf8mb4',
		]);
		
		return $this->checkDatabaseSettings($config);
	}

	/**
	 * Confirm the settings for the database
	 *
	 * @param DbConfig $config database configuration
	 *
	 * @return bool
	 */
	protected function checkDatabaseSettings(DbConfig $config): bool {
		$app = $this->getApp();
		
		$db = new Database($config, $app->internal_services->queryCache, $app->internal_services->config);

		try {
			$db->getConnection('read')->executeQuery('SELECT 1');
		} catch (DatabaseException $e) {
			if (str_starts_with($e->getMessage(), "Elgg couldn't connect")) {
				$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:error:databasesettings'));
			} else {
				$database = (string) elgg_extract('database', $config->getConnectionConfig());
				$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:error:nodatabase', [$database]));
			}

			return false;
		}

		// check MySQL version
		$version = $db->getServerVersion();
		$min_version = $db->isMariaDB() ? self::MARIADB_MINIMAL_VERSION : self::MYSQL_MINIMAL_VERSION;
		
		if (version_compare($version, $min_version, '<')) {
			$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:error:database_version', [$min_version, $version]));

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
	 */
	protected function createSettingsFile(array $params): bool {
		$app = $this->getApp();

		$template = file_get_contents(Paths::elgg() . 'elgg-config/settings.example.php');
		if (!$template) {
			$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:error:readsettingsphp'));

			return false;
		}

		foreach ($params as $k => $v) {
			// do some sanitization
			switch ($k) {
				case 'dataroot':
					$v = Paths::sanitize($v);
					break;
				case 'dbpassword':
					$v = addslashes($v);
					break;
			}

			$template = str_replace('{{' . $k . '}}', $v, $template);
		}

		$result = file_put_contents(Config::resolvePath(), $template);
		if ($result === false) {
			$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:error:writesettingphp'));

			return false;
		}

		$config = (object) [
			'dbhost' => elgg_extract('dbhost', $params, 'localhost'),
			'dbport' => elgg_extract('dbport', $params, 3306),
			'dbuser' => elgg_extract('dbuser', $params),
			'dbpass' => elgg_extract('dbpassword', $params),
			'dbname' => elgg_extract('dbname', $params),
			'dbencoding' => elgg_extract('dbencoding', $params, 'utf8mb4'),
			'dbprefix' => elgg_extract('dbprefix', $params, 'elgg_'),
		];

		$dbConfig = new DbConfig($config);
		$this->getApp()->internal_services->set('dbConfig', $dbConfig);
		$this->getApp()->internal_services->db->resetConnections($dbConfig);

		return true;
	}

	/**
	 * Bootstrap database connection before entire engine is available
	 *
	 * @return bool
	 */
	protected function connectToDatabase(): bool {
		try {
			$app = $this->getApp();
			$app->internal_services->db->setupConnections();
		} catch (DatabaseException $e) {
			$app->internal_services->system_messages->addErrorMessage($e->getMessage());

			return false;
		}

		return true;
	}

	/**
	 * Create the database tables
	 *
	 * @return bool
	 */
	protected function installDatabase(): bool {
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
	protected function createDataDirectory(array &$submissionVars, array $formVars): bool {
		// did the user have option of Elgg creating the data directory
		if ($formVars['dataroot']['type'] !== 'combo') {
			return true;
		}

		// did the user select the option
		if ($submissionVars['dataroot'] !== 'dataroot-checkbox') {
			return true;
		}

		$dir = \Elgg\Project\Paths::sanitize($submissionVars['path']) . 'data';
		if (file_exists($dir) || mkdir($dir, 0755)) {
			$submissionVars['dataroot'] = $dir;
			if (!file_exists("{$dir}/.htaccess")) {
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
	protected function validateSettingsVars(array $submissionVars, array $formVars): bool {
		$app = $this->getApp();

		foreach ($formVars as $field => $info) {
			$submissionVars[$field] = trim($submissionVars[$field]);
			if ($info['required'] === true && $submissionVars[$field] === '') {
				$name = elgg_echo("install:settings:label:{$field}");
				$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:error:requiredfield', [$name]));

				return false;
			}
		}

		// check that email address is email address
		if ($submissionVars['siteemail'] && !elgg_is_valid_email((string) $submissionVars['siteemail'])) {
			$save_value = $this->sanitizeInputValue($submissionVars['siteemail']);
			$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:error:emailaddress', [$save_value]));

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
	 */
	protected function saveSiteSettings(array $submissionVars): bool {
		$app = $this->getApp();

		$site = elgg_get_site_entity();

		if (!$site->guid) {
			$site = new \ElggSite();
			$site->name = strip_tags($submissionVars['sitename']);
			$site->access_id = ACCESS_PUBLIC;
			$site->email = $submissionVars['siteemail'];
			$site->save();
		}

		if ($site->guid !== 1) {
			$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:error:createsite'));

			return false;
		}

		$app->internal_services->config->site = $site;

		$sets = [
			'installed' => time(),
			'simplecache_enabled' => 1,
			'system_cache_enabled' => 1,
			'simplecache_minify_js' => true,
			'simplecache_minify_css' => true,
			'lastcache' => time(),
			'language' => 'en',
			'default_access' => $submissionVars['siteaccess'],
			'allow_registration' => false,
			'require_admin_validation' => false,
			'walled_garden' => false,
			'allow_user_default_access' => '',
			'default_limit' => 10,
		];

		foreach ($sets as $key => $value) {
			elgg_save_config($key, $value);
		}

		try {
			_elgg_services()->plugins->generateEntities();

			$app->internal_services->reset('plugins');
			
			if (elgg_extract('activate_plugins', $submissionVars, true)) {
				$plugins = $app->internal_services->plugins->find('all');
	
				foreach ($plugins as $plugin) {
					$plugin_config = $plugin->getStaticConfig('plugin', []);
					if (!elgg_extract('activate_on_install', $plugin_config, false)) {
						continue;
					}
					
					try {
						$plugin->activate();
					} catch (PluginException $e) {
						// do nothing
					}
				}
			}

			// Wo don't need to run upgrades on new installations
			$app->internal_services->events->unregisterHandler('create:after', 'object', \Elgg\Upgrade\CreateAdminNoticeHandler::class);
			$upgrades = $app->internal_services->upgradeLocator->locate();
			foreach ($upgrades as $upgrade) {
				$upgrade->setCompleted();
			}
		} catch (\Exception $e) {
			$app->internal_services->logger->log(\Psr\Log\LogLevel::ERROR, $e);
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
	 */
	protected function validateAdminVars(array $submissionVars, array $formVars): bool {

		$app = $this->getApp();

		foreach ($formVars as $field => $info) {
			if ($info['required'] === true && !$submissionVars[$field]) {
				$name = elgg_echo("install:admin:label:{$field}");
				$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:error:requiredfield', [$name]));

				return false;
			}
		}

		if ($submissionVars['password1'] !== $submissionVars['password2']) {
			$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:admin:password:mismatch'));

			return false;
		}

		if (trim($submissionVars['password1']) === '') {
			$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:admin:password:empty'));

			return false;
		}

		$minLength = $app->internal_services->configTable->get('min_password_length');
		if (strlen($submissionVars['password1']) < $minLength) {
			$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:admin:password:tooshort'));

			return false;
		}

		// check that email address is email address
		if ($submissionVars['email'] && !elgg_is_valid_email((string) $submissionVars['email'])) {
			$save_value = $this->sanitizeInputValue($submissionVars['email']);
			$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:error:emailaddress', [$save_value]));

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
	 */
	protected function createAdminAccount(array $submissionVars, bool $login = false): bool {
		$app = $this->getApp();

		try {
			$user = elgg_register_user([
				'username' => $submissionVars['username'],
				'password' => $submissionVars['password1'],
				'name' => $submissionVars['displayname'],
				'email' => $submissionVars['email'],
			]);
		} catch (RegistrationException $e) {
			$app->internal_services->system_messages->addErrorMessage($e->getMessage());

			return false;
		}

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($app, $user) {
			if (!$user->makeAdmin()) {
				$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:error:adminaccess'));
			}
		});
		
		// add validation data to satisfy user validation plugins
		$user->validated = true;
		$user->validated_method = 'admin_user';

		if (!$login) {
			return true;
		}

		try {
			elgg_login($user);
		} catch (LoginException $ex) {
			$app->internal_services->system_messages->addErrorMessage(elgg_echo('install:error:adminlogin'));
			
			return false;
		}

		return true;
	}
	
	/**
	 * Sanitize input to help prevent XSS
	 *
	 * @param mixed $input_value the input to sanitize
	 *
	 * @return mixed
	 */
	protected function sanitizeInputValue($input_value) {
		if (is_array($input_value)) {
			return array_map([$this, __FUNCTION__], $input_value);
		}
		
		if (!is_string($input_value)) {
			return $input_value;
		}
		
		return htmlspecialchars($input_value);
	}
}
