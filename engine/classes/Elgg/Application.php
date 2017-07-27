<?php

namespace Elgg;

use Elgg\Database\SiteSecret;
use Elgg\Di\ServiceProvider;
use Elgg\Filesystem\Directory;
use Elgg\Http\Request;
use Elgg\Filesystem\Directory\Local;
use ConfigurationException;
use Elgg\Project\Paths;

/**
 * Load, boot, and implement a front controller for an Elgg application
 *
 * To run as PHP CLI server:
 * <code>php -S localhost:8888 /full/path/to/elgg/index.php</code>
 *
 * The full path is necessary to work around this: https://bugs.php.net/bug.php?id=55726
 *
 * @since 2.0.0
 *
 * @property-read \Elgg\Menu\Service $menus
 * @property-read \Elgg\Views\TableColumn\ColumnFactory $table_columns
 */
class Application {

	const DEFAULT_LANG = 'en';
	const DEFAULT_LIMIT = 10;

	/**
	 * @var ServiceProvider
	 */
	private $services;

	/**
	 * @var bool
	 */
	private static $core_loaded = false;

	/**
	 * @var bool
	 */
	private static $testing_app;

	/**
	 * Property names of the service provider to be exposed via __get()
	 *
	 * E.g. the presence of `'foo' => true` in the list would allow _elgg_services()->foo to
	 * be accessed via elgg()->foo.
	 *
	 * @var string[]
	 */
	private static $public_services = [
		//'config' => true,
		'menus' => true,
		'table_columns' => true,
	];

	/**
	 * Reference to the loaded Application returned by elgg()
	 *
	 * @internal Do not use this. use elgg() to access the application
	 * @access private
	 * @var Application
	 */
	public static $_instance;

	/**
	 * Constructor
	 *
	 * Upon construction, no actions are taken to load or boot Elgg.
	 *
	 * @param ServiceProvider $services Elgg services provider
	 * @throws ConfigurationException
	 */
	public function __construct(ServiceProvider $services) {
		$this->services = $services;
		$services->setValue('app', $this);

		$this->initConfig();
	}

	/**
	 * Validate, normalize, fill in missing values, and lock some
	 *
	 * @return void
	 * @throws ConfigurationException
	 */
	private function initConfig() {
		$config = $this->services->config;

		if ($config->Config_locks === null) {
			$config->Config_locks = true;
		}

		if ($config->Config_locks) {
			$lock = function ($name) use ($config) {
				$config->lock($name);
			};
		} else {
			// the installer needs to build an application with defaults then update
			// them after they're validated, so we don't want to lock them.
			$lock = function () {
			};
		}

		$this->services->timer->begin([]);

		// Until DB loads, let's log problems
		if ($config->debug === null) {
			$config->debug = 'NOTICE';
		}

		if ($config->dataroot) {
			$config->dataroot = rtrim($config->dataroot, '\\/') . DIRECTORY_SEPARATOR;
		} else {
			if (!$config->installer_running) {
				throw new ConfigurationException('Config value "dataroot" is required.');
			}
		}
		$lock('dataroot');

		if ($config->cacheroot) {
			$config->cacheroot = rtrim($config->cacheroot, '\\/') . DIRECTORY_SEPARATOR;
		} else {
			$config->cacheroot = $config->dataroot;
		}
		$lock('cacheroot');

		if ($config->wwwroot) {
			$config->wwwroot = rtrim($config->wwwroot, '/') . '/';
		} else {
			$config->wwwroot = $this->services->request->sniffElggUrl();
		}
		$lock('wwwroot');

		if (!$config->language) {
			$config->language = self::DEFAULT_LANG;
		}

		if ($config->default_limit) {
			$lock('default_limit');
		} else {
			$config->default_limit = self::DEFAULT_LIMIT;
		}

		$locked_props = [
			'site_guid' => 1,
			'path' => Paths::project(),
			'plugins_path' => Paths::project() . "mod/",
			'pluginspath' => Paths::project() . "mod/",
			'url' => $config->wwwroot,
		];
		foreach ($locked_props as $name => $value) {
			$config->$name = $value;
			$lock($name);
		}

		// move sensitive credentials into isolated services
		$this->services->dbConfig;

		// move sensitive credentials into isolated services
		$secret = SiteSecret::fromConfig($config);
		if ($secret) {
			$this->services->setValue('siteSecret', $secret);
		}

		$config->boot_complete = false;
	}

