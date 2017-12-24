<?php

namespace Elgg\Di;

use Elgg\Application;
use Elgg\Application\BootHandler;
use Elgg\Application\Bootstrap;
use Elgg\Application\CacheHandler;
use Elgg\Application\ExceptionHandler;
use Elgg\Application\InstallationHandler;
use Elgg\Application\MigrationHandler;
use Elgg\Application\ServeFileHandler;
use Elgg\Application\ShutdownHandler;
use Elgg\Application\UpgradeHandler;
use Elgg\Cache\CompositeCache;
use Elgg\CliKernel;
use Elgg\Config;
use Elgg\Http\Request;
use Elgg\HttpKernel;
use Elgg\Kernel;
use WideImage\Exception\Exception;

/**
 * Application container
 *
 * @property-read Application         $application
 * @property-read BootHandler         $boot
 * @property-read CompositeCache      $bootCache
 * @property-read CacheHandler        $cacheHandler
 * @property-read Config              $config
 * @property-read ExceptionHandler    $exceptionHandler
 * @property-read InstallationHandler $installationHandler
 * @property-read Kernel              $kernel
 * @property-read MigrationHandler    $migrationHandler
 * @property-read ServeFileHandler    $serveFileHandler
 * @property-read ServiceProvider     $services
 * @property-read string              $settingsPath
 * @property-read ShutdownHandler     $shutdownHandler
 * @property-read UpgradeHandler      $upgradeHandler
 */
class ApplicationContainer extends DiContainer {

	/**
	 * @var ApplicationContainer
	 */
	public static $_instance;

	/**
	 * Get the global ApplicationContainer instance
	 *
	 * @return ApplicationContainer|null
	 */
	public static function getInstance() {
		if (self::$_instance === null) {
			self::$_instance = self::factory();
		}

		return self::$_instance;
	}

	/**
	 * Set the global ApplicationContainer instance
	 *
	 * @param Application $application Global application contianer
	 *
	 * @return void
	 */
	public static function setInstance(ApplicationContainer $application = null) {
		self::$_instance = $application;
	}

	/**
	 * Contructor
	 *
	 * @param string $settings_path Path to settings.php
	 */
	public function __construct($settings_path = null) {

		$this->setFactory('application', function (ApplicationContainer $c) {
			return new Application($c->services);
		});

		$this->setFactory('boot', function (ApplicationContainer $c) {
			$boot = new \Elgg\Application\BootHandler($c->application, $c->bootCache);
			if ($c->config->enable_profiling) {
				$boot->setTimer($c->application->_services->timer);
			}

			return $boot;
		});

		$this->setFactory('bootCache', function (ApplicationContainer $c) {
			$flags = ELGG_CACHE_PERSISTENT | ELGG_CACHE_FILESYSTEM | ELGG_CACHE_RUNTIME;

			return new CompositeCache("elgg_boot", $c->config, $flags);
		});

		$this->setFactory('cacheHandler', function (ApplicationContainer $c) {
			return new CacheHandler($c->application);
		});

		$this->setFactory('config', function (ApplicationContainer $c) {
			try {
				$config = Config::factory($c->settingsPath);
				$this->setValue('settingsPath', $config->getPath());
			} catch (\ConfigurationException $ex) {
				$config = new Config();
			}
			return $config;
		});

		$this->setFactory('exceptionHandler', function (ApplicationContainer $c) {
			return new ExceptionHandler($c->application, $c->kernel);
		});

		$this->setFactory('installationHandler', function (ApplicationContainer $c) {
			return new InstallationHandler();
		});

		$this->setFactory('kernel', function (ApplicationContainer $c) {
			switch (php_sapi_name()) {
				case 'cli' :
				case 'cli-server' :
				case 'phpdbg' :
					return new CliKernel($c->application, $c->cacheHandler, $c->serveFileHandler);
			}

			return new HttpKernel($c->application, $c->cacheHandler, $c->serveFileHandler);
		});

		$this->setFactory('migrationHandler', function (ApplicationContainer $c) {
			return new MigrationHandler($c->application, $c->kernel);
		});

		$this->setFactory('serveFileHandler', function (ApplicationContainer $c) {
			return new \Elgg\Application\ServeFileHandler($c->application);
		});

		$this->setFactory('services', function (ApplicationContainer $c) {
			return new ServiceProvider($c->config);
		});

		$this->setValue('settingsPath', $settings_path);

		$this->setFactory('shutdownHandler', function (ApplicationContainer $c) {
			return new ShutdownHandler($c->application, $c->kernel);
		});

		$this->setFactory('upgradeHandler', function (ApplicationContainer $c) {
			return new UpgradeHandler($c->application, $c->kernel);
		});


		self::setInstance($this);
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
	 *
	 * @return self
	 */
	public static function factory(array $spec = []) {
		Bootstrap::loadCore();

		$defaults = [
			'config' => null,
			'handle_exceptions' => true,
			'handle_shutdown' => true,
			'request' => null,
			'service_provider' => null,
			'set_start_time' => true,
			'settings_path' => null,
			'kernel' => null,
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

		$container = new ApplicationContainer($spec['settings_path']);

		if ($spec['kernel'] instanceof \Closure) {
			$container->setFactory('kernel', $spec['kernel']);
		}

		if ($spec['config']) {
			$container->setValue('config', $spec['config']);
		}

		if ($spec['service_provider']) {
			$container->setValue('services', $spec['service_provider']);
		}

		if ($spec['request']) {
			if ($spec['request'] instanceof Request) {
				$container->services->setValue('request', $spec['request']);
			}
		}

		if ($spec['handle_exceptions']) {
			$container->kernel->setErrorHandler([$container->exceptionHandler, 'handleErrors']);
			$container->kernel->setExceptionHandler([$container->exceptionHandler, 'handleExceptions']);
		}

		if ($spec['handle_shutdown']) {
			$container->kernel->registerShutdownFunction([$container->shutdownHandler, 'handleShutdown']);
		}

		global $CONFIG;
		$CONFIG = $container->config;

		return $container;
	}
}
