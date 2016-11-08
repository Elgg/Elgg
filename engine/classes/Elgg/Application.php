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

	const GET_PATH_KEY = '__elgg_uri';
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

		/**
		 * This was introduced in 2.0 in order to remove all internal non-API state from $CONFIG. This will
		 * be a breaking change, but frees us to refactor in 2.x without fear of plugins depending on
		 * $CONFIG.
		 *
		 * @access private
		 */
		if (!isset($GLOBALS['_ELGG'])) {
			$GLOBALS['_ELGG'] = new \stdClass();
		}

		$this->engine_dir = __DIR__ . '/../..';
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
		if (function_exists('elgg')) {
			return;
		}

		$lib_dir = self::elggDir()->chroot("engine/lib");

		// load the rest of the library files from engine/lib/
		// All on separate lines to make diffs easy to read + make it apparent how much
		// we're actually loading on every page (Hint: it's too much).
		$lib_files = array(
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
			'friends.php',
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
			'deprecated-1.9.php',
			'deprecated-1.10.php',
			'deprecated-1.11.php',
			'deprecated-1.12.php',
			'deprecated-2.1.php',
		);

		// isolate global scope
		call_user_func(function () use ($lib_dir, $lib_files) {

			$setups = array();

			// include library files, capturing setup functions
			foreach ($lib_files as $file) {
				$setup = (require_once $lib_dir->getPath($file));

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
	}

	/**
	 * Replacement for loading engine/start.php
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

		if ($config->getVolatile('boot_complete')) {
			return;
		}

		$this->loadSettings();

		$config->set('boot_complete', false);

		// This will be overridden by the DB value but may be needed before the upgrade script can be run.
		$config->set('default_limit', 10);

		// in case not loaded already
		$this->loadCore();

		$events = $this->services->events;

		// Connect to database, load language files, load configuration, init session
		// Plugins can't use this event because they haven't been loaded yet.
		$events->trigger('boot', 'system');

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

			// This is root directory start.php, not elgg/engine/start.php
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
	 * @note Do not type hint on \Elgg\Database, as this will fail in 3.0. If you must type hint,
	 *       expect an \Elgg\Application\Database.
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
		$path = $this->setupPath();

		// allow testing from the upgrade page before the site is upgraded.
		if (isset($_GET[self::REWRITE_TEST_TOKEN])) {
			if (false !== strpos($path, self::REWRITE_TEST_TOKEN)) {
				echo self::REWRITE_TEST_OUTPUT;
			}
			return true;
		}

		if (php_sapi_name() === 'cli-server') {
			$www_root = "http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}/";
			$this->services->config->set('wwwroot', $www_root);
		}

		if (0 === strpos($path, '/cache/')) {
			(new Application\CacheHandler($this, $this->services->config, $_SERVER))->handleRequest($path);
			return true;
		}

		if (0 === strpos($path, '/serve-file/')) {
			$this->services->serveFileHandler->getResponse($this->services->request)->send();
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

		if (!$this->services->router->route($this->services->request)) {
			forward('', '404');
		}
	}

	/**
	 * Determine the Elgg data directory with trailing slash, save it to config, and return it
	 *
	 * @todo Consider a better place for this logic? We need it before boot
	 *
	 * @return string
	 * @throws \InstallationException
	 */
	public static function getDataPath() {
		$app = self::create();
		$app->services->config->loadSettingsFile();

		if ($GLOBALS['_ELGG']->dataroot_in_settings) {
			return $app->services->config->getVolatile('dataroot');
		}

		$dataroot = $app->services->datalist->get('dataroot');
		if (!$dataroot) {
			throw new \InstallationException('The datalists table lacks a value for "dataroot".');
		}
		$dataroot = rtrim($dataroot, '/\\') . DIRECTORY_SEPARATOR;
		$app->services->config->set('dataroot', $dataroot);
		return $dataroot;
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

		$site_url = elgg_get_config('url');
		$site_host = parse_url($site_url, PHP_URL_HOST) . '/';

		// turn any full in-site URLs into absolute paths
		$forward_url = get_input('forward', '/admin', false);
		$forward_url = str_replace(array($site_url, $site_host), '/', $forward_url);

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
					$msg = "You must update your .htaccess file so that the path is injected "
						. "into the GET parameter __elgg_uri (you can use install/config/htaccess.dist as a guide).";
				}
				echo $msg;
				exit;
			}

			$vars = array(
				'forward' => $forward_url
			);

			// reset cache to have latest translations available during upgrade
			elgg_reset_system_cache();

			echo elgg_view_page(elgg_echo('upgrading'), '', 'upgrade', $vars);
			exit;
		}

		forward($forward_url);
	}

	/**
	 * Get the request URI and store it in $_GET['__elgg_uri']
	 *
	 * @return string e.g. "cache/123..."
	 */
	private function setupPath() {
		if (!isset($_GET[self::GET_PATH_KEY]) || is_array($_GET[self::GET_PATH_KEY])) {
			if (php_sapi_name() === 'cli-server') {
				$_GET[self::GET_PATH_KEY] = (string)parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
			} else {
				$_GET[self::GET_PATH_KEY] = '/';
			}
		}

		// normalize
		$_GET[self::GET_PATH_KEY] = '/' . trim($_GET[self::GET_PATH_KEY], '/');

		return $_GET[self::GET_PATH_KEY];
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
		_elgg_set_initial_context($new);
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
}