	/**
	 * Get the DB credentials.
	 *
	 * We no longer leave DB credentials in the config in case it gets accidentally dumped.
	 *
	 * @return \Elgg\Database\DbConfig
	 */
	public function getDbConfig() {
		return $this->services->dbConfig;
	}

	/**
	 * Define all Elgg global functions and constants, wire up boot events, but don't boot
	 *
	 * This includes all the .php files in engine/lib (not upgrades). If a script returns a function,
	 * it is queued and executed at the end.
	 *
	 * @return void
	 * @access private
	 * @internal
	 * @throws \InstallationException
	 */
	public function loadCore() {
		if (self::$core_loaded) {
			return;
		}

		$setups = [];
		$path = Paths::elgg() . 'engine/lib';

		$files = scandir($path, SCANDIR_SORT_ASCENDING);
		$files = array_filter($files, function ($file) use ($path) {
			if (!is_file("$path/$file")) {
				return false;
			}
			return substr($file, -4) === '.php';
		});

		// include library files, capturing setup functions
		foreach ($files as $file) {
			$return = Includer::includeFile("$path/$file");
			if (!$return) {
				throw new \InstallationException("Elgg lib file failed include: engine/lib/$file");
			}
			if ($return instanceof \Closure) {
				$setups[$file] = $return;
			}
		}

		// store instance to be returned by elgg()
		self::$_instance = $this;

		// allow global services access. :(
		_elgg_services($this->services);

		// setup logger and inject into config
		//$this->services->config->setLogger($this->services->logger);

		$hooks = $this->services->hooks;
		$events = $hooks->getEvents();

		// run setups
		foreach ($setups as $func) {
			$func($events, $hooks);
		}

		self::$core_loaded = true;
	}

	/**
	 * Start and boot the core
	 *
	 * @return self
	 */
	public static function start() {
		$app = self::factory();
		$app->bootCore();
		return $app;
	}

	/**
	 * Bootstrap the Elgg engine, loads plugins, and calls initial system events
	 *
	 * This method loads the full Elgg engine, checks the installation
	 * state, and triggers a series of events to finish booting Elgg:
	 * 	- {@elgg_event boot system}
	 * 	- {@elgg_event init system}
	 * 	- {@elgg_event ready system}
	 *
	 * If Elgg is not fully installed, the browser will be redirected to an installation page.
	 *
	 * @return void
	 */
	public function bootCore() {
		$config = $this->services->config;

		if ($this->isTestingApplication()) {
			throw new \RuntimeException('Unit tests should not call ' . __METHOD__);
		}

		if ($config->boot_complete) {
			return;
		}

		// in case not loaded already
		$this->loadCore();

		if (!$this->services->db) {
			// no database boot!
			elgg_views_boot();
			$this->services->session->start();
			$this->services->translator->loadTranslations();

			actions_init();
			_elgg_init();
			_elgg_input_init();
			_elgg_nav_init();

			$config->boot_complete = true;
			$config->lock('boot_complete');
			return;
		}

		// Connect to database, load language files, load configuration, init session
		$this->services->boot->boot($this->services);

		elgg_views_boot();

		// Load the plugins that are active
		$this->services->plugins->load();

		if (Paths::project() != Paths::elgg()) {
			// Elgg is installed as a composer dep, so try to treat the root directory
			// as a custom plugin that is always loaded last and can't be disabled...
			if (!$config->system_cache_loaded) {
				// configure view locations for the custom plugin (not Elgg core)
				$viewsFile = Paths::project() . 'views.php';
				if (is_file($viewsFile)) {
					$viewsSpec = Includer::includeFile($viewsFile);
					if (is_array($viewsSpec)) {
						$this->services->views->mergeViewsSpec($viewsSpec);
					}
				}

				// find views for the custom plugin (not Elgg core)
				$this->services->views->registerPluginViews(Paths::project());
			}

			if (!$config->i18n_loaded_from_cache) {
				$this->services->translator->registerPluginTranslations(Paths::project());
			}

			// This is root directory start.php
			$root_start = Paths::project() . "start.php";
			if (is_file($root_start)) {
				require $root_start;
			}
		}

		$this->allowPathRewrite();

		$events = $this->services->hooks->getEvents();

		// Allows registering handlers strictly before all init, system handlers
		$events->trigger('plugins_boot', 'system');

		// Complete the boot process for both engine and plugins
		$events->trigger('init', 'system');

		$config->boot_complete = true;
		$config->lock('boot_complete');

		// System loaded and ready
		$events->trigger('ready', 'system');
	}

