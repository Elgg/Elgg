<?php

namespace Elgg;

use Elgg\Di\ServiceProvider;
use Elgg\Environments\BasicEnvironment;

/**
 * Load, boot, and implement a front controller for an Elgg application
 *
 * @property-read \Elgg\Services\Config      $config
 * @property-read \Elgg\Services\Environment $env
 *
 * @since 2.0.0
 */
class Application {

	/**
	 * @var ServiceProvider
	 */
	private $services;

	/**
	 * @var string
	 */
	private $engine_dir;

	/**
	 * @var string
	 */
	private $install_dir;

	/**
	 * Property names of the service provider to be exposed via __get()
	 *
	 * E.g. the presence of `'foo' => true` in the list would allow _elgg_services()->foo to
	 * be accessed via elgg()->foo.
	 *
	 * @var string[]
	 */
	private static $public_services = [
		'config' => true,
		'env' => true,
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
	public function __construct(ServiceProvider $services = null) {
		if (!$services) {
			$services = new ServiceProvider(new Config());
		}
		$this->services = $services;

		/**
		 * The time with microseconds when the Elgg engine was started.
		 *
		 * @global float
		 */
		global $START_MICROTIME;
		if (!isset($START_MICROTIME)) {
			$START_MICROTIME = microtime(true);
		}

		$this->engine_dir = dirname(dirname(__DIR__));
		$this->install_dir = dirname($this->engine_dir);
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

		$lib_dir = $this->engine_dir . "/lib";

		// we only depend on it to be defining _elgg_services function
		require_once "$lib_dir/autoloader.php";

		// set up autoloading and DIC
		_elgg_services($this->services);

		// load the rest of the library files from engine/lib/
		// All on separate lines to make diffs easy to read + make it apparent how much
		// we're actually loading on every page (Hint: it's too much).
		$lib_files = array(
			// Needs to be loaded first to correctly bootstrap
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
			'deprecated-1.7.php',
			'deprecated-1.8.php',
			'deprecated-1.9.php',
			'deprecated-1.10.php',
		);

		// isolate global scope
		call_user_func(function () use ($lib_dir, $lib_files) {

			$setups = array();

			// include library files, capturing setup functions
			foreach ($lib_files as $file) {
				$setup = (require_once "$lib_dir/$file");

				if ($setup instanceof \Closure) {
					$setups[$file] = $setup;
				}
			}

			// store instance to be returned by elgg()
			self::$_instance = $this;

			$events = $this->services->events;
			$hooks = $this->services->hooks;

			// run setups
			foreach ($setups as $func) {
				$func($events, $hooks);
			}
		});
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
	 * @see install.php
	 * @return void
	 */
	public function bootCore() {
		$config = $this->services->config;

		if ($config->getVolatile('boot_complete')) {
			return;
		}

		$this->loadSettings();

		$config->set('boot_complete', false);

		// This will be overridden by the DB value but may be needed before the upgrade script can be run.
		$config->set('default_limit', 10);

		// in case not loaded already
		$this->loadCore();

		// Make sure devs can't read $CONFIG->elgg_env
		$this->services->env;
		$config->set(BasicEnvironment::CONFIG_KEY_FACTORY, null);

		$events = $this->services->events;

		// Connect to database, load language files, load configuration, init session
		// Plugins can't use this event because they haven't been loaded yet.
		$events->trigger('boot', 'system');

		// Load the plugins that are active
		$this->services->plugins->load();

		// @todo move loading plugins into a single boot function that replaces 'boot', 'system' event
		// and then move this code in there.
		// This validates the view type - first opportunity to do it is after plugins load.
		$viewtype = elgg_get_viewtype();
		if (!elgg_is_registered_viewtype($viewtype)) {
			elgg_set_viewtype('default');
		}

		// @todo deprecate as plugins can use 'init', 'system' event
		$events->trigger('plugins_boot', 'system');

		// Complete the boot process for both engine and plugins
		$events->trigger('init', 'system');

		$config->set('boot_complete', true);

		// System loaded and ready
		$events->trigger('ready', 'system');
	}

	/**
	 * Get the Database instance for performing queries without booting Elgg
	 *
	 * If settings.php has not been loaded, it will be loaded to configure the DB connection.
	 *
	 * Note: Before boot, the Database instance will not yet be bound to a Logger.
	 *
	 * @return Database
	 */
	public function getDb() {
		$this->loadSettings();
		return $this->services->db;
	}

	/**
	 * Rewrite rules for PHP cli webserver used for testing. Do not use on production sites
	 * as normal web server replacement.
	 *
	 * You need to explicitly point to index.php in order for router to work properly:
	 *
	 * <code>php -S localhost:8888 index.php</code>
	 *
	 * @return bool True if Elgg's router will handle the file, false if PHP should serve it directly
	 */
	protected function runPhpWebServer() {
		$urlPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

		if (preg_match('/^\/cache\/(.*)$/', $urlPath, $matches)) {
			$_GET['request'] = $matches[1];
			$handler = new CacheHandler($this);
			$handler->handleRequest($_GET, $_SERVER);
			exit;
		}

		if (preg_match('/^\/export\/([A-Za-z]+)\/([0-9]+)\/?$/', $urlPath, $matches)) {
			$_GET['view'] = $matches[1];
			$_GET['guid'] = $matches[2];
			require "{$this->engine_dir}/handlers/export_handler.php";
			exit;
		}

		if (preg_match('/^\/export\/([A-Za-z]+)\/([0-9]+)\/([A-Za-z]+)\/([A-Za-z0-9\_]+)\/$/', $urlPath, $matches)) {
			$_GET['view'] = $matches[1];
			$_GET['guid'] = $matches[2];
			$_GET['type'] = $matches[3];
			$_GET['idname'] = $matches[4];
			require "{$this->engine_dir}/handlers/export_handler.php";
			exit;
		}

		if (preg_match("/^\/rewrite.php$/", $urlPath, $matches)) {
			require "{$this->install_dir}/install.php";
			exit;
		}

		if ($urlPath !== '/' && file_exists($this->install_dir . $urlPath)) {
			// serve the requested resource as-is.
			return false;
		}

		$_GET['__elgg_uri'] = $urlPath;
		return true;
	}

	/**
	 * Routes the request, booting core if not yet booted
	 *
	 * @return bool False if Elgg wants the PHP CLI server to handle the request
	 */
	public function run() {
		if (php_sapi_name() === 'cli-server') {
			if (!$this->runPhpWebServer()) {
				// PHP will serve this file directly
				return false;
			}
		}

		// allow testing from the upgrade page before the site is upgraded.
		if (isset($_GET['__testing_rewrite'])) {
			if (isset($_GET['__elgg_uri']) && false !== strpos($_GET['__elgg_uri'], '__testing_rewrite')) {
				echo "success";
			}
			return true;
		}

		$this->bootCore();

		if (!$this->services->router->route($this->services->request)) {
			forward('', '404');
		}

		return true;
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
}
