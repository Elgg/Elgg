<?php

use Elgg\Filesystem\Directory;

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
 *
 * @package    Elgg.Core
 * @subpackage Installer
 */
class ElggInstaller {
	
	protected $steps = [
		'welcome',
		'requirements',
		'database',
		'settings',
		'admin',
		'complete',
		];

	protected $status = [
		'config' => false,
		'database' => false,
		'settings' => false,
		'admin' => false,
	];

	protected $isAction = false;

	protected $autoLogin = true;

	private $view_path = '';

	/**
	 * Global Elgg configuration
	 *
	 * @var \stdClass
	 */
	private $CONFIG;

	/**
	 * Constructor bootstraps the Elgg engine
	 */
	public function __construct() {
		global $CONFIG;
		if (!isset($CONFIG)) {
			$CONFIG = new stdClass;
		}
		
		global $_ELGG;
		if (!isset($_ELGG)) {
			$_ELGG = new stdClass;
		}

		$this->CONFIG = $CONFIG;

		$this->isAction = isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST';

		$this->bootstrapConfig();

		$this->bootstrapEngine();

		_elgg_services()->views->view_path = $this->view_path;
		
		_elgg_services()->setValue('session', \ElggSession::getMock());

		elgg_set_viewtype('installation');

		set_error_handler('_elgg_php_error_handler');
		set_exception_handler('_elgg_php_exception_handler');

		_elgg_services()->config->set('simplecache_enabled', false);
		_elgg_services()->translator->registerTranslations(\Elgg\Application::elggDir()->getPath("/install/languages/"), true);
		_elgg_services()->views->registerPluginViews(\Elgg\Application::elggDir()->getPath("/"));
	}
	