	/**
	 * Get a Database wrapper for performing queries without booting Elgg
	 *
	 * If settings has not been loaded, it will be loaded to configure the DB connection.
	 *
	 * @note Before boot, the Database instance will not yet be bound to a Logger.
	 *
	 * @return \Elgg\Application\Database
	 */
	public function getDb() {
		return $this->services->publicDb;
	}

	/**
	 * Get an undefined property
	 *
	 * @param string $name The property name accessed
	 *
	 * @return mixed
	 */
	public function __get($name) {
		if (isset(self::$public_services[$name])) {
			return $this->services->{$name};
		}
		trigger_error("Undefined property: " . __CLASS__ . ":\${$name}");
	}

	/**
	 * Creates a new, trivial instance of Elgg\Application and set it as the singleton instance.
	 * If the singleton is already set, it's returned.
	 *
	 * @param array $spec Specification for initial call.
	 * @return self
	 * @throws ConfigurationException
	 */
	public static function factory(array $spec = []) {
		if (self::$_instance !== null) {
			return self::$_instance;
		}

		$defaults = [
			'service_provider' => null,
			'config' => null,
			'settings_path' => null,
			'handle_exceptions' => true,
			'handle_shutdown' => true,
			'overwrite_global_config' => true,
			'set_start_time' => true,
			'request' => null,
		];
		$spec = array_merge($defaults, $spec);

		if ($spec['set_start_time']) {
			/**
			 * The time with microseconds when the Elgg engine was started.
			 *
			 * @global float
			 */
			if (!isset($GLOBALS['START_MICROTIME'])) {
				$GLOBALS['START_MICROTIME'] = microtime(true);
			}
		}

		if (!$spec['service_provider']) {
			if (!$spec['config']) {
				$spec['config'] = Config::factory($spec['settings_path']);
			}
			$spec['service_provider'] = new ServiceProvider($spec['config']);
		}

		if ($spec['request']) {
			if ($spec['request'] instanceof Request) {
				$spec['service_provider']->setValue('request', $spec['request']);
			} else {
				throw new \InvalidArgumentException("Given request is not a " . Request::class);
			}
		}

		self::$_instance = new self($spec['service_provider']);

		if ($spec['handle_exceptions']) {
			set_error_handler([self::$_instance, 'handleErrors']);
			set_exception_handler([self::$_instance, 'handleExceptions']);
		}

		if ($spec['handle_shutdown']) {
			// we need to register for shutdown before Symfony registers the
			// session_write_close() function. https://github.com/Elgg/Elgg/issues/9243
			register_shutdown_function(function () {
				// There are cases where we may exit before this function is defined
				if (function_exists('_elgg_shutdown_hook')) {
					_elgg_shutdown_hook();
				}
			});
		}

		if ($spec['overwrite_global_config']) {
			global $CONFIG;

			// this will be buggy be at least PHP will log failures
			$CONFIG = $spec['service_provider']->config;
		}

		return self::$_instance;
	}

