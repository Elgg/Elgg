<?php

namespace Elgg;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @since 2.0.0
 */
class Application {

	/**
	 * @var \Elgg\Config
	 */
	private $config;

	/**
	 * @var string
	 */
	private $engine_dir;

	/**
	 * @var string
	 */
	private $install_dir;

	/**
	 * @var string
	 */
	private $config_file;

	/**
	 * @var bool
	 */
	private $core_loaded = false;

	/**
	 * @var bool
	 */
	private $core_booted = false;

	/**
	 * Constructor
	 *
	 * @param Config|string $config Config object or settings file location or
	 */
	public function __construct($config = null) {
		/**
		 * The time with microseconds when the Elgg engine was started.
		 *
		 * @global float
		 */
		global $START_MICROTIME;
		$START_MICROTIME = microtime(true);

		$this->engine_dir = dirname(dirname(__DIR__));
		$this->install_dir = dirname($this->engine_dir);

		if ($config) {
			if (is_string($config)) {
				$this->config_file = $config;
			} elseif ($config instanceof Config) {
				$this->config = $config;
			} else {
				throw new \InvalidArgumentException('$config must be an Elgg\Config or a settings file location');
			}
		} else {
			$this->config_file = "{$this->engine_dir}/settings.php";
		}
	}

	/**
	 * @return Config
	 */
	public function getConfig() {
		if (!$this->config) {
			$this->loadConfig();
		}
		return $this->config;
	}

	/**
	 * Load all lib files without triggering boot events
	 *
	 * @return void
	 */
	protected function loadConfig() {
		/**
		 * Configuration values.
		 *
		 * The $CONFIG global contains configuration values required
		 * for running Elgg as defined in the settings.php file.
		 *
		 * Plugin authors are encouraged to use elgg_get_config() instead of accessing
		 * the global directly.
		 *
		 * @see elgg_get_config()
		 * @see engine/settings.php
		 * @global \stdClass $CONFIG
		 */
		global $CONFIG;
		if (!isset($CONFIG)) {
			$CONFIG = new \stdClass;
		}
		$CONFIG->boot_complete = false;

		// No settings means a fresh install
		if (!is_file($this->config_file)) {
			header("Location: install.php");
			exit;
		}

		if (!is_readable($this->config_file)) {
			echo "The Elgg settings file exists but the web server doesn't have read permission to it.";
			exit;
		}

		require_once $this->config_file;

		if (isset($CONFIG->dataroot)) {
			$CONFIG->dataroot = rtrim($CONFIG->dataroot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		}

		$this->config = new Config($CONFIG);
	}

	/**
	 * Load all Elgg code without formal boot process, for internal testing purposes
	 *
	 * @access private
	 * @return void
	 */
	public function loadCore() {
		if ($this->core_loaded) {
			return;
		}

		$lib_dir = $this->engine_dir . "/lib";

		// we only depend on it to be defining _elgg_services function
		require_once "$lib_dir/autoloader.php";

		// set up autoloading and DIC
		$services = _elgg_services($this);

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
		call_user_func(function () use ($lib_dir, $lib_files, $services) {

			$setups = array();

			// include library files, capturing setup functions
			foreach ($lib_files as $file) {
				$setup = (require_once "$lib_dir/$file");

				if ($setup instanceof \Closure) {
					$setups[$file] = $setup;
				}
			}

			$events = $services->events;
			$hooks = $services->hooks;

			// run setups
			foreach ($setups as $func) {
				$func($events, $hooks);
			}
		});

		$this->core_loaded = true;
	}

	/**
	 * Bootstraps the Elgg engine.
	 *
	 * This method loads the full Elgg engine, checks the installation
	 * state, and triggers a series of events to finish booting Elgg:
	 * 	- {@elgg_event boot system}
	 * 	- {@elgg_event init system}
	 * 	- {@elgg_event ready system}
	 *
	 * If Elgg is fully uninstalled, the browser will be redirected to an
	 * installation page.
	 *
	 * @see install.php
	 * @return void
	 */
	function bootCore() {
		if ($this->core_booted) {
			return;
		}

		$config = $this->getConfig();

		// This will be overridden by the DB value but may be needed before the upgrade script can be run.
		$config->set('default_limit', 10);

		$this->loadCore();

		// Connect to database, load language files, load configuration, init session
		// Plugins can't use this event because they haven't been loaded yet.
		elgg_trigger_event('boot', 'system');

		// Load the plugins that are active
		_elgg_load_plugins();

		// @todo move loading plugins into a single boot function that replaces 'boot', 'system' event
		// and then move this code in there.
		// This validates the view type - first opportunity to do it is after plugins load.
		$viewtype = elgg_get_viewtype();
		if (!elgg_is_registered_viewtype($viewtype)) {
			elgg_set_viewtype('default');
		}

		// @todo deprecate as plugins can use 'init', 'system' event
		elgg_trigger_event('plugins_boot', 'system');

		// Complete the boot process for both engine and plugins
		elgg_trigger_event('init', 'system');

		$config->set('boot_complete', true);

		// System loaded and ready
		elgg_trigger_event('ready', 'system');

		$this->core_booted = true;
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
	 * Bootstraps core, plugins and handles the routing.
	 *
	 * @return bool False if Elgg wants the PHP CLI server to handle the request
	 */
	function run() {
		if (php_sapi_name() === 'cli-server') {
			if (!$this->runPhpWebServer()) {
				// PHP will serve this file directly
				return false;
			}
		}

		$this->bootCore();

		$router = _elgg_services()->router;
		$request = _elgg_services()->request;

		if (!$router->route($request)) {
			forward('', '404');
		}
		return true;
	}
}