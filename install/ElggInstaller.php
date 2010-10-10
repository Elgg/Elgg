<?php
/**
 * Elgg Installer.
 * Controller for installing Elgg.
 *
 * @package Elgg
 * @subpackage Installer
 * @author Curverider Ltd
 * @link http://elgg.org/
 */


class ElggInstaller {

	protected $steps = array(
		'welcome',
		'requirements',
		'database',
		'settings',
		'admin',
		'complete',
		);

	protected $status = array(
		'database' => FALSE,
		'settings' => FALSE,
		'admin' => FALSE,
	);

	protected $isAction = FALSE;

	protected $autoLogin = FALSE;

	/**
	 * Constructor bootstraps the Elgg engine
	 */
	public function __construct() {
		$this->isAction = $_SERVER['REQUEST_METHOD'] === 'POST';

		$this->bootstrapConfig();

		$this->bootstrapEngine();

		elgg_set_viewtype('installation');

		set_error_handler('__elgg_php_error_handler');
		set_exception_handler('__elgg_php_exception_handler');

		register_translations(dirname(__FILE__) . '/languages/', TRUE);
	}

	/**
	 * Dispatches a request to one of the step controllers
	 *
	 * @param string $step
	 */
	public function run($step) {

		// check if this a mod rewrite test coming in
		$this->runModRewriteTest();

		if (!in_array($step, $this->getSteps())) {
			$msg = sprintf(elgg_echo('InstallationException:UnknownStep'), $step);
			throw new InstallationException($msg);
		}

		$this->setInstallStatus();

		$this->checkInstallCompletion($step);

		// check if this is an install being resumed
		$this->resumeInstall($step);

		$this->finishBootstraping($step);

		$params = $this->getPostVariables();
		$this->$step($params);
	}

	/**
	 * Set the auto login flag
	 * 
	 * @param bool $flag
	 */
	public function setAutoLogin(bool $flag) {
		$this->autoLogin = $value;
	}