	/**
	 * Elgg's front controller. Handles basically all incoming URL requests.
	 *
	 * @return bool True if Elgg will handle the request, false if the server should (PHP-CLI server)
	 */
	public static function index() {
		$req = Request::createFromGlobals();
		/** @var Request $req */

		if ($req->isRewriteCheck()) {
			echo Request::REWRITE_TEST_OUTPUT;
			return true;
		}

		return self::factory(['request' => $req])->run();
	}

	/**
	 * Routes the request, booting core if not yet booted
	 *
	 * @return bool False if Elgg wants the PHP CLI server to handle the request
	 */
	public function run() {
		$config = $this->services->config;
		$request = $this->services->request;

		if ($request->isCliServer()) {
			if ($request->isCliServable(Paths::project())) {
				return false;
			}

			// overwrite value from settings
			$www_root = rtrim($request->getSchemeAndHttpHost() . $request->getBaseUrl(), '/') . '/';
			$config->wwwroot = $www_root;
			$config->wwwroot_cli_server = $www_root;
		}

		if (0 === strpos($request->getElggPath(), '/cache/')) {
			$this->services->cacheHandler->handleRequest($request)->prepare($request)->send();
			return true;
		}

		if (0 === strpos($request->getElggPath(), '/serve-file/')) {
			$this->services->serveFileHandler->getResponse($request)->send();
			return true;
		}

		$this->bootCore();

		// TODO use formal Response object instead
		// This is to set the charset to UTF-8.
		header("Content-Type: text/html;charset=utf-8", true);

		// re-fetch new request from services in case it was replaced by route:rewrite
		$request = $this->services->request;

		if (!$this->services->router->route($request)) {
			forward('', '404');
		}
	}

	/**
	 * Returns a directory that points to the root of Elgg, but not necessarily
	 * the install root. See `self::root()` for that.
	 *
	 * @return Directory
	 */
	public static function elggDir() {
		return Local::elggRoot();
	}

	/**
	 * Returns a directory that points to the project root, where composer is installed.
	 *
	 * @return Directory
	 */
	public static function projectDir() {
		return Local::projectRoot();
	}

	/**
	 * Renders a web UI for installing Elgg.
	 *
	 * @return void
	 */
	public static function install() {
		ini_set('display_errors', 1);
		$installer = new \ElggInstaller();
		$installer->run();
	}