	/**
	 * Dispatches a request to one of the step controllers
	 *
	 * @param string $step The installation step to run
	 *
	 * @return void
	 * @throws InstallationException
	 */
	public function run($step) {
		global $CONFIG;
		
		// language needs to be set before the first call to elgg_echo()
		$CONFIG->language = 'en';

		// check if this is a URL rewrite test coming in
		$this->processRewriteTest();

		if (!in_array($step, $this->getSteps())) {
			$msg = _elgg_services()->translator->translate('InstallationException:UnknownStep', [$step]);
			throw new InstallationException($msg);
		}

		$this->setInstallStatus();
	
		$this->checkInstallCompletion($step);

		// check if this is an install being resumed
		$this->resumeInstall($step);

		$this->finishBootstrapping($step);

		$params = $this->getPostVariables();

		$this->$step($params);
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
	 * If the settings.php file exists, it will use that rather than the parameters
	 * passed to this function.
	 *
	 * @param array $params         Array of key value pairs
	 * @param bool  $createHtaccess Should .htaccess be created
	 *
	 * @return void
	 * @throws InstallationException
	 */
	public function batchInstall(array $params, $createHtaccess = false) {
		

		restore_error_handler();
		restore_exception_handler();

		$defaults = [
			'dbhost' => 'localhost',
			'dbprefix' => 'elgg_',
			'language' => 'en',
			'siteaccess' => ACCESS_PUBLIC,
			'site_guid' => 1,
		];
		$params = array_merge($defaults, $params);

		$requiredParams = [
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
		foreach ($requiredParams as $key) {
			if (empty($params[$key])) {
				$msg = _elgg_services()->translator->translate('install:error:requiredfield', [$key]);
				throw new InstallationException($msg);
			}
		}

		// password is passed in once
		$params['password1'] = $params['password2'] = $params['password'];

		if ($createHtaccess) {
			$rewriteTester = new ElggRewriteTester();
			if (!$rewriteTester->createHtaccess($params['wwwroot'], Directory\Local::root()->getPath())) {
				throw new InstallationException(_elgg_services()->translator->translate('install:error:htaccess'));
			}
		}

		$this->setInstallStatus();

		if (!$this->status['config']) {
			if (!$this->createSettingsFile($params)) {
				throw new InstallationException(_elgg_services()->translator->translate('install:error:settings'));
			}
		}

		if (!$this->connectToDatabase()) {
			throw new InstallationException(_elgg_services()->translator->translate('install:error:databasesettings'));
		}

		if (!$this->status['database']) {
			if (!$this->installDatabase()) {
				throw new InstallationException(_elgg_services()->translator->translate('install:error:cannotloadtables'));
			}
		}

		// load remaining core libraries
		$this->finishBootstrapping('settings');

		if (!$this->saveSiteSettings($params)) {
			throw new InstallationException(_elgg_services()->translator->translate('install:error:savesitesettings'));
		}

		if (!$this->createAdminAccount($params)) {
			throw new InstallationException(_elgg_services()->translator->translate('install:admin:cannot_create'));
		}
	}

	/**
	 * Renders the data passed by a controller
	 *
	 * @param string $step The current step
	 * @param array  $vars Array of vars to pass to the view
	 *
	 * @return void
	 */
	protected function render($step, $vars = []) {
		$vars['next_step'] = $this->getNextStep($step);

		$title = _elgg_services()->translator->translate("install:$step");
		$body = elgg_view("install/pages/$step", $vars);
				
		echo elgg_view_page(
				$title,
				$body,
				'default',
				[
					'step' => $step,
					'steps' => $this->getSteps(),
					]
				);
		exit;
	}

	/**
	 * Step controllers
	 */

	/**
	 * Welcome controller
	 *
	 * @param array $vars Not used
	 *
	 * @return void
	 */
	protected function welcome($vars) {
		$this->render('welcome');
	}

	/**
	 * Requirements controller
	 *
	 * Checks version of php, libraries, permissions, and rewrite rules
	 *
	 * @param array $vars Vars
	 *
	 * @return void
	 */
	protected function requirements($vars) {

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
		$report['database'] = [[
			'severity' => 'info',
			'message' => _elgg_services()->translator->translate('install:check:database')
		]];

		// any failures?
		$numFailures = $this->countNumConditions($report, 'failure');

		// any warnings
		$numWarnings = $this->countNumConditions($report, 'warning');


		$params = [
			'report' => $report,
			'num_failures' => $numFailures,
			'num_warnings' => $numWarnings,
		];

		$this->render('requirements', $params);
	}

	/**
	 * Database set up controller
	 *
	 * Creates the settings.php file and creates the database tables
	 *
	 * @param array $submissionVars Submitted form variables
	 *
	 * @return void
	 */
	protected function database($submissionVars) {

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
				'value' => _elgg_services()->config->getSiteUrl(),
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
			$this->isAction = true;
		}

		if ($this->isAction) {
			do {
				// only create settings file if it doesn't exist
				if (!$this->checkSettingsFile()) {
					if (!$this->validateDatabaseVars($submissionVars, $formVars)) {
						// error so we break out of action and serve same page
						break;
					}

					if (!$this->createSettingsFile($submissionVars)) {
						break;
					}
				}

				// check db version and connect
				if (!$this->connectToDatabase()) {
					break;
				}

				if (!$this->installDatabase()) {
					break;
				}

				system_message(_elgg_services()->translator->translate('install:success:database'));

				$this->continueToNextStep('database');
			} while (false);  // PHP doesn't support breaking out of if statements
		}

		$formVars = $this->makeFormSticky($formVars, $submissionVars);

		$params = ['variables' => $formVars,];

		if ($this->checkSettingsFile()) {
			// settings file exists and we're here so failed to create database
			$params['failure'] = true;
		}

		$this->render('database', $params);
	}

	/**
	 * Site settings controller
	 *
	 * Sets the site name, URL, data directory, etc.
	 *
	 * @param array $submissionVars Submitted vars
	 *
	 * @return void
	 */
	protected function settings($submissionVars) {
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

		// if Apache, we give user option of having Elgg create data directory
		//if (ElggRewriteTester::guessWebServer() == 'apache') {
		//	$formVars['dataroot']['type'] = 'combo';
		//	$GLOBALS['_ELGG']->translations['en']['install:settings:help:dataroot'] =
		//			$GLOBALS['_ELGG']->translations['en']['install:settings:help:dataroot:apache'];
		//}

		if ($this->isAction) {
			do {
				//if (!$this->createDataDirectory($submissionVars, $formVars)) {
				//	break;
				//}

				if (!$this->validateSettingsVars($submissionVars, $formVars)) {
					break;
				}

				if (!$this->saveSiteSettings($submissionVars)) {
					break;
				}

				system_message(_elgg_services()->translator->translate('install:success:settings'));

				$this->continueToNextStep('settings');
			} while (false);  // PHP doesn't support breaking out of if statements
		}

		$formVars = $this->makeFormSticky($formVars, $submissionVars);

		$this->render('settings', ['variables' => $formVars]);
	}

	/**
	 * Admin account controller
	 *
	 * Creates an admin user account
	 *
	 * @param array $submissionVars Submitted vars
	 *
	 * @return void
	 */
	protected function admin($submissionVars) {
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
		
		if ($this->isAction) {
			do {
				if (!$this->validateAdminVars($submissionVars, $formVars)) {
					break;
				}

				if (!$this->createAdminAccount($submissionVars, $this->autoLogin)) {
					break;
				}

				system_message(_elgg_services()->translator->translate('install:success:admin'));

				$this->continueToNextStep('admin');
			} while (false);  // PHP doesn't support breaking out of if statements
		}

		// bit of a hack to get the password help to show right number of characters
		
		$lang = _elgg_services()->translator->getCurrentLanguage();
		$GLOBALS['_ELGG']->translations[$lang]['install:admin:help:password1'] =
				sprintf($GLOBALS['_ELGG']->translations[$lang]['install:admin:help:password1'],
				$this->CONFIG->min_password_length);

		$formVars = $this->makeFormSticky($formVars, $submissionVars);

		$this->render('admin', ['variables' => $formVars]);
	}

	/**
	 * Controller for last step
	 *
	 * @return void
	 */
	protected function complete() {

		$params = [];
		if ($this->autoLogin) {
			$params['destination'] = 'admin';
		} else {
			$params['destination'] = 'index.php';
		}

		$this->render('complete', $params);
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
	 * @return void
	 */
	protected function continueToNextStep($currentStep) {
		$this->isAction = false;
		forward($this->getNextStepUrl($currentStep));
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
	 */
	protected function getNextStepUrl($currentStep) {
		$nextStep = $this->getNextStep($currentStep);
		return _elgg_services()->config->getSiteUrl() . "install.php?step=$nextStep";
	}

	/**
	 * Check the different install steps for completion
	 *
	 * @return void
	 * @throws InstallationException
	 */
	protected function setInstallStatus() {
		if (!is_readable($this->getSettingsPath())) {
			return;
		}

		$this->loadSettingsFile();

		$this->status['config'] = true;

		// must be able to connect to database to jump install steps
		$dbSettingsPass = $this->checkDatabaseSettings(
				$this->CONFIG->dbuser,
				$this->CONFIG->dbpass,
				$this->CONFIG->dbname,
				$this->CONFIG->dbhost
				);

		if ($dbSettingsPass == false) {
			return;
		}

		if (!include_once(\Elgg\Application::elggDir()->getPath("engine/lib/database.php"))) {
			throw new InstallationException(_elgg_services()->translator->translate('InstallationException:MissingLibrary', ['database.php']));
		}

		// check that the config table has been created
		$query = "show tables";
		$result = _elgg_services()->db->getData($query);
		if ($result) {
			foreach ($result as $table) {
				$table = (array) $table;
				if (in_array("{$this->CONFIG->dbprefix}config", $table)) {
					$this->status['database'] = true;
				}
			}
			if ($this->status['database'] == false) {
				return;
			}
		} else {
			// no tables
			return;
		}

		// check that the config table has entries
		$query = "SELECT COUNT(*) AS total FROM {$this->CONFIG->dbprefix}config";
		$result = _elgg_services()->db->getData($query);
		if ($result && $result[0]->total > 0) {
			$this->status['settings'] = true;
		} else {
			return;
		}

		// check that the users entity table has an entry
		$query = "SELECT COUNT(*) AS total FROM {$this->CONFIG->dbprefix}users_entity";
		$result = _elgg_services()->db->getData($query);
		if ($result && $result[0]->total > 0) {
			$this->status['admin'] = true;
		} else {
			return;
		}
	}

	/**
	 * Security check to ensure the installer cannot be run after installation
	 * has finished. If this is detected, the viewer is sent to the front page.
	 *
	 * @param string $step Installation step to check against
	 *
	 * @return void
	 */
	protected function checkInstallCompletion($step) {
		if ($step != 'complete') {
			if (!in_array(false, $this->status)) {
				// install complete but someone is trying to view an install page
				forward();
			}
		}
	}

	/**
	 * Check if this is a case of a install being resumed and figure
	 * out where to continue from. Returns the best guess on the step.
	 *
	 * @param string $step Installation step to resume from
	 *
	 * @return string
	 */
	protected function resumeInstall($step) {
		// only do a resume from the first step
		if ($step !== 'welcome') {
			return;
		}

		if ($this->status['database'] == false) {
			return;
		}

		if ($this->status['settings'] == false) {
			forward("install.php?step=settings");
		}

		if ($this->status['admin'] == false) {
			forward("install.php?step=admin");
		}

		// everything appears to be set up
		forward("install.php?step=complete");
	}

	/**
	 * Bootstraping
	 */

	/**
	 * Load the essential libraries of the engine
	 *
	 * @return void
	 */
	protected function bootstrapEngine() {
		$config = new \Elgg\Config($this->CONFIG);
		$services = new \Elgg\Di\ServiceProvider($config);
		(new \Elgg\Application($services))->loadCore();
	}

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

		$dbIndex = array_search('database', $this->getSteps());
		$settingsIndex = array_search('settings', $this->getSteps());
		$adminIndex = array_search('admin', $this->getSteps());
		$completeIndex = array_search('complete', $this->getSteps());
		$stepIndex = array_search($step, $this->getSteps());

		// To log in the user, we need to use the Elgg core session handling.
		// Otherwise, use default php session handling
		$useElggSession = ($stepIndex == $adminIndex && $this->isAction) ||
				$stepIndex == $completeIndex;
		if (!$useElggSession) {
			session_name('Elgg_install');
			session_start();
		}

		if ($stepIndex > $dbIndex) {
			// once the database has been created, load rest of engine
			
			$lib_dir = \Elgg\Application::elggDir()->chroot('/engine/lib/');

			$this->loadSettingsFile();

			$lib_files = [
				// these want to be loaded first apparently?
				'autoloader.php',
				'database.php',
				'actions.php',

				'admin.php',
				'annotations.php',
				'cron.php',
				'entities.php',
				'extender.php',
				'filestore.php',
				'group.php',
				'mb_wrapper.php',
				'memcache.php',
				'metadata.php',
				'metastrings.php',
				'navigation.php',
				'notification.php',
				'objects.php',
				'pagehandler.php',
				'pam.php',
				'plugins.php',
				'private_settings.php',
				'relationships.php',
				'river.php',
				'sites.php',
				'statistics.php',
				'tags.php',
				'user_settings.php',
				'users.php',
				'upgrade.php',
				'widgets.php',
			];

			foreach ($lib_files as $file) {
				if (!include_once($lib_dir->getPath($file))) {
					throw new InstallationException('InstallationException:MissingLibrary', [$file]);
				}
			}

			_elgg_services()->db->setupConnections();
			_elgg_services()->translator->registerTranslations(\Elgg\Application::elggDir()->getPath("/languages/"));
			$this->CONFIG->language = 'en';

			if ($stepIndex > $settingsIndex) {
				$this->CONFIG->site_guid = 1;
				$this->CONFIG->site = get_entity(1);
				_elgg_services()->config->getCookieConfig();
				_elgg_session_boot();
			}

			_elgg_services()->events->trigger('init', 'system');
		}
	}

	/**
	 * Set up configuration variables
	 *
	 * @return void
	 */
	protected function bootstrapConfig() {
		$this->CONFIG->installer_running = true;

		if (empty($this->CONFIG->dbencoding)) {
			$this->CONFIG->dbencoding = 'utf8mb4';
		}
		$this->CONFIG->wwwroot = $this->getBaseUrl();
		$this->CONFIG->url = $this->CONFIG->wwwroot;
		$this->CONFIG->path = Directory\Local::root()->getPath('/');
		$this->view_path = $this->CONFIG->path . 'views/';
		$this->CONFIG->pluginspath = $this->CONFIG->path . 'mod/';
		$this->CONFIG->context = [];
		$this->CONFIG->entity_types = ['group', 'object', 'site', 'user'];

		// required by elgg_view_page()
		$this->CONFIG->sitename = '';
		$this->CONFIG->sitedescription = '';

		// required by Elgg\Config::get
		$this->CONFIG->site_guid = 1;
	}
	
	/**
	 * @return bool Whether the install process is encrypted.
	 */
	private function isHttps() {
		return (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ||
			(!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
	}

	/**
	 * Get the best guess at the base URL
	 *
	 * @note Cannot use current_page_url() because it depends on $this->CONFIG->wwwroot
	 * @todo Should this be a core function?
	 *
	 * @return string
	 */
	protected function getBaseUrl() {
		$protocol = $this->isHttps() ? 'https' : 'http';
		
		if (isset($_SERVER["SERVER_PORT"])) {
			$port = ':' . $_SERVER["SERVER_PORT"];
		} else {
			$port = '';
		}
		if ($port == ':80' || $port == ':443') {
			$port = '';
		}
		$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
		$cutoff = strpos($uri, 'install.php');
		$uri = substr($uri, 0, $cutoff);
		$serverName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';

		return "$protocol://{$serverName}$port{$uri}";
	}

	/**
	 * Load settings.php
	 *
	 * @return void
	 * @throws InstallationException
	 */
	protected function loadSettingsFile() {
		if (!include_once($this->getSettingsPath())) {
			throw new InstallationException(_elgg_services()->translator->translate('InstallationException:CannotLoadSettings'));
		}
	}

	/**
	 * Action handling methods
	 */

	/**
	 * Return an associative array of post variables
	 * (could be selective based on expected variables)
	 *
	 * Does not filter as person installing the site should not be attempting
	 * XSS attacks. If filtering is added, it should not be done for passwords.
	 *
	 * @return array
	 */
	protected function getPostVariables() {
		$vars = [];
		foreach ($_POST as $k => $v) {
			$vars[$k] = $v;
		}
		return $vars;
	}

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
		$root = Directory\Local::root()->getPath();
		$abs_path = \Elgg\Application::elggDir()->getPath('elgg-config');

		if (0 === strpos($abs_path, $root)) {
			$relative_path = substr($abs_path, strlen($root));
		} else {
			$relative_path = $abs_path;
		}
		$relative_path = rtrim($relative_path, '/\\');

		$writable = is_writable(Directory\Local::root()->getPath('elgg-config'));
		if (!$writable) {
			$report['settings'] = [
				[
					'severity' => 'failure',
					'message' => _elgg_services()->translator->translate('install:check:installdir', [$relative_path]),
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
		if (!file_exists($this->getSettingsPath())) {
			return false;
		}

		if (!is_readable($this->getSettingsPath())) {
			$report['settings'] = [
				[
					'severity' => 'failure',
					'message' => _elgg_services()->translator->translate('install:check:readsettings'),
				]
			];
		}
		
		return true;
	}
	
	/**
	 * Returns the path to the root settings.php file.
	 *
	 * @return string
	 */
	private function getSettingsPath() {
		return Directory\Local::root()->getPath("elgg-config/settings.php");
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

		$min_php_version = '5.6.0';
		if (version_compare(PHP_VERSION, $min_php_version, '<')) {
			$phpReport[] = [
				'severity' => 'failure',
				'message' => _elgg_services()->translator->translate('install:check:php:version', [$min_php_version, PHP_VERSION])
			];
		}

		$this->checkPhpExtensions($phpReport);

		$this->checkPhpDirectives($phpReport);

		if (count($phpReport) == 0) {
			$phpReport[] = [
				'severity' => 'pass',
				'message' => _elgg_services()->translator->translate('install:check:php:success')
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
					'severity' => 'failure',
					'message' => _elgg_services()->translator->translate('install:check:php:extension', [$extension])
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
					'message' => _elgg_services()->translator->translate('install:check:php:extension:recommend', [$extension])
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
				'message' => _elgg_services()->translator->translate("install:check:php:open_basedir")
			];
		}

		if (ini_get('safe_mode')) {
			$phpReport[] = [
				'severity' => 'warning',
				'message' => _elgg_services()->translator->translate("install:check:php:safe_mode")
			];
		}

		if (ini_get('arg_separator.output') !== '&') {
			$separator = htmlspecialchars(ini_get('arg_separator.output'));
			$msg = _elgg_services()->translator->translate("install:check:php:arg_separator", [$separator]);
			$phpReport[] = [
				'severity' => 'failure',
				'message' => $msg,
			];
		}

		if (ini_get('register_globals')) {
			$phpReport[] = [
				'severity' => 'failure',
				'message' => _elgg_services()->translator->translate("install:check:php:register_globals")
			];
		}

		if (ini_get('session.auto_start')) {
			$phpReport[] = [
				'severity' => 'failure',
				'message' => _elgg_services()->translator->translate("install:check:php:session.auto_start")
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
	protected function checkRewriteRules(&$report) {
		$tester = new ElggRewriteTester();
		$url = _elgg_services()->config->getSiteUrl() . "rewrite.php";
		$report['rewrite'] = [$tester->run($url, Directory\Local::root()->getPath())];
	}

	/**
	 * Check if the request is coming from the URL rewrite test on the
	 * requirements page.
	 *
	 * @return void
	 */
	protected function processRewriteTest() {
		if (strpos($_SERVER['REQUEST_URI'], 'rewrite.php') !== false) {
			echo \Elgg\Application::REWRITE_TEST_OUTPUT;
			exit;
		}
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
	 */
	protected function validateDatabaseVars($submissionVars, $formVars) {

		foreach ($formVars as $field => $info) {
			if ($info['required'] == true && !$submissionVars[$field]) {
				$name = _elgg_services()->translator->translate("install:database:label:$field");
				register_error(_elgg_services()->translator->translate('install:error:requiredfield', [$name]));
				return false;
			}
		}

		// check that data root is absolute path
		if (stripos(PHP_OS, 'win') === 0) {
			if (strpos($submissionVars['dataroot'], ':') !== 1) {
				$msg = _elgg_services()->translator->translate('install:error:relative_path', [$submissionVars['dataroot']]);
				register_error($msg);
				return false;
			}
		} else {
			if (strpos($submissionVars['dataroot'], '/') !== 0) {
				$msg = _elgg_services()->translator->translate('install:error:relative_path', [$submissionVars['dataroot']]);
				register_error($msg);
				return false;
			}
		}

		// check that data root exists
		if (!is_dir($submissionVars['dataroot'])) {
			$msg = _elgg_services()->translator->translate('install:error:datadirectoryexists', [$submissionVars['dataroot']]);
			register_error($msg);
			return false;
		}

		// check that data root is writable
		if (!is_writable($submissionVars['dataroot'])) {
			$msg = _elgg_services()->translator->translate('install:error:writedatadirectory', [$submissionVars['dataroot']]);
			register_error($msg);
			return false;
		}

		if (!isset($this->CONFIG->data_dir_override) || !$this->CONFIG->data_dir_override) {
			// check that data root is not subdirectory of Elgg root
			if (stripos($submissionVars['dataroot'], $this->CONFIG->path) === 0) {
				$msg = _elgg_services()->translator->translate('install:error:locationdatadirectory', [$submissionVars['dataroot']]);
				register_error($msg);
				return false;
			}
		}

		// according to postgres documentation: SQL identifiers and key words must
		// begin with a letter (a-z, but also letters with diacritical marks and
		// non-Latin letters) or an underscore (_). Subsequent characters in an
		// identifier or key word can be letters, underscores, digits (0-9), or dollar signs ($).
		// Refs #4994
		if (!preg_match("/^[a-zA-Z_][\w]*$/", $submissionVars['dbprefix'])) {
			register_error(_elgg_services()->translator->translate('install:error:database_prefix'));
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
		$config = new \Elgg\Database\Config((object) [
			'dbhost' => $host,
			'dbuser' => $user,
			'dbpass' => $password,
			'dbname' => $dbname,
			'dbencoding' => 'utf8mb4',
		]);
		$db = new \Elgg\Database($config);

		try {
			$db->getDataRow("SELECT 1");
		} catch (DatabaseException $e) {
			if (0 === strpos($e->getMessage(), "Elgg couldn't connect")) {
				register_error(_elgg_services()->translator->translate('install:error:databasesettings'));
			} else {
				register_error(_elgg_services()->translator->translate('install:error:nodatabase', [$dbname]));
			}
			return false;
		}

		// check MySQL version
		$version = $db->getServerVersion(\Elgg\Database\Config::READ_WRITE);
		if (version_compare($version, '5.5.3', '<')) {
			register_error(_elgg_services()->translator->translate('install:error:oldmysql2', [$version]));
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
	protected function createSettingsFile($params) {
		$template = \Elgg\Application::elggDir()->getContents("elgg-config/settings.example.php");
		if (!$template) {
			register_error(_elgg_services()->translator->translate('install:error:readsettingsphp'));
			return false;
		}

		foreach ($params as $k => $v) {
			$template = str_replace("{{" . $k . "}}", $v, $template);
		}

		$result = file_put_contents($this->getSettingsPath(), $template);
		if (!$result) {
			register_error(_elgg_services()->translator->translate('install:error:writesettingphp'));
			return false;
		}

		return true;
	}

	/**
	 * Bootstrap database connection before entire engine is available
	 *
	 * @return bool
	 */
	protected function connectToDatabase() {
		if (!include_once($this->getSettingsPath())) {
			register_error('Elgg could not load the settings file. It does not exist or there is a file permissions issue.');
			return false;
		}

		if (!include_once(\Elgg\Application::elggDir()->getPath("engine/lib/database.php"))) {
			register_error('Could not load database.php');
			return false;
		}

		try {
			_elgg_services()->db->setupConnections();
		} catch (DatabaseException $e) {
			register_error($e->getMessage());
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
			_elgg_services()->db->runSqlScript(\Elgg\Application::elggDir()->getPath("/engine/schema/mysql.sql"));
		} catch (Exception $e) {
			$msg = $e->getMessage();
			if (strpos($msg, 'already exists')) {
				$msg = _elgg_services()->translator->translate('install:error:tables_exist');
			}
			register_error($msg);
			return false;
		}

		return true;
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

		$dir = sanitise_filepath($submissionVars['path']) . 'data';
		if (file_exists($dir) || mkdir($dir, 0700)) {
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
		foreach ($formVars as $field => $info) {
			$submissionVars[$field] = trim($submissionVars[$field]);
			if ($info['required'] == true && $submissionVars[$field] === '') {
				$name = _elgg_services()->translator->translate("install:settings:label:$field");
				register_error(_elgg_services()->translator->translate('install:error:requiredfield', [$name]));
				return false;
			}
		}

		// check that email address is email address
		if ($submissionVars['siteemail'] && !is_email_address($submissionVars['siteemail'])) {
			$msg = _elgg_services()->translator->translate('install:error:emailaddress', [$submissionVars['siteemail']]);
			register_error($msg);
			return false;
		}

		// @todo check that url is a url
		// @note filter_var cannot be used because it doesn't work on international urls

		return true;
	}

	/**
	 * Initialize the site including site entity, plugins, and configuration
	 *
	 * @param array $submissionVars Submitted vars
	 *
	 * @return bool
	 */
	protected function saveSiteSettings($submissionVars) {
		$site = new ElggSite();
		$site->name = strip_tags($submissionVars['sitename']);
		$site->access_id = ACCESS_PUBLIC;
		$site->email = $submissionVars['siteemail'];
		$guid = $site->save();

		if ($guid !== 1) {
			register_error(_elgg_services()->translator->translate('install:error:createsite'));
			return false;
		}

		// bootstrap site info
		$this->CONFIG->site_guid = 1;
		$this->CONFIG->site = $site;

		_elgg_services()->configTable->set('installed', time());
		_elgg_services()->configTable->set('version', elgg_get_version());
		_elgg_services()->configTable->set('simplecache_enabled', 1);
		_elgg_services()->configTable->set('system_cache_enabled', 1);
		_elgg_services()->configTable->set('simplecache_lastupdate', time());

		// new installations have run all the upgrades
		$upgrades = elgg_get_upgrade_files(\Elgg\Application::elggDir()->getPath("/engine/lib/upgrades/"));
		_elgg_services()->configTable->set('processed_upgrades', $upgrades);

		_elgg_services()->configTable->set('view', 'default');
		_elgg_services()->configTable->set('language', 'en');
		_elgg_services()->configTable->set('default_access', $submissionVars['siteaccess']);
		_elgg_services()->configTable->set('allow_registration', true);
		_elgg_services()->configTable->set('walled_garden', false);
		_elgg_services()->configTable->set('allow_user_default_access', '');
		_elgg_services()->configTable->set('default_limit', 10);
		_elgg_services()->configTable->set('security_protect_upgrade', true);
		_elgg_services()->configTable->set('security_notify_admins', true);
		_elgg_services()->configTable->set('security_notify_user_password', true);
		_elgg_services()->configTable->set('security_email_require_password', true);

		$this->setSubtypeClasses();

		$this->enablePlugins();

		return true;
	}

	/**
	 * Register classes for core objects
	 *
	 * @return void
	 */
	protected function setSubtypeClasses() {
		add_subtype("object", "plugin", "ElggPlugin");
		add_subtype("object", "file", "ElggFile");
		add_subtype("object", "widget", "ElggWidget");
		add_subtype("object", "comment", "ElggComment");
		add_subtype("object", "elgg_upgrade", 'ElggUpgrade');
	}

	/**
	 * Enable a set of default plugins
	 *
	 * @return void
	 */
	protected function enablePlugins() {
		_elgg_generate_plugin_entities();
		$plugins = elgg_get_plugins('any');
		foreach ($plugins as $plugin) {
			if ($plugin->getManifest()) {
				if ($plugin->getManifest()->getActivateOnInstall()) {
					$plugin->activate();
				}
				if (in_array('theme', $plugin->getManifest()->getCategories())) {
					$plugin->setPriority('last');
				}
			}
		}
	}

	/**
	 * Admin account support methods
	 */

	/**
	 * Validate account form variables
	 *
	 * @param array $submissionVars Submitted vars
	 * @param array $formVars       Form vars
	 *
	 * @return bool
	 */
	protected function validateAdminVars($submissionVars, $formVars) {

		foreach ($formVars as $field => $info) {
			if ($info['required'] == true && !$submissionVars[$field]) {
				$name = _elgg_services()->translator->translate("install:admin:label:$field");
				register_error(_elgg_services()->translator->translate('install:error:requiredfield', [$name]));
				return false;
			}
		}

		if ($submissionVars['password1'] !== $submissionVars['password2']) {
			register_error(_elgg_services()->translator->translate('install:admin:password:mismatch'));
			return false;
		}

		if (trim($submissionVars['password1']) == "") {
			register_error(_elgg_services()->translator->translate('install:admin:password:empty'));
			return false;
		}

		$minLength = _elgg_services()->configTable->get('min_password_length');
		if (strlen($submissionVars['password1']) < $minLength) {
			register_error(_elgg_services()->translator->translate('install:admin:password:tooshort'));
			return false;
		}

		// check that email address is email address
		if ($submissionVars['email'] && !is_email_address($submissionVars['email'])) {
			$msg = _elgg_services()->translator->translate('install:error:emailaddress', [$submissionVars['email']]);
			register_error($msg);
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
	protected function createAdminAccount($submissionVars, $login = false) {
		try {
			$guid = register_user(
					$submissionVars['username'],
					$submissionVars['password1'],
					$submissionVars['displayname'],
					$submissionVars['email']
					);
		} catch (Exception $e) {
			register_error($e->getMessage());
			return false;
		}

		if (!$guid) {
			register_error(_elgg_services()->translator->translate('install:admin:cannot_create'));
			return false;
		}

		$user = get_entity($guid);
		if (!$user instanceof ElggUser) {
			register_error(_elgg_services()->translator->translate('install:error:loadadmin'));
			return false;
		}

		elgg_set_ignore_access(true);
		if ($user->makeAdmin() == false) {
			register_error(_elgg_services()->translator->translate('install:error:adminaccess'));
		} else {
			_elgg_services()->configTable->set('admin_registered', 1);
		}
		elgg_set_ignore_access(false);

		// add validation data to satisfy user validation plugins
		$user->validated = 1;
		$user->validated_method = 'admin_user';

		if ($login) {
			$handler = new Elgg\Http\DatabaseSessionHandler(_elgg_services()->db);

			// session.cache_limiter is unfortunately set to "" by the NativeSessionStorage constructor,
			// so we must capture and inject it directly.
			$options = [
				'cache_limiter' => session_cache_limiter(),
			];
			$storage = new Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage($options, $handler);

			$session = new ElggSession(new Symfony\Component\HttpFoundation\Session\Session($storage));
			$session->setName('Elgg');
			_elgg_services()->setValue('session', $session);
			if (login($user) == false) {
				register_error(_elgg_services()->translator->translate('install:error:adminlogin'));
			}
		}

		return true;
	}
}