	/**
	 * Renders the data passed by a controller
	 *
	 * @param string $step
	 * @param array $vars
	 */
	protected function render($step, $vars = array()) {

		$vars['next_step'] = $this->getNextStep($step);

		$title = elgg_echo("install:$step");
		$body = elgg_view("install/pages/$step", $vars);
		page_draw(
				$title,
				$body,
				'page_shells/default',
				array(
					'step' => $step,
					'steps' => $this->getSteps(),
					)
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
	 */
	protected function welcome($vars) {
		$this->render('welcome');
	}

	/**
	 * Requirements controller
	 *
	 * Checks version of php, libraries, permissions, and rewrite rules
	 *
	 * @param array $vars
	 */
	protected function requirements($vars) {

		$report = array();

		// check PHP parameters and libraries
		$this->checkPHP($report);

		// check rewrite. If failure, create .htaccess and try again.
		$rewriteResult = $this->checkRewriteRules($report);
		if ($rewriteResult == FALSE) {
			$htaccessExists = $this->createHtaccess($report);
			if ($htaccessExists) {
				$this->checkRewriteRules($report);
			}
		}

		// check for existence of settings file
		if ($this->checkSettingsFile() != TRUE) {
			// no file, so check permissions on engine directory
			$this->checkEngineDir($report);
		}

		// check the database later
		$report['database'] = array(array(
			'severity' => 'info',
			'message' => elgg_echo('install:check:database')
		));

		// any failures?
		$numFailures = $this->countNumConditions($report, 'failure');

		// any warnings
		$numWarnings = $this->countNumConditions($report, 'warning');


		$params = array(
			'report' => $report,
			'num_failures' => $numFailures,
			'num_warnings' => $numWarnings,
		);

		$this->render('requirements', $params);
	}

	/**
	 * Database set up controller
	 *
	 * Creates the settings.php file and creates the database tables
	 *
	 * @param array $submissionVars Submitted form variables
	 */
	protected function database($submissionVars) {

		$formVars = array(
			'dbuser' => array(
				'type' => 'text',
				'value' => '',
				'required' => TRUE,
				),
			'dbpassword' => array(
				'type' => 'password',
				'value' => '',
				'required' => TRUE,
				),
			'dbname' => array(
				'type' => 'text',
				'value' => '',
				'required' => TRUE,
				),
			'dbhost' => array(
				'type' => 'text',
				'value' => 'localhost',
				'required' => TRUE,
				),
			'dbprefix' => array(
				'type' => 'text',
				'value' => 'elgg_',
				'required' => TRUE,
				),
		);

		if ($this->checkSettingsFile()) {
			// user manually created settings file so we fake out action test
			$this->isAction = TRUE;
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

				system_message(elgg_echo('install:success:database'));

				$this->continueToNextStep('database');
			} while (FALSE);  // PHP doesn't support breaking out of if statements
		}

		$formVars = $this->makeFormSticky($formVars, $submissionVars);

		$params = array('variables' => $formVars,);

		if ($this->checkSettingsFile()) {
			// settings file exists and we're here so failed to create database
			$params['failure'] = TRUE;
		}

		$this->render('database', $params);
	}

	/**
	 * Site settings controller
	 *
	 * Sets the site name, URL, data directory, etc.
	 *
	 * @param array $submissionVars
	 */
	protected function settings($submissionVars) {
		global $CONFIG;

		$languages = get_installed_translations();
		$formVars = array(
			'sitename' => array(
				'type' => 'text',
				'value' => 'New Elgg site',
				'required' => TRUE,
				),
			'siteemail' => array(
				'type' => 'text',
				'value' => '',
				'required' => FALSE,
				),
			'wwwroot' => array(
				'type' => 'text',
				'value' => $CONFIG->wwwroot,
				'required' => TRUE,
				),
			'path' => array(
				'type' => 'text',
				'value' => $CONFIG->path,
				'required' => TRUE,
				),
			'dataroot' => array(
				'type' => 'text',
				'value' => '',
				'required' => TRUE,
				),
			'language' => array(
				'type' => 'pulldown',
				'value' => 'en',
				'options_values' => $languages,
				'required' => TRUE,
				),
			'siteaccess' => array(
				'type' => 'access',
				'value' =>  ACCESS_PUBLIC,
				'required' => TRUE,
				),
		);

		if ($this->isAction) {
			do {
				if (!$this->validateSettingsVars($submissionVars, $formVars)) {
					break;
				}

				if (!$this->saveSiteSettings($submissionVars)) {
					break;
				}

				system_message(elgg_echo('install:success:settings'));

				$this->continueToNextStep('settings');

			} while (FALSE);  // PHP doesn't support breaking out of if statements
		}

		$formVars = $this->makeFormSticky($formVars, $submissionVars);

		$this->render('settings', array('variables' => $formVars));
	}

	/**
	 * Admin account controller
	 *
	 * Creates an admin user account
	 *
	 * @param array $submissionVars
	 */
	protected function admin($submissionVars) {
		$formVars = array(
			'displayname' => array(
				'type' => 'text',
				'value' => '',
				'required' => TRUE,
				),
			'email' => array(
				'type' => 'text',
				'value' => '',
				'required' => TRUE,
				),
			'username' => array(
				'type' => 'text',
				'value' => '',
				'required' => TRUE,
				),
			'password1' => array(
				'type' => 'password',
				'value' => '',
				'required' => TRUE,
				),
			'password2' => array(
				'type' => 'password',
				'value' => '',
				'required' => TRUE,
				),
		);

		if ($this->isAction) {
			do {
				if (!$this->validateAdminVars($submissionVars, $formVars)) {
					break;
				}

				if (!$this->createAdminAccount($submissionVars, $this->autoLogin)) {
					break;
				}
				
				system_message(elgg_echo('install:success:admin'));

				$this->continueToNextStep('admin');

			} while (FALSE);  // PHP doesn't support breaking out of if statements
		}

		$formVars = $this->makeFormSticky($formVars, $submissionVars);

		$this->render('admin', array('variables' => $formVars));
	}

	/**
	 * Controller for last step
	 *
	 * @param array $vars
	 */
	protected function complete($vars) {

		$params = array();
		if ($this->autoLogin) {
			// remind users to enable / disable desired tools
			$msg = elgg_echo('firstadminlogininstructions');
			elgg_add_admin_notice('first_installation_plugin_reminder', $msg);

			$params['destination'] = 'pg/admin/plugins/simple';
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
	 * @param string $currentStep
	 */
	protected function continueToNextStep($currentStep) {
		$this->isAction = FALSE;
		forward($this->getNextStepUrl($currentStep));
	}

	/**
	 * Get the next step as a string
	 *
	 * @param string $currentStep
	 * @return string
	 */
	protected function getNextStep($currentStep) {
		return $this->steps[1 + array_search($currentStep, $this->steps)];
	}

	/**
	 * Get the URL of the next step
	 *
	 * @param string $currentStep
	 * @return string
	 */
	protected function getNextStepUrl($currentStep) {
		global $CONFIG;
		$nextStep = $this->getNextStep($currentStep);
		return "{$CONFIG->wwwroot}install.php?step=$nextStep";
	}

	/**
	 * Check the different nstall steps for completion
	 */
	protected function setInstallStatus() {
		global $CONFIG;

		$settingsCreated = $this->checkSettingsFile();
		if ($settingsCreated == FALSE) {
			return;
		}

		$this->loadSettingsFile();

		// must be able to connect to database to jump install steps
		$dbSettingsPass = $this->checkDatabaseSettings(
				$CONFIG->dbuser,
				$CONFIG->dbpass,
				$CONFIG->dbname,
				$CONFIG->dbhost
				);
		if ($dbSettingsPass == FALSE) {
			return;
		}

		if (!include_once("{$CONFIG->path}engine/lib/database.php")) {
			$msg = sprintf(elgg_echo('InstallationException:MissingLibrary'), 'database.php');
			throw new InstallationException($msg);
		}

		// check that the config table has been created
		$query = "show tables";
		$result = get_data($query);
		if ($result) {
			foreach ($result as $table) {
				$table = (array) $table;
				if (in_array("{$CONFIG->dbprefix}config", $table)) {
					$this->status['database'] = TRUE;
				}
			}
			if ($this->status['database'] == FALSE) {
				return;
			}
		} else {
			// no tables
			return;
		}

		// check that the config table has entries
		$query = "SELECT COUNT(*) AS total FROM {$CONFIG->dbprefix}config";
		$result = get_data($query);
		if ($result && $result[0]->total > 0) {
			$this->status['settings'] = TRUE;
		} else {
			return;
		}

		// check that the users entity table has an entry
		$query = "SELECT COUNT(*) AS total FROM {$CONFIG->dbprefix}users_entity";
		$result = get_data($query);
		if ($result && $result[0]->total > 0) {
			$this->status['admin'] = TRUE;
		} else {
			return;
		}
	}

	/**
	 * Security check to ensure the installer cannot be run after installation
	 * has finished. If this is detected, the viewer is sent to the front page.
	 *
	 * @param string $step
	 */
	protected function checkInstallCompletion($step) {
		if ($step != 'complete') {
			if (!in_array(FALSE, $this->status)) {
				// install complete but someone is trying to view an install page
				forward();
			}
		}
	}

	/**
	 * Check if this is a case of a install being resumed and figure
	 * out where to continue from. Returns the best guess on the step.
	 *
	 * @param string $step
	 * @return string
	 */
	protected function resumeInstall($step) {
		global $CONFIG;

		// only do a resume from the first step
		if ($step !== 'welcome') {
			return;
		}

		if ($this->status['database'] == FALSE) {
			return;
		}

		if ($this->status['settings'] == FALSE) {
			forward("{$CONFIG->wwwroot}install.php?step=settings");
		}

		if ($this->status['admin'] == FALSE) {
			forward("{$CONFIG->wwwroot}install.php?step=admin");
		}

		// everything appears to be set up
		forward("{$CONFIG->wwwroot}install.php?step=complete");
	}

	/**
	 * Bootstraping
	 */

	/**
	 * Load the essential libraries of the engine
	 */
	protected function bootstrapEngine() {
		global $CONFIG;

		$lib_dir = $CONFIG->path . 'engine/lib/';

		// bootstrapping with required files in a required order
		$required_files = array(
			'exceptions.php', 'elgglib.php', 'views.php', 'access.php', 'system_log.php', 'export.php',
			'sessions.php', 'languages.php', 'input.php', 'install.php', 'cache.php', 'output.php'
		);

		foreach ($required_files as $file) {
			$path = $lib_dir . $file;
			if (!include($path)) {
				echo "Could not load file '$path'. "
					. 'Please check your Elgg installation for all required files.';
				exit;
			}
		}
	}

	/**
	 * Load remaining engine libraries and complete bootstraping (see start.php)
	 *
	 * @param string $step
	 */
	protected function finishBootstraping($step) {

		$dbIndex = array_search('database', $this->getSteps());
		$settingsIndex = array_search('settings', $this->getSteps());
		$stepIndex = array_search($step, $this->getSteps());

		if ($stepIndex <= $settingsIndex) {
			// install has its own session handling before the db created and set up
			session_name('Elgg');
			session_start();
			unregister_elgg_event_handler('boot', 'system', 'session_init');
		}

		if ($stepIndex > $dbIndex) {
			// once the database has been created, load rest of engine
			global $CONFIG;
			$lib_dir = $CONFIG->path . 'engine/lib/';

			$this->loadSettingsFile();

			$lib_files = array(
				// these want to be loaded first apparently?
				'database.php', 'actions.php',

				'admin.php', 'annotations.php', 'api.php',
				'calendar.php', 'configuration.php', 'cron.php', 'entities.php',
				'extender.php', 'filestore.php', 'group.php',
				'location.php', 'mb_wrapper.php',
				'memcache.php', 'metadata.php', 'metastrings.php', 'notification.php',
				'objects.php', 'opendd.php', 'pagehandler.php',
				'pageowner.php', 'pam.php', 'plugins.php', 'query.php',
				'relationships.php', 'river.php', 'sites.php', 'social.php',
				'statistics.php', 'tags.php', 'usersettings.php',
				'users.php', 'version.php', 'widgets.php', 'xml.php', 'xml-rpc.php'
			);

			foreach ($lib_files as $file) {
				$path = $lib_dir . $file;
				if (!include_once($path)) {
					$msg = sprintf(elgg_echo('InstallationException:MissingLibrary'), $file);
					throw new InstallationException($msg);
				}
			}

			set_default_config();

			trigger_elgg_event('boot', 'system');
			trigger_elgg_event('init', 'system');
		}
	}

	/**
	 * Set up configuration variables
	 */
	protected function bootstrapConfig() {
		global $CONFIG;
		if (!isset($CONFIG)) {
			$CONFIG = new stdClass;
		}

		$CONFIG->wwwroot = $this->getBaseUrl();
		$CONFIG->url = $CONFIG->wwwroot;
		$CONFIG->path = dirname(dirname(__FILE__)) . '/';
	}

	/**
	 * Get the best guess at the base URL
	 * @todo Should this be a core function?
	 * @return string
	 */
	protected function getBaseUrl() {
		$protocol = 'http';
		if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
			$protocol = 'https';
		}
		$port = ':' . $_SERVER["SERVER_PORT"];
		if ($port == ':80' || $port == ':443') {
			$port = '';
		}
		$uri = $_SERVER['REQUEST_URI'];
		$cutoff = strpos($uri, 'install.php');
		$uri = substr($uri, 0, $cutoff);

		$url = "$protocol://{$_SERVER['SERVER_NAME']}$port{$uri}";
		return $url;
	}

	protected function loadSettingsFile() {
		global $CONFIG;

		if (!include_once("{$CONFIG->path}engine/settings.php")) {
			$msg = elgg_echo('InstallationException:CannotLoadSettings');
			throw new InstallationException($msg);
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
		$vars = array();
		foreach ($_POST as $k => $v) {
			$vars[$k] = $v;
		}
		return $vars;
	}

	/**
	 * If form is reshown, remember previously submitted variables
	 *
	 * @param array $formVars
	 * @param array $submissionVars
	 * @return array
	 */
	protected function makeFormSticky($formVars, $submissionVars) {
		foreach ($submissionVars as $field => $value) {
			$formVars[$field]['value'] = $value;
		}
		return $formVars;
	}

	/**
	 * Requirement checks support methods
	 */

	/**
	 * Create Elgg's .htaccess file or confirm that it exists
	 *
	 * @param array $report Reference to the report array
	 * @return bool
	 */
	protected function createHtaccess(&$report) {
		global $CONFIG;

		$filename = "{$CONFIG->path}.htaccess";
		if (file_exists($filename)) {
			// check that this is the Elgg .htaccess
			$data = file_get_contents($filename);
			if ($data === FALSE) {
				// don't have permission to read the file
			}
			if (strpos($data, 'Elgg') === FALSE) {
				$report['htaccess'] = array(
					array(
						'severity' => 'failure',
						'message' => elgg_echo('install:check:htaccess_exists'),
					)
				);
				return FALSE;
			} else {
				// Elgg .htaccess is already there
				return TRUE;
			}
		}

		if (!is_writable($CONFIG->path)) {
			$report['htaccess'] = array(
				array(
					'severity' => 'failure',
					'message' => elgg_echo('install:check:root'),
				)
			);
			return FALSE;
		}

		// create the .htaccess file
		$result = copy("{$CONFIG->path}htaccess_dist", $filename);
		if (!$result) {
			$report['htaccess'] = array(
				array(
					'severity' => 'failure',
					'message' => elgg_echo('install:check:htaccess_fail'),
				)
			);
		}

		return $result;
	}

	/**
	 * Check that the engine dir is writable
	 * @param array $report
	 * @return bool
	 */
	protected function checkEngineDir(&$report) {
		global $CONFIG;

		$writable = is_writable("{$CONFIG->path}engine");
		if (!$writable) {
			$report['engine'] = array(
				array(
					'severity' => 'failure',
					'message' => elgg_echo('install:check:enginedir'),
				)
			);
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Check that the settings file exists
	 * @return bool
	 */
	protected function checkSettingsFile() {
		global $CONFIG;

		if (is_readable("{$CONFIG->path}engine/settings.php")) {
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Check version of PHP, extensions, and variables
	 * @param array $report
	 */
	protected function checkPHP(&$report) {
		$phpReport = array();

		$elgg_php_version = '5.2.0';
		if (version_compare(PHP_VERSION, $elgg_php_version, '<')) {
			$phpReport[] = array(
				'severity' => 'failure',
				'message' => sprintf(elgg_echo('install:check:php:version'), $elgg_php_version, PHP_VERSION)
			);
		}

		$this->checkPhpExtensions($phpReport);

		$this->checkPhpDirectives($phpReport);

		if (count($phpReport) == 0) {
			$phpReport[] = array(
				'severity' => 'pass',
				'message' => elgg_echo('install:check:php:success')
			);
		}

		$report['php'] = $phpReport;
	}

	/**
	 * Check the server's PHP extensions
	 *
	 * @param array $phpReport
	 */
	protected function checkPhpExtensions(&$phpReport) {
		$extensions = get_loaded_extensions();
		$requiredExtensions = array(
			'mysql',
			'json',
			'xml',
			'gd',
		);
		foreach ($requiredExtensions as $extension) {
			if (!in_array($extension, $extensions)) {
				$phpReport[] = array(
					'severity' => 'failure',
					'message' => sprintf(elgg_echo('install:check:php:extension'), $extension)
				);
			}
		}

		$recommendedExtensions = array(
			'mbstring',
		);
		foreach ($recommendedExtensions as $extension) {
			if (!in_array($extension, $extensions)) {
				$phpReport[] = array(
					'severity' => 'warning',
					'message' => sprintf(elgg_echo('install:check:php:extension:recommend'), $extension)
				);
			}
		}
	}

	/**
	 * Check PHP parameters
	 *
	 * @param array $phpReport
	 */
	protected function checkPhpDirectives(&$phpReport) {
		if (ini_get('open_basedir')) {
			$phpReport[] = array(
				'severity' => 'warning',
				'message' => elgg_echo("install:check:php:open_basedir")
			);
		}

		if (ini_get('safe_mode')) {
			$phpReport[] = array(
				'severity' => 'warning',
				'message' => elgg_echo("install:check:php:safe_mode")
			);
		}
	}

	/**
	 * Confirm that the rewrite rules are firing
	 * @param array $report
	 * @return bool
	 */
	protected function checkRewriteRules(&$report) {
		global $CONFIG;

		$url = "{$CONFIG->wwwroot}modrewrite.php";

		if (function_exists('curl_init')) {
			// try curl if installed
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			$response = curl_exec($ch);
			curl_close($ch);
			$result = $response === 'success';
		} else if (ini_get('allow_url_fopen')) {
			// use file_get_contents as fallback
			$response = file_get_contents($url);
			$result = $response === 'success';
		} else {
			$report['htaccess'] = array(
				array(
					'severity' => 'warning',
					'message' => elgg_echo('install:check:rewrite:unknown'),
				)
			);
			return FALSE;
		}

		if ($result) {
			$report['htaccess'] = array(
				array(
					'severity' => 'pass',
					'message' => elgg_echo('install:check:rewrite:success'),
				)
			);
		} else {
			if (strpos($response, 'No input file specified.') !== FALSE) {
				// nginx with no or bad rewrite rules
			}
			$report['htaccess'] = array(
				array(
					'severity' => 'failure',
					'message' => elgg_echo('install:check:rewrite:fail'),
				)
			);
		}

		return $result;
	}

	/**
	 * Check if the request is coming from the mod rewrite test on the
	 * requirements page.
	 */
	protected function runModRewriteTest() {
		if (strpos($_SERVER['REQUEST_URI'], 'modrewrite.php') !== FALSE) {
			echo 'success';
			exit;
		}
	}

	/**
	 * Count the number of failures in the requirements report
	 *
	 * @param array $report
	 * @param string $condition 'failure' or 'warning'
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
	 * @param array $submissionVars
	 * @param array $formVars
	 * @return bool
	 */
	protected function validateDatabaseVars($submissionVars, $formVars) {

		foreach ($formVars as $field => $info) {
			if ($info['required'] == TRUE && !$submissionVars[$field]) {
				$name = elgg_echo("install:database:label:$field");
				register_error("$name is required");
				return FALSE;
			}
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
	 * @param string $user
	 * @param string $password
	 * @param string $dbname
	 * @param string $host
	 * @return bool
	 */
	protected function checkDatabaseSettings($user, $password, $dbname, $host) {
		$mysql_dblink = mysql_connect($host, $user, $password, true);
		if ($mysql_dblink == FALSE) {
			register_error(elgg_echo('install:error:databasesettings'));
			return $FALSE;
		}

		$result = mysql_select_db($dbname, $mysql_dblink);

		// check MySQL version - must be 5.0 or >
		$required_version = 5.0;
		$version = mysql_get_server_info();
		$points = explode('.', $version);
		if ($points[0] < $required_version) {
			register_error(sprintf(elgg_echo('install:error:oldmysql'), $version));
			return FALSE;
		}

		mysql_close($mysql_dblink);

		if (!$result) {
			register_error(sprintf(elgg_echo('install:error:nodatabase'), $dbname));
		}

		return $result;
	}

	/**
	 * Writes the settings file to the engine directory
	 *
	 * @param array $params
	 * @return bool
	 */
	protected function createSettingsFile($params) {
		global $CONFIG;

		$templateFile = "{$CONFIG->path}engine/settings.example.php";
		$template = file_get_contents($templateFile);
		if (!$template) {
			register_error(elgg_echo('install:error:readsettingsphp'));
			return FALSE;
		}

		foreach ($params as $k => $v) {
			$template = str_replace("{{".$k."}}", $v, $template);
		}

		$settingsFilename = "{$CONFIG->path}engine/settings.php";
		$result = file_put_contents($settingsFilename, $template);
		if (!$result) {
			register_error(elgg_echo('install:error:writesettingphp'));
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Bootstrap database connection before entire engine is available
	 *
	 * @return bool
	 */
	protected function connectToDatabase() {
		global $CONFIG;

		if (!include_once("{$CONFIG->path}engine/settings.php")) {
			register_error(elgg_echo('InstallationException:CannotLoadSettings'));
			return FALSE;
		}

		if (!include_once("{$CONFIG->path}engine/lib/database.php")) {
			$msg = sprintf(elgg_echo('InstallationException:MissingLibrary'), 'database.php');
			register_error($msg);
			return FALSE;
		}

		try  {
			setup_db_connections();
		} catch (Exception $e) {
			register_error($e->getMessage());
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Create the database tables
	 *
	 * @return bool
	 */
	protected function installDatabase() {
		global $CONFIG;

		try {
			run_sql_script("{$CONFIG->path}engine/schema/mysql.sql");
		} catch (Exception $e) {
			register_error($e->getMessage());
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Site settings support methods
	 */

	/**
	 * Validate the site settings form variables
	 *
	 * @param array $submissionVars
	 * @param array $formVars
	 * @return bool
	 */
	protected function validateSettingsVars($submissionVars, $formVars) {

		foreach ($formVars as $field => $info) {
			if ($info['required'] == TRUE && !$submissionVars[$field]) {
				$name = elgg_echo("install:settings:label:$field");
				register_error(sprintf(elgg_echo('install:error:requiredfield'), $name));
				return FALSE;
			}
		}

		// check that data root is writable
		if (!is_writable($submissionVars['dataroot'])) {
			$msg = sprintf(elgg_echo('install:error:writedatadirectory'), $submissionVars['dataroot']);
			register_error($msg);
			return FALSE;
		}

		// check that data root is not subdirectory of Elgg root
		if (stripos($submissionVars['dataroot'], $submissionVars['path']) !== FALSE) {
			$msg = sprintf(elgg_echo('install:error:locationdatadirectory'), $submissionVars['dataroot']);
			register_error($msg);
			return FALSE;
		}

		// check that email address is email address
		if ($submissionVars['siteemail'] && !is_email_address($submissionVars['siteemail'])) {
			$msg = sprintf(elgg_echo('install:error:emailaddress'), $submissionVars['siteemail']);
			register_error($msg);
			return FALSE;
		}

		// @todo check that url is a url
		// @note filter_var cannot be used because it doesn't work on international urls

		return TRUE;
	}

	/**
	 * Initialize the site including site entity, plugins, and configuration
	 *
	 * @param array $submissionVars
	 * @return bool
	 */
	protected function saveSiteSettings($submissionVars) {
		global $CONFIG;

		// ensure that file path, data path, and www root end in /
		$submissionVars['path'] = sanitise_filepath($submissionVars['path']);
		$submissionVars['dataroot'] = sanitise_filepath($submissionVars['dataroot']);
		$submissionVars['wwwroot'] = sanitise_filepath($submissionVars['wwwroot']);

		$site = new ElggSite();
		$site->name      = $submissionVars['sitename'];
		$site->url       = $submissionVars['wwwroot'];
		$site->access_id = ACCESS_PUBLIC;
		$site->email     = $submissionVars['siteemail'];
		$guid            = $site->save();

		if (!$guid) {
			register_error(elgg_echo('install:error:createsite'));
			return FALSE;
		}

		// bootstrap site info
		$CONFIG->site_guid = $guid;
		$CONFIG->site = $site;

		datalist_set('installed', time());
		datalist_set('path', $submissionVars['path']);
		datalist_set('dataroot', $submissionVars['dataroot']);
		datalist_set('default_site', $site->getGUID());
		datalist_set('version', get_version());

		set_config('view', 'default', $site->getGUID());
		set_config('language', $submissionVars['language'], $site->getGUID());
		set_config('default_access', $submissionVars['siteaccess'], $site->getGUID());
		set_config('allow_registration', TRUE, $site->getGUID());
		set_config('walled_garden', FALSE, $site->getGUID());

		$this->enablePlugins();

		// reset the views path in case of installing over an old data dir.
		$dataroot = datalist_get('dataroot');
		$cache = new ElggFileCache($dataroot);
		$cache->delete('view_paths');

		return TRUE;
	}

	/**
	 * Enable a set of default plugins
	 */
	protected function enablePlugins() {
		// activate plugins with manifest.xml: elgg_install_state = enabled
		$plugins = get_plugin_list();
		foreach ($plugins as $plugin) {
			if ($manifest = load_plugin_manifest($plugin)) {
				if (isset($manifest['elgg_install_state']) && $manifest['elgg_install_state'] == 'enabled') {
					enable_plugin($plugin);
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
	 * @param array $submissionVars
	 * @param array $formVars
	 * @return bool
	 */
	protected function validateAdminVars($submissionVars, $formVars) {

		foreach ($formVars as $field => $info) {
			if ($info['required'] == TRUE && !$submissionVars[$field]) {
				$name = elgg_echo("install:admin:label:$field");
				register_error(sprintf(elgg_echo('install:error:requiredfield'), $name));
				return FALSE;
			}
		}

		if ($submissionVars['password1'] !== $submissionVars['password2']) {
			register_error(elgg_echo('install:admin:password:mismatch'));
			return FALSE;
		}

		if (trim($submissionVars['password1']) == "") {
			register_error(elgg_echo('install:admin:password:empty'));
			return FALSE;
		}

		// check that email address is email address
		if ($submissionVars['email'] && !is_email_address($submissionVars['email'])) {
			$msg = sprintf(elgg_echo('install:error:emailaddress'), $submissionVars['email']);
			register_error($msg);
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Create a user account for the admin
	 *
	 * @param array $submissionVars
	 * @param bool $login Login in the admin user?
	 * @return bool
	 */
	protected function createAdminAccount($submissionVars, $login = FALSE) {
		global $CONFIG;

		$guid = register_user(
				$submissionVars['username'],
				$submissionVars['password1'],
				$submissionVars['displayname'],
				$submissionVars['email']
				);

		if (!$guid) {
			register_error(elgg_echo('install:admin:cannot_create'));
			return FALSE;
		}

		$user = get_entity($guid);
		if (!$user) {
			register_error(elgg_echo('install:error:loadadmin'));
			return FALSE;
		}

		elgg_set_ignore_access(TRUE);
		if ($user->makeAdmin() == FALSE) {
			register_error(elgg_echo('install:error:adminaccess'));
		}
		elgg_set_ignore_access(FALSE);

		// add validation data to satisfy the user validation plugins
		create_metadata($guid, 'validated', TRUE, '', 0, ACCESS_PUBLIC);
		create_metadata($guid, 'validated_method', 'admin_user', '', 0, ACCESS_PUBLIC);

		if ($login) {
			if (login($user) == FALSE) {
				register_error(elgg_echo('install:error:adminlogin'));				
			}
		}

		return TRUE;
	}
}