	/**
	 * Elgg upgrade script.
	 *
	 * This script triggers any necessary upgrades. If the site has been upgraded
	 * to the most recent version of the code, no upgrades are run but the caches
	 * are flushed.
	 *
	 * Upgrades use a table {db_prefix}upgrade_lock as a mutex to prevent concurrent upgrades.
	 *
	 * The URL to forward to after upgrades are complete can be specified by setting $_GET['forward']
	 * to a relative URL.
	 *
	 * @return void
	 */
	public static function upgrade() {
		// we want to know if an error occurs
		ini_set('display_errors', 1);

		define('UPGRADING', 'upgrading');

		self::start();
		
		// check security settings
		if (_elgg_config()->security_protect_upgrade && !elgg_is_admin_logged_in()) {
			// only admin's or users with a valid token can run upgrade.php
			elgg_signed_request_gatekeeper();
		}
		
		$site_url = _elgg_config()->url;
		$site_host = parse_url($site_url, PHP_URL_HOST) . '/';

		// turn any full in-site URLs into absolute paths
		$forward_url = get_input('forward', '/admin', false);
		$forward_url = str_replace([$site_url, $site_host], '/', $forward_url);

		if (strpos($forward_url, '/') !== 0) {
			$forward_url = '/' . $forward_url;
		}

		if (get_input('upgrade') == 'upgrade') {
			$upgrader = _elgg_services()->upgrades;
			$result = $upgrader->run();

			if ($result['failure'] == true) {
				register_error($result['reason']);
				forward($forward_url);
			}

			// Find unprocessed batch upgrade classes and save them as ElggUpgrade objects
			$core_upgrades = (require self::elggDir()->getPath('engine/lib/upgrades/async-upgrades.php'));
			$has_pending_upgrades = _elgg_services()->upgradeLocator->run($core_upgrades);

			if ($has_pending_upgrades) {
				// Forward to the list of pending upgrades
				$forward_url = '/admin/upgrades';
			}
		} else {
			$rewriteTester = new \ElggRewriteTester();
			$url = elgg_get_site_url() . "__testing_rewrite?__testing_rewrite=1";
			if (!$rewriteTester->runRewriteTest($url)) {
				// see if there is a problem accessing the site at all
				// due to ip restrictions for example
				if (!$rewriteTester->runLocalhostAccessTest()) {
					// note: translation may not be available until after upgrade
					$msg = elgg_echo("installation:htaccess:localhost:connectionfailed");
					if ($msg === "installation:htaccess:localhost:connectionfailed") {
						$msg = "Elgg cannot connect to itself to test rewrite rules properly. Check "
								. "that curl is working and there are no IP restrictions preventing "
								. "localhost connections.";
					}
					echo $msg;
					exit;
				}

				// note: translation may not be available until after upgrade
				$msg = elgg_echo("installation:htaccess:needs_upgrade");
				if ($msg === "installation:htaccess:needs_upgrade") {
					$msg = "You must update your .htaccess file (use install/config/htaccess.dist as a guide).";
				}
				echo $msg;
				exit;
			}

			$vars = [
				'forward' => $forward_url
			];

			// reset cache to have latest translations available during upgrade
			elgg_reset_system_cache();

			echo elgg_view_page(elgg_echo('upgrading'), '', 'upgrade', $vars);
			exit;
		}

		forward($forward_url);
	}

	/**
	 * Allow plugins to rewrite the path.
	 *
	 * @return void
	 */
	private function allowPathRewrite() {
		$request = $this->services->request;
		$new = $this->services->router->allowRewrite($request);
		if ($new === $request) {
			return;
		}

		$this->services->setValue('request', $new);
		$this->services->context->initialize($new);
	}

	/**
	 * Flag this application as running for testing (PHPUnit)
	 *
	 * @param bool $testing Is testing application
	 * @return void
	 */
	public static function setTestingApplication($testing = true) {
		self::$testing_app = $testing;
	}

	/**
	 * Checks if the application is running in PHPUnit
	 * @return bool
	 */
	public static function isTestingApplication() {
		return (bool) self::$testing_app;
	}

