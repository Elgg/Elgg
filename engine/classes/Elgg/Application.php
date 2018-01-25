<?php

namespace Elgg;

use ConfigurationException;
use Doctrine\DBAL\Connection;
use Elgg\Database\DbConfig;
use Elgg\Di\ServiceProvider;
use Elgg\Filesystem\Directory;
use Elgg\Filesystem\Directory\Local;
use Elgg\Http\ErrorResponse;
use Elgg\Http\RedirectResponse;
use Elgg\Http\Request;
use Elgg\Project\Paths;
use InstallationException;
use InvalidArgumentException;
use InvalidParameterException;
use SecurityException;

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
	 *
	 * @internal DO NOT USE
	 */
	public $_services;

	/**
	 * @var \Closure[]
	 */
	private static $_setups = [];

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
	 * Get the global Application instance. If not set, it's auto-created and wired to $CONFIG.
	 *
	 * @return Application|null
	 */
	public static function getInstance() {
		if (self::$_instance === null) {
			self::$_instance = self::factory();
			self::setGlobalConfig(self::$_instance);
		}
		return self::$_instance;
	}

	/**
	 * Set the global Application instance
	 *
	 * @param Application $application Global application
	 * @return void
	 */
	public static function setInstance(Application $application = null) {
		self::$_instance = $application;
	}

	/**
	 * Constructor
	 *
	 * Upon construction, no actions are taken to load or boot Elgg.
	 *
	 * @param ServiceProvider $services Elgg services provider
	 * @throws ConfigurationException
	 */
	public function __construct(ServiceProvider $services) {
		$this->_services = $services;
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
	public static function loadCore() {
		if (self::isCoreLoaded()) {
			return;
		}

		$path = Paths::elgg() . 'engine/lib';

		// include library files, capturing setup functions
		foreach (self::getEngineLibs() as $file) {
			try {
				self::requireSetupFileOnce("$path/$file");
			} catch (\Error $e) {
				throw new \InstallationException("Elgg lib file failed include: engine/lib/$file");
			}
		}
	}

	/**
	 * Require a library/plugin file once and capture returned anonymous functions
	 *
	 * @param string   $file      File to require
	 * @param \Closure $condition Condition that must be met for the setup file to be executable
	 * @return mixed
	 * @internal
	 * @access private
	 */
	public static function requireSetupFileOnce($file, \Closure $condition = null) {
		$return = Includer::requireFileOnce($file);
		if ($return instanceof \Closure) {
			if ($condition) {
				$setup = function() use ($condition, $return) {
					return $condition() ? $return : null;
				};
			} else {
				$setup = $return;
			}
			self::$_setups[] = $setup;
		}
		return $return;
	}

	/**
	 * Start and boot the core
	 *
	 * @return self
	 */
	public static function start() {
		$app = self::getInstance();
		$app->bootCore();
		return $app;
	}

	/**
	 * Are Elgg's global functions loaded?
	 *
	 * @return bool
	 */
	public static function isCoreLoaded() {
		return function_exists('elgg');
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
	 * @throws InstallationException
	 */
	public function bootCore() {
		$config = $this->_services->config;

		if ($config->boot_complete) {
			return;
		}

		// in case not loaded already
		$this->loadCore();

		$hooks = $this->_services->hooks;
		$events = $hooks->getEvents();

		foreach (self::$_setups as $setup) {
			$setup($events, $hooks);
		}

		if (!$this->_services->db) {
			// no database boot!
			elgg_views_boot();
			$this->_services->session->start();
			$this->_services->translator->loadTranslations();

			actions_init();
			_elgg_init();
			_elgg_input_init();
			_elgg_nav_init();

			$config->boot_complete = true;
			$config->lock('boot_complete');
			return;
		}

		// Connect to database, load language files, load configuration, init session
		$this->_services->boot->boot($this->_services);

		elgg_views_boot();

		// Load the plugins that are active
		$this->_services->plugins->load();

		if (Paths::project() != Paths::elgg()) {
			// Elgg is installed as a composer dep, so try to treat the root directory
			// as a custom plugin that is always loaded last and can't be disabled...
			if (!$config->system_cache_loaded) {
				// configure view locations for the custom plugin (not Elgg core)
				$viewsFile = Paths::project() . 'views.php';
				if (is_file($viewsFile)) {
					$viewsSpec = Includer::includeFile($viewsFile);
					if (is_array($viewsSpec)) {
						$this->_services->views->mergeViewsSpec($viewsSpec);
					}
				}

				// find views for the custom plugin (not Elgg core)
				$this->_services->views->registerPluginViews(Paths::project());
			}

			if (!$config->i18n_loaded_from_cache) {
				$this->_services->translator->registerTranslations(Paths::project() . 'languages');
			}

			// This is root directory start.php
			$root_start = Paths::project() . "start.php";
			if (is_file($root_start)) {
				require $root_start;
			}
		}

		// after plugins are started we know which viewtypes are populated
		$this->_services->views->clampViewtypeToPopulatedViews();

		$this->allowPathRewrite();

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
	 * Get the DB credentials.
	 *
	 * We no longer leave DB credentials in the config in case it gets accidentally dumped.
	 *
	 * @return \Elgg\Database\DbConfig
	 */
	public function getDbConfig() {
		return $this->_services->dbConfig;
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
		return $this->_services->publicDb;
	}

	/**
	 * Get database connection
	 *
	 * @param string $type Connection type
	 * @return Connection|false
	 *
	 * @access private
	 */
	public function getDbConnection($type = 'readwrite') {
		try {
			return $this->getDb()->getConnection($type);
		} catch (\DatabaseException $e) {
			return false;
		}
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
			return $this->_services->{$name};
		}
		trigger_error("Undefined property: " . __CLASS__ . ":\${$name}");
	}

	/**
	 * Make the global $CONFIG a reference to this application's config service
	 *
	 * @param Application $application The Application
	 * @return void
	 */
	public static function setGlobalConfig(Application $application) {
		global $CONFIG;
		$CONFIG = $application->_services->config;
	}

	/**
	 * Create a new application.
	 *
	 * @warning You generally want to use getInstance().
	 *
	 * For normal operation, you must use setInstance() and optionally setGlobalConfig() to wire the
	 * application to Elgg's global API.
	 *
	 * @param array $spec Specification for initial call.
	 * @return self
	 * @throws ConfigurationException
	 * @throws InvalidArgumentException
	 */
	public static function factory(array $spec = []) {
		self::loadCore();

		$defaults = [
			'config' => null,
			'handle_exceptions' => true,
			'handle_shutdown' => true,
			'request' => null,
			'service_provider' => null,
			'set_start_time' => true,
			'settings_path' => null,
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
				throw new InvalidArgumentException("Given request is not a " . Request::class);
			}
		}

		$app = new self($spec['service_provider']);

		if ($spec['handle_exceptions']) {
			set_error_handler([$app, 'handleErrors']);
			set_exception_handler([$app, 'handleExceptions']);
		}

		if ($spec['handle_shutdown']) {
			register_shutdown_function('_elgg_db_run_delayed_queries');
			register_shutdown_function('_elgg_db_log_profiling_data');

			// we need to register for shutdown before Symfony registers the
			// session_write_close() function. https://github.com/Elgg/Elgg/issues/9243
			register_shutdown_function(function () {
				// There are cases where we may exit before this function is defined
				if (function_exists('_elgg_shutdown_hook')) {
					_elgg_shutdown_hook();
				}
			});
		}

		return $app;
	}

	/**
	 * Elgg's front controller. Handles basically all incoming URL requests.
	 *
	 * @return bool True if Elgg will handle the request, false if the server should (PHP-CLI server)
	 * @throws ConfigurationException
	 */
	public static function index() {
		$req = Request::createFromGlobals();
		/** @var Request $req */

		if ($req->isRewriteCheck()) {
			echo Request::REWRITE_TEST_OUTPUT;
			return true;
		}

		try {
			$app = self::factory([
				'request' => $req,
			]);
		} catch (ConfigurationException $ex) {
			return self::install();
		}

		self::setGlobalConfig($app);
		self::setInstance($app);

		return $app->run();
	}

	/**
	 * Routes the request, booting core if not yet booted
	 *
	 * @return bool False if Elgg wants the PHP CLI server to handle the request
	 * @throws InstallationException
	 * @throws InvalidParameterException
	 * @throws SecurityException
	 */
	public function run() {
		try {
			$config = $this->_services->config;
			$request = $this->_services->request;

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
				$this->_services->cacheHandler->handleRequest($request, $this)->prepare($request)->send();

				return true;
			}

			if (0 === strpos($request->getElggPath(), '/serve-file/')) {
				$this->_services->serveFileHandler->getResponse($request)->send();

				return true;
			}

			$this->bootCore();

			// re-fetch new request from services in case it was replaced by route:rewrite
			$request = $this->_services->request;

			if (!$this->_services->router->route($request)) {
				throw new PageNotFoundException();
			}
		} catch (HttpException $ex) {
			$forward_url = null;
			if ($ex instanceof GatekeeperException) {
				$forward_url = elgg_is_logged_in() ? null : elgg_get_login_url();
			}

			$hook_params = [
				'exception' => $ex,
			];
			
			$forward_url = $this->_services->hooks->trigger('forward', $ex->getCode(), $hook_params, $forward_url);

			if (isset($forward_url)) {
				if ($ex->getMessage()) {
					register_error($ex->getMessage());
				}
				$response = new RedirectResponse($forward_url);
			} else {
				$response = new ErrorResponse($ex->getMessage(), $ex->getCode(), $forward_url);
			}

			$this->_services->responseFactory->respond($response);
		}

		return true;
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
	 * @return bool
	 * @throws InstallationException
	 */
	public static function install() {
		ini_set('display_errors', 1);

		$installer = new \ElggInstaller();
		$response = $installer->run();
		try {
			// we won't trust server configuration but specify utf-8
			elgg_set_http_header('Content-type: text/html; charset=utf-8');

			// turn off browser caching
			elgg_set_http_header('Pragma: public', true);
			elgg_set_http_header("Cache-Control: no-cache, must-revalidate", true);
			elgg_set_http_header('Expires: Fri, 05 Feb 1982 00:00:00 -0500', true);

			_elgg_services()->responseFactory->respond($response);
			return headers_sent();
		} catch (InvalidParameterException $ex) {
			throw new InstallationException($ex->getMessage());
		}
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
	 * @throws InstallationException
	 */
	public static function upgrade() {
		// we want to know if an error occurs
		ini_set('display_errors', 1);
		
		set_time_limit(0);
		
		$is_cli = (php_sapi_name() === 'cli');

		$forward = function ($url) use ($is_cli) {
			if ($is_cli) {
				fwrite(STDOUT, "Open $url in your browser to continue." . PHP_EOL);
				return;
			}

			forward($url);
		};

		define('UPGRADING', 'upgrading');

		self::migrate();
		self::start();

		// clear autoload cache so plugin classes can be reregistered and used during upgrade
		_elgg_services()->autoloadManager->deleteCache();

		// check security settings
		if (!$is_cli && _elgg_config()->security_protect_upgrade && !elgg_is_admin_logged_in()) {
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

		if ($is_cli || (get_input('upgrade') == 'upgrade')) {
			$upgrader = _elgg_services()->upgrades;
			$result = $upgrader->run();

			if ($result['failure'] == true) {
				register_error($result['reason']);
				$forward($forward_url);
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

		$forward($forward_url);
	}

	/**
	 * Runs database migrations
	 *
	 * @throws InstallationException
	 * @return bool
	 */
	public static function migrate() {
		$conf = self::elggDir()->getPath('engine/conf/migrations.php');
		if (!$conf) {
			throw new InstallationException('Settings file is required to run database migrations.');
		}

		// setting timeout because some database migrations can take a long time
		set_time_limit(0);
		
		$app = new \Phinx\Console\PhinxApplication();
		$wrapper = new \Phinx\Wrapper\TextWrapper($app, [
			'configuration' => $conf,
		]);
		$log = $wrapper->getMigrate();

		if (!empty($_SERVER['argv']) && in_array('--verbose', $_SERVER['argv'])) {
			error_log($log);
		}

		return true;
	}

	/**
	 * Returns configuration array for database migrations
	 * @return array
	 */
	public static function getMigrationSettings() {

		$config = Config::factory();
		$db_config = DbConfig::fromElggConfig($config);

		if ($db_config->isDatabaseSplit()) {
			$conn = $db_config->getConnectionConfig(DbConfig::WRITE);
		} else {
			$conn = $db_config->getConnectionConfig();
		}

		return [
			"paths" => [
				"migrations" => Paths::elgg() . 'engine/schema/migrations/',
			],
			"environments" => [
				"default_migration_table" => "{$conn['prefix']}migrations",
				"default_database" => "prod",
				"prod" => [
					"adapter" => "mysql",
					"host" => $conn['host'],
					"name" => $conn['database'],
					"user" => $conn['user'],
					"pass" => $conn['password'],
					"charset" => $conn['encoding'],
					"table_prefix" => $conn['prefix'],
				],
			],
		];
	}

	/**
	 * Allow plugins to rewrite the path.
	 *
	 * @return void
	 */
	private function allowPathRewrite() {
		$request = $this->_services->request;
		$new = $this->_services->router->allowRewrite($request);
		if ($new === $request) {
			return;
		}

		$this->_services->setValue('request', $new);
		$this->_services->context->initialize($new);
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

		if (!self::isCoreLoaded()) {
			http_response_code(500);
			echo "Exception loading Elgg core. Check log at time $timestamp";
			return;
		}

		try {
			// allow custom scripts to trigger on exception
			// value in settings.php should be a system path to a file to include
			$exception_include = $this->_services->config->exception_include;

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
			if (!self::isCoreLoaded()) {
				return false;
			}

			if (!self::$_instance) {
				// can occur during tests
				return false;
			}

			return elgg_log($message, $level);
		};

		switch ($errno) {
			case E_USER_ERROR:
				if (!$log("PHP: $error", 'ERROR')) {
					error_log("PHP ERROR: $error");
				}
				if (self::isCoreLoaded()) {
					register_error("ERROR: $error");
				}

				// Since this is a fatal error, we want to stop any further execution but do so gracefully.
				throw new \Exception($error);

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
	 * Get all engine/lib library filenames
	 *
	 * @note We can't just pull in all directory files because some users leave old files in place.
	 *
	 * @return string[]
	 */
	private static function getEngineLibs() {
		return [
			'elgglib.php',
			'access.php',
			'actions.php',
			'admin.php',
			'annotations.php',
			'cache.php',
			'comments.php',
			'configuration.php',
			'constants.php',
			'cron.php',
			'database.php',
			'deprecated-2.3.php',
			'deprecated-3.0.php',
			'entities.php',
			'filestore.php',
			'group.php',
			'input.php',
			'languages.php',
			'mb_wrapper.php',
			'metadata.php',
			'navigation.php',
			'notification.php',
			'output.php',
			'pagehandler.php',
			'pageowner.php',
			'pam.php',
			'plugins.php',
			'relationships.php',
			'river.php',
			'search.php',
			'sessions.php',
			'statistics.php',
			'tags.php',
			'upgrade.php',
			'user_settings.php',
			'users.php',
			'views.php',
			'widgets.php',
		];
	}
}
