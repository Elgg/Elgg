<?php

use Elgg\Filesystem\Directory;
use Elgg\Application;
use Elgg\Config;
use Elgg\Project\Paths;

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

	/**
	 * Constructor bootstraps the Elgg engine
	 */
	public function __construct() {
		Application::factory()->loadCore();

		$this->isAction = _elgg_services()->request->getMethod() === 'POST';

		$config = _elgg_config();
		$config->wwwroot = _elgg_services()->request->sniffElggUrl();
		$config->installer_running = true;
		if (!$config->dbencoding) {
			$config->dbencoding = 'utf8mb4';
		}
		$config->simplecache_enabled = false;

		_elgg_services()->setValue('session', \ElggSession::getMock());
		_elgg_services()->views->setViewtype('installation');
		_elgg_services()->translator->registerTranslations(Paths::elgg() . "install/languages/", true);
		_elgg_services()->views->registerPluginViews(Paths::elgg());
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
		if (!in_array($step, $this->getSteps())) {
			$msg = _elgg_services()->translator->translate('InstallationException:UnknownStep', [$step]);
			throw new InstallationException($msg);
		}

		$this->setInstallStatus();
	
		$this->checkInstallCompletion($step);

		// check if this is an install being resumed
		$this->resumeInstall($step);

		$this->finishBootstrapping($step);

		$params = _elgg_services()->request->request->all();

		$method = "run" . ucwords($step);
		$this->$method($params);
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
	 * If the .env.php file exists, it will use that rather than the parameters
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
			if (!$rewriteTester->createHtaccess($params['wwwroot'])) {
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
	protected function runWelcome($vars) {
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
	 * Creates the .env.php file and creates the database tables
	 *
	 * @param array $submissionVars Submitted form variables
	 *
	 * @return void
	 */
	protected function runDatabase($submissionVars) {

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
				'value' => _elgg_services()->request->sniffElggUrl(),
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
			call_user_func(function () use ($submissionVars, $formVars) {
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

				system_message(_elgg_services()->translator->translate('install:success:database'));

				$this->continueToNextStep('database');
			});
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
	protected function runSettings($submissionVars) {
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

		if ($this->isAction) {
			call_user_func(function () use ($submissionVars, $formVars) {
				if (!$this->validateSettingsVars($submissionVars, $formVars)) {
					return;
				}

				if (!$this->saveSiteSettings($submissionVars)) {
					return;
				}

				system_message(_elgg_services()->translator->translate('install:success:settings'));

				$this->continueToNextStep('settings');
			});
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
	protected function runAdmin($submissionVars) {
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

		$translator = _elgg_services()->translator;
		
		if ($this->isAction) {
			call_user_func(function () use ($submissionVars, $formVars, $translator) {
				if (!$this->validateAdminVars($submissionVars, $formVars)) {
					return;
				}

				if (!$this->createAdminAccount($submissionVars, $this->autoLogin)) {
					return;
				}

				system_message($translator->translate('install:success:admin'));

				$this->continueToNextStep('admin');
			});
		}

		// Bit of a hack to get the password help to show right number of characters
		// We burn the value into the stored translation.
		$lang = $translator->getCurrentLanguage();
		$translations = $translator->getLoadedTranslations();
		$translator->addTranslation($lang, [
			'install:admin:help:password1' => sprintf(
				$translations[$lang]['install:admin:help:password1'],
				_elgg_config()->min_password_length
			),
		]);

		$formVars = $this->makeFormSticky($formVars, $submissionVars);

		$this->render('admin', ['variables' => $formVars]);
	}

	/**
	 * Controller for last step
	 *
	 * @return void
	 */
	protected function runComplete() {

		// nudge to check out settings
		$link = elgg_format_element([
			'#tag_name' => 'a',
			'#text' => _elgg_services()->translator->translate('install:complete:admin_notice:link_text'),
			'href' => elgg_normalize_url('admin/settings/basic'),
		]);
		$notice = _elgg_services()->translator->translate('install:complete:admin_notice', [$link]);
		elgg_add_admin_notice('fresh_install', $notice);

		$this->render('complete');
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
		return _elgg_config()->wwwroot . "install.php?step=$nextStep";
	}

	/**
	 * Check the different install steps for completion
	 *
	 * @return void
	 * @throws InstallationException
	 */
	protected function setInstallStatus() {
		$path = Paths::settingsFile();
		if (!is_file($path) || !is_readable($path)) {
			return;
		}

		exit('rework');

		$this->status['config'] = true;

		// must be able to connect to database to jump install steps
		$dbSettingsPass = $this->checkDatabaseSettings(
			_elgg_config()->dbuser,
			_elgg_config()->dbpass,
			_elgg_config()->dbname,
			_elgg_config()->dbhost
		);

		if ($dbSettingsPass == false) {
			return;
		}

		$prefix = _elgg_config()->dbprefix;

		// check that the config table has been created
		$query = "show tables";
		$result = _elgg_services()->db->getData($query);
		if ($result) {
			foreach ($result as $table) {
				$table = (array) $table;
				if (in_array("{$prefix}config", $table)) {
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
		$query = "SELECT COUNT(*) AS total FROM {$prefix}config";
		$result = _elgg_services()->db->getData($query);
		if ($result && $result[0]->total > 0) {
			$this->status['settings'] = true;
		} else {
			return;
		}

		// check that the users entity table has an entry
		$query = "SELECT COUNT(*) AS total FROM {$prefix}users_entity";
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

			// dummy site needed to boot
			elgg_set_config('site', new ElggSite());

			Application::$_instance->bootCore();
		}
	}

	/**
	 * Load settings
	 *
	 * @return void
	 * @throws InstallationException
	 */
	protected function loadSettingsFile() {
		echo "rewrite";
		exit;
		try {
//			_elgg_config()->loadSettingsFile();
		} catch (\Exception $e) {
			$msg = _elgg_services()->translator->translate('InstallationException:CannotLoadSettings');
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
	 * Indicates whether the webserver can add .env.php on its own or not.
	 *
	 * @param array $report The requirements report object
	 *
	 * @return bool
	 */
	protected function isInstallDirWritable(&$report) {
		if (!is_writable(Paths::projectConfig())) {
			$msg = _elgg_services()->translator->translate('install:check:installdir', [Paths::PATH_TO_CONFIG]);
			$report['settings'] = [
				[
					'severity' => 'failure',
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
		if (!is_file($this->getSettingsPath())) {
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
	 * Returns the path to the root .env.php file.
	 *
	 * @return string
	 */
	private function getSettingsPath() {
		return Paths::project() . "elgg-config/.env.php";
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
		$url = _elgg_config()->wwwroot;
		$url .= Application::REWRITE_TEST_TOKEN . '?' . http_build_query([
			Application::REWRITE_TEST_TOKEN => '1',
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

		if (!_elgg_config()->data_dir_override) {
			// check that data root is not subdirectory of Elgg root
			if (stripos($submissionVars['dataroot'], _elgg_config()->path) === 0) {
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
		$template = Application::elggDir()->getContents("elgg-config/.env.php.example");
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
			_elgg_services()->db->runSqlScript(Paths::elgg() . "engine/schema/mysql.sql");
			init_site_secret();
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
		elgg_set_config('site', $site);

		if ($guid !== 1) {
			register_error(_elgg_services()->translator->translate('install:error:createsite'));
			return false;
		}

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
			elgg_set_config($key, $value);
		}

		// Enable a set of default plugins
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

		return true;
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