	/**
	 * Intercepts, logs, and displays uncaught exceptions.
	 *
	 * To use a viewtype other than failsafe, create the views:
	 *  <viewtype>/messages/exceptions/admin_exception
	 *  <viewtype>/messages/exceptions/exception
	 * See the json viewtype for an example.
	 *
	 * @warning This function should never be called directly.
	 *
	 * @see http://www.php.net/set-exception-handler
	 *
	 * @param \Exception|\Error $exception The exception/error being handled
	 *
	 * @return void
	 * @access private
	 */
	public function handleExceptions($exception) {
		$timestamp = time();
		error_log("Exception at time $timestamp: $exception");

		// Wipe any existing output buffer
		ob_end_clean();

		// make sure the error isn't cached
		header("Cache-Control: no-cache, must-revalidate", true);
		header('Expires: Fri, 05 Feb 1982 00:00:00 -0500', true);

		if ($exception instanceof \InstallationException) {
			forward('/install.php');
		}

		if (!self::$core_loaded) {
			http_response_code(500);
			echo "Exception loading Elgg core. Check log at time $timestamp";
			return;
		}

		try {
			// allow custom scripts to trigger on exception
			// value in .env.php should be a system path to a file to include
			$exception_include = $this->services->config->exception_include;

			if ($exception_include && is_file($exception_include)) {
				ob_start();

				// don't isolate, these scripts may use the local $exception var.
				include $exception_include;

				$exception_output = ob_get_clean();

				// if content is returned from the custom handler we will output
				// that instead of our default failsafe view
				if (!empty($exception_output)) {
					echo $exception_output;
					exit;
				}
			}

			if (elgg_is_xhr()) {
				elgg_set_viewtype('json');
				$response = new \Symfony\Component\HttpFoundation\JsonResponse(null, 500);
			} else {
				elgg_set_viewtype('failsafe');
				$response = new \Symfony\Component\HttpFoundation\Response('', 500);
			}

			if (elgg_is_admin_logged_in()) {
				$body = elgg_view("messages/exceptions/admin_exception", [
					'object' => $exception,
					'ts' => $timestamp
				]);
			} else {
				$body = elgg_view("messages/exceptions/exception", [
					'object' => $exception,
					'ts' => $timestamp
				]);
			}

			$response->setContent(elgg_view_page(elgg_echo('exception:title'), $body));
			$response->send();
		} catch (\Exception $e) {
			$timestamp = time();
			$message = $e->getMessage();
			http_response_code(500);
			echo "Fatal error in exception handler. Check log for Exception at time $timestamp";
			error_log("Exception at time $timestamp : fatal error in exception handler : $message");
		}
	}

	/**
	 * Intercepts catchable PHP errors.
	 *
	 * @warning This function should never be called directly.
	 *
	 * @internal
	 * For catchable fatal errors, throws an Exception with the error.
	 *
	 * For non-fatal errors, depending upon the debug settings, either
	 * log the error or ignore it.
	 *
	 * @see http://www.php.net/set-error-handler
	 *
	 * @param int    $errno    The level of the error raised
	 * @param string $errmsg   The error message
	 * @param string $filename The filename the error was raised in
	 * @param int    $linenum  The line number the error was raised at
	 * @param array  $vars     An array that points to the active symbol table where error occurred
	 *
	 * @return true
	 * @throws \Exception
	 * @access private
	 */
	public function handleErrors($errno, $errmsg, $filename, $linenum, $vars) {
		$error = date("Y-m-d H:i:s (T)") . ": \"$errmsg\" in file $filename (line $linenum)";

		$log = function ($message, $level) {
			if (self::$core_loaded) {
				return elgg_log($message, $level);
			}

			return false;
		};

		switch ($errno) {
			case E_USER_ERROR:
				if (!$log("PHP: $error", 'ERROR')) {
					error_log("PHP ERROR: $error");
				}
				if (self::$core_loaded) {
					register_error("ERROR: $error");
				}

				// Since this is a fatal error, we want to stop any further execution but do so gracefully.
				throw new \Exception($error);
				break;

			case E_WARNING :
			case E_USER_WARNING :
			case E_RECOVERABLE_ERROR: // (e.g. type hint violation)

				// check if the error wasn't suppressed by the error control operator (@)
				if (error_reporting() && !$log("PHP: $error", 'WARNING')) {
					error_log("PHP WARNING: $error");
				}
				break;

			default:
				if (function_exists('_elgg_config')) {
					$debug = _elgg_config()->debug;
				} else {
					$debug = isset($GLOBALS['CONFIG']->debug) ? $GLOBALS['CONFIG']->debug : null;
				}
				if ($debug !== 'NOTICE') {
					return true;
				}

				if (!$log("PHP (errno $errno): $error", 'NOTICE')) {
					error_log("PHP NOTICE: $error");
				}
		}

		return true;
	}

	/**
	 * Does nothing.
	 *
	 * @return void
	 * @deprecated
	 */
	public function loadSettings() {
		trigger_error(__METHOD__ . ' is no longer needed and will be removed.');
	}
}
