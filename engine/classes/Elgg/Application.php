<?php

namespace Elgg;

use Elgg\Di\ServiceProvider;
use Elgg\Filesystem\Directory;

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

	const REWRITE_TEST_TOKEN = '__testing_rewrite';
	const REWRITE_TEST_OUTPUT = 'success';

	/**
	 * @var ServiceProvider
	 */
	private $services;

	/**
	 * @var string
	 */
	private $engine_dir;

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
	 */
	public function __construct(ServiceProvider $services) {
		$this->services = $services;

		/**
		 * The time with microseconds when the Elgg engine was started.
		 *
		 * @global float
		 */
		if (!isset($GLOBALS['START_MICROTIME'])) {
			$GLOBALS['START_MICROTIME'] = microtime(true);
		}

		$services->timer->begin([]);

		$this->engine_dir = dirname(dirname(__DIR__));
	}

	/**
	 * Load settings.php
	 *
	 * This is done automatically during the boot process or before requesting a database object
	 *
	 * @see Config::loadSettingsFile
	 * @return void
	 */
	public function loadSettings() {
		$this->services->config->loadSettingsFile();
	}

	/**
	 * Load all Elgg procedural code and wire up boot events, but don't boot
	 *
	 * This is used for internal testing purposes
	 *
	 * @return void
	 * @access private
	 * @internal
	 */
	public function loadCore() {
		if (self::$core_loaded) {
			return;
		}

		$lib_dir = self::elggDir()->chroot("engine/lib");

		// load the rest of the library files from engine/lib/
		// All on separate lines to make diffs easy to read + make it apparent how much
		// we're actually loading on every page (Hint: it's too much).
		$lib_files = [
			// Needs to be loaded first to correctly bootstrap
			'autoloader.php',
			'elgglib.php',

			// The order of these doesn't matter, so keep them alphabetical
			'access.php',
			'actions.php',
			'admin.php',
			'annotations.php',
			'cache.php',
			'comments.php',
			'configuration.php',
			'cron.php',
			'database.php',
			'entities.php',
			'extender.php',
			'filestore.php',
			'group.php',
			'input.php',
			'languages.php',
			'mb_wrapper.php',
			'memcache.php',
			'metadata.php',
			'metastrings.php',
			'navigation.php',
			'notification.php',
			'objects.php',
			'output.php',
			'pagehandler.php',
			'pageowner.php',
			'pam.php',
			'plugins.php',
			'private_settings.php',
			'relationships.php',
			'river.php',
			'sessions.php',
			'sites.php',
			'statistics.php',
			'system_log.php',
			'tags.php',
			'user_settings.php',
			'users.php',
			'upgrade.php',
			'views.php',
			'widgets.php',

			// backward compatibility
			'deprecated-2.1.php',
			'deprecated-3.0.php',
		];

		// isolate global scope
		call_user_func(function () use ($lib_dir, $lib_files) {

			$setups = [];

			// include library files, capturing setup functions
			foreach ($lib_files as $file) {
				$setup = (include_once $lib_dir->getPath($file));
				if (!$setup) {
					throw new \InstallationException("Elgg installation is missing file engine/lib/$file");
				}
				if ($setup instanceof \Closure) {
					$setups[$file] = $setup;
				}
			}

			// store instance to be returned by elgg()
			self::$_instance = $this;

			// set up autoloading and DIC
			_elgg_services($this->services);

			$events = $this->services->events;
			$hooks = $this->services->hooks;

			// run setups
			foreach ($setups as $func) {
				$func($events, $hooks);
			}
		});

		self::$core_loaded = true;
	}

	/**
	 * Start and boot the core
	 *
	 * @return self
	 */
	public static function start() {
		$app = self::create();
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

		if ($config->get('boot_complete')) {
			return;
		}

		$this->loadSettings();
		$this->resolveWebRoot();

		$config->set('boot_complete', false);

		// This will be overridden by the DB value but may be needed before the upgrade script can be run.
		$config->set('default_limit', 10);

		// in case not loaded already
		$this->loadCore();

		// Connect to database, load language files, load configuration, init session
		$this->services->boot->boot();
		elgg_views_boot();

		// Load the plugins that are active
		$this->services->plugins->load();

		$root = Directory\Local::root();
		if ($root->getPath() != self::elggDir()->getPath()) {
			// Elgg is installed as a composer dep, so try to treat the root directory
			// as a custom plugin that is always loaded last and can't be disabled...
			if (!elgg_get_config('system_cache_loaded')) {
				// configure view locations for the custom plugin (not Elgg core)
				$viewsFile = $root->getFile('views.php');
				if ($viewsFile->exists()) {
					$viewsSpec = $viewsFile->includeFile();
					if (is_array($viewsSpec)) {
						_elgg_services()->views->mergeViewsSpec($viewsSpec);
					}
				}

				// find views for the custom plugin (not Elgg core)
				_elgg_services()->views->registerPluginViews($root->getPath());
			}

			if (!elgg_get_config('i18n_loaded_from_cache')) {
				_elgg_services()->translator->registerPluginTranslations($root->getPath());
			}

			// This is root directory start.php
			$root_start = $root->getPath("start.php");
			if (is_file($root_start)) {
				require $root_start;
			}
		}


		// @todo move loading plugins into a single boot function that replaces 'boot', 'system' event
		// and then move this code in there.
		// This validates the view type - first opportunity to do it is after plugins load.
		$viewtype = elgg_get_viewtype();
		if (!elgg_is_registered_viewtype($viewtype)) {
			elgg_set_viewtype('default');
		}

		$this->allowPathRewrite();

		$events = $this->services->events;

		// Allows registering handlers strictly before all init, system handlers
		$events->trigger('plugins_boot', 'system');

		// Complete the boot process for both engine and plugins
		$events->trigger('init', 'system');

		$config->set('boot_complete', true);

		// System loaded and ready
		$events->trigger('ready', 'system');
	}

	/**
	 * Get a Database wrapper for performing queries without booting Elgg
	 *
	 * If settings.php has not been loaded, it will be loaded to configure the DB connection.
	 *
	 * @note Before boot, the Database instance will not yet be bound to a Logger.
	 *
	 * @return \Elgg\Application\Database
	 */
	public function getDb() {
		$this->loadSettings();
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
	 * @return self
	 */
	private static function create() {
		if (self::$_instance === null) {
			// we need to register for shutdown before Symfony registers the
			// session_write_close() function. https://github.com/Elgg/Elgg/issues/9243
			register_shutdown_function(function () {
				// There are cases where we may exit before this function is defined
				if (function_exists('_elgg_shutdown_hook')) {
					_elgg_shutdown_hook();
				}
			});

			self::$_instance = new self(new Di\ServiceProvider(new Config()));
			self::$_instance->initErrorHandling();
		}

		return self::$_instance;
	}

	/**
	 * Elgg's front controller. Handles basically all incoming URL requests.
	 *
	 * @return bool True if Elgg will handle the request, false if the server should (PHP-CLI server)
	 */
	public static function index() {
		return self::create()->run();
	}

	/**
	 * Routes the request, booting core if not yet booted
	 *
	 * @return bool False if Elgg wants the PHP CLI server to handle the request
	 */
	public function run() {
		$config = $this->services->config;

		$request = $this->services->request;
		$path = $request->getPathInfo();

		// allow testing from the upgrade page before the site is upgraded.
		if (isset($_GET[self::REWRITE_TEST_TOKEN])) {
			if (false !== strpos($path, self::REWRITE_TEST_TOKEN)) {
				echo self::REWRITE_TEST_OUTPUT;
			}
			return true;
		}

		if (php_sapi_name() === 'cli-server') {
			// overwrite value from settings
			$www_root = rtrim($request->getSchemeAndHttpHost() . $request->getBaseUrl(), '/') . '/';
			$config->set('wwwroot', $www_root);
		}

		if (0 === strpos($path, '/cache/')) {
			(new Application\CacheHandler($this, $config, $_SERVER))->handleRequest($path);
			return true;
		}

		if (0 === strpos($path, '/serve-file/')) {
			$this->services->serveFileHandler->getResponse($request)->send();
			return true;
		}

		if ($path === '/rewrite.php') {
			require Directory\Local::root()->getPath("install.php");
			return true;
		}

		if (php_sapi_name() === 'cli-server') {
			// The CLI server routes ALL requests here (even existing files), so we have to check for these.
			if ($path !== '/' && Directory\Local::root()->isFile($path)) {
				// serve the requested resource as-is.
				return false;
			}
		}

		$this->bootCore();

		// TODO use formal Response object instead
		header("Content-Type: text/html;charset=utf-8");

		// fetch new request from services in case it was replaced by route:rewrite
		if (!$this->services->router->route($this->services->request)) {
			forward('', '404');
		}
	}

	/**
	 * Use this application to handle errors and exceptions
	 *
	 * @access private
	 * @return void
	 * @internal
	 */
	public function initErrorHandling() {
		set_error_handler([$this, 'handleErrors']);
		set_exception_handler([$this, 'handleExceptions']);
	}

	/**
	 * Get the Elgg data directory with trailing slash
	 *
	 * @return string
	 */
	public static function getDataPath() {
		return self::create()->services->config->getDataPath();
	}

	/**
	 * Returns a directory that points to the root of Elgg, but not necessarily
	 * the install root. See `self::root()` for that.
	 *
	 * @return Directory
	 */
	public static function elggDir() /*: Directory*/ {
		return Directory\Local::fromPath(realpath(__DIR__ . '/../../..'));
	}

	/**
	 * Renders a web UI for installing Elgg.
	 *
	 * @return void
	 */
	public static function install() {
		ini_set('display_errors', 1);
		$installer = new \ElggInstaller();
		$step = get_input('step', 'welcome');
		$installer->run($step);
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
		if (elgg_get_config('security_protect_upgrade') && !elgg_is_admin_logged_in()) {
			// only admin's or users with a valid token can run upgrade.php
			elgg_signed_request_gatekeeper();
		}
		
		$site_url = elgg_get_config('url');
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
	 * Make sure config has a non-empty wwwroot. Calculate from request if missing.
	 *
	 * @return void
	 */
	private function resolveWebRoot() {
		$config = $this->services->config;
		$request = $this->services->request;

		$config->loadSettingsFile();
		if (!$config->get('wwwroot')) {
			$www_root = rtrim($request->getSchemeAndHttpHost() . $request->getBaseUrl(), '/') . '/';
			$config->set('wwwroot', $www_root);
		}
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
	 * @param \Exception $exception The exception being handled
	 *
	 * @return void
	 * @access private
	 */
	public function handleExceptions(\Exception $exception) {
		$timestamp = time();
		error_log("Exception at time $timestamp: $exception");

		// Wipe any existing output buffer
		ob_end_clean();

		// make sure the error isn't cached
		header("Cache-Control: no-cache, must-revalidate", true);
		header('Expires: Fri, 05 Feb 1982 00:00:00 -0500', true);

		if (!self::$core_loaded) {
			http_response_code(500);
			echo "Exception loading Elgg core. Check log at time $timestamp";
			return;
		}

		try {
			$exception_include = $this->services->config->get('exception_include');

			// allow custom scripts to trigger on exception
			// $CONFIG->exception_include can be set locally in settings.php
			// value should be a system path to a file to include
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
				if (_elgg_services()->config->get('debug') === 'NOTICE') {
					if (!$log("PHP (errno $errno): $error", 'NOTICE')) {
						error_log("PHP NOTICE: $error");
					}
				}
		}

		return true;
	}
}
