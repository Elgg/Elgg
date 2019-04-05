<?php

namespace Elgg;

use ClassException;
use Closure;
use ConfigurationException;
use DatabaseException;
use Elgg\Application\BootHandler;
use Elgg\Application\ErrorHandler;
use Elgg\Application\ExceptionHandler;
use Elgg\Application\ShutdownHandler;
use Elgg\Database\DbConfig;
use Elgg\Di\ServiceProvider;
use Elgg\Filesystem\Directory;
use Elgg\Filesystem\Directory\Local;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;
use Elgg\Http\RedirectResponse;
use Elgg\Http\Request;
use Elgg\Http\ResponseBuilder;
use Elgg\Http\ResponseTransport;
use Elgg\Project\Paths;
use Elgg\Security\UrlSigner;
use ElggInstaller;
use Exception;
use InstallationException;
use InvalidArgumentException;
use InvalidParameterException;
use SecurityException;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirect;
use Symfony\Component\HttpFoundation\Response;

/**
 * Load, boot, and implement a front controller for an Elgg application
 *
 * To run as PHP CLI server:
 * <code>php -S localhost:8888 /full/path/to/elgg/index.php</code>
 *
 * The full path is necessary to work around this: https://bugs.php.net/bug.php?id=55726
 *
 * @since 2.0.0
 */
class Application {

	use Loggable;

	const DEFAULT_LANG = 'en';
	const DEFAULT_LIMIT = 10;

	/**
	 * @var ServiceProvider
	 *
	 * @internal DO NOT USE
	 */
	public $_services;

	/**
	 * @var Closure[]
	 */
	private static $_setups = [];

	/**
	 * Reference to the loaded Application
	 *
	 * @internal Do not use this
	 * @access private
	 * @var Application
	 */
	public static $_instance;

	/**
	 * Get the global Application instance. If not set, it's auto-created and wired to $CONFIG.
	 *
	 * @return Application|null
	 * @throws ConfigurationException
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
	 *
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
	 * @return array
	 * @access private
	 * @internal
	 * @throws InstallationException
	 */
	public static function loadCore() {
		$setups = [];

		$path = Paths::elgg() . 'engine/lib';

		// include library files, capturing setup functions
		foreach (self::getEngineLibs() as $file) {
			try {
				$setups[] = self::requireSetupFileOnce("$path/$file");
			} catch (\Error $e) {
				throw new \InstallationException("Elgg lib file failed include: $path/$file");
			}
		}

		return $setups;
	}

	/**
	 * Require a library/plugin file once and capture returned anonymous functions
	 *
	 * @param string $file File to require
	 *
	 * @return mixed
	 * @internal
	 * @access private
	 */
	public static function requireSetupFileOnce($file) {
		if (isset(self::$_setups[$file])) {
			return self::$_setups[$file];
		}

		$return = Includer::requireFileOnce($file);
		self::$_setups[$file] = $return;

		return $return;
	}

	/**
	 * Start and boot the core
	 *
	 * @return self
	 * @throws ClassException
	 * @throws ConfigurationException
	 * @throws DatabaseException
	 * @throws InstallationException
	 * @throws InvalidParameterException
	 * @throws SecurityException
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
	 *    - {@elgg_event plugins_load system}
	 *    - {@elgg_event plugins_boot system}
	 *    - {@elgg_event init system}
	 *    - {@elgg_event ready system}
	 *
	 * Please note that the Elgg session is started after all plugins are loader, there will therefore
	 * be no information about a logged user available until plugins_load,system event is complete.
	 *
	 * If Elgg is not fully installed, the browser will be redirected to an installation page.
	 *
	 * @return void
	 *
	 * @throws InstallationException
	 * @throws InvalidParameterException
	 * @throws SecurityException
	 * @throws ClassException
	 * @throws DatabaseException
	 *
	 * @access private
	 * @internal
	 */
	public function bootCore() {
		$boot = new BootHandler($this);
		$boot();
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
	 * Make the global $CONFIG a reference to this application's config service
	 *
	 * @param Application $application The Application
	 *
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
	 *
	 * @return self
	 * @throws ConfigurationException
	 * @throws InvalidArgumentException
	 */
	public static function factory(array $spec = []) {

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

		if ($spec['handle_exceptions']) {
			set_error_handler(new ErrorHandler());
			set_exception_handler(new ExceptionHandler());
		}

		self::loadCore();

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

		if ($spec['handle_shutdown']) {
			register_shutdown_function(new ShutdownHandler($app));
		}

		return $app;
	}

	/**
	 * Route a request
	 *
	 * @param Request $request Request
	 *
	 * @return Response|false
	 * @throws ClassException
	 * @throws DatabaseException
	 * @throws InstallationException
	 * @throws InvalidParameterException
	 * @throws SecurityException
	 */
	public static function route(Request $request) {
		self::loadCore();

		if ($request->isRewriteCheck()) {
			$response = new OkResponse(Request::REWRITE_TEST_OUTPUT);
			return self::respond($response);
		}

		if (self::$_instance) {
			$app = self::$_instance;
			$app->_services->setValue('request', $request);
		} else {
			try {
				$app = self::factory([
					'request' => $request,
				]);

				self::setGlobalConfig($app);
				self::setInstance($app);
			} catch (ConfigurationException $ex) {
				return self::install();
			}
		}

		return $app->run();
	}

	/**
	 * Build and send a response
	 *
	 * If application is booted, we will use the response factory service,
	 * otherwise we will prepare a non-cacheable response
	 *
	 * @param ResponseBuilder $builder Response builder
	 *
	 * @return Response|false Sent response
	 * @throws InvalidParameterException
	 */
	public static function respond(ResponseBuilder $builder) {
		if (self::$_instance) {
			self::$_instance->_services->responseFactory->respond($builder);

			return self::$_instance->_services->responseFactory->getSentResponse();
		}

		try {
			$content = $builder->getContent();
			$status = $builder->getStatusCode();
			$headers = $builder->getHeaders();

			if ($builder->isRedirection()) {
				$forward_url = $builder->getForwardURL();
				$response = new SymfonyRedirect($forward_url, $status, $headers);
			} else {
				$response = new Response($content, $status, $headers);
			}
		} catch (Exception $ex) {
			$response = new Response($ex->getMessage(), 500);
		}

		$response->headers->set('Pragma', 'public');
		$response->headers->set('Cache-Control', 'no-cache, must-revalidate');
		$response->headers->set('Expires', 'Fri, 05 Feb 1982 00:00:00 -0500');

		self::getResponseTransport()->send($response);

		return $response;
	}

	/**
	 * Elgg's front controller. Handles basically all incoming URL requests.
	 *
	 * @return Response|false True if Elgg will handle the request, false if the server should (PHP-CLI server)
	 * @throws ClassException
	 * @throws DatabaseException
	 * @throws InstallationException
	 * @throws InvalidParameterException
	 * @throws SecurityException
	 */
	public static function index() {
		return self::route(self::getRequest());
	}

	/**
	 * Routes the request, booting core if not yet booted
	 *
	 * @return Response|false False if Elgg wants the PHP CLI server to handle the request
	 * @throws ClassException
	 * @throws DatabaseException
	 * @throws InstallationException
	 * @throws InvalidParameterException
	 * @throws SecurityException
	 */
	public function run() {
		$config = $this->_services->config;
		$request = $this->_services->request;

		try {
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
				$config->_disable_session_save = true;
				$response = $this->_services->cacheHandler->handleRequest($request, $this)->prepare($request);
				self::getResponseTransport()->send($response);

				return $response;
			}

			if (0 === strpos($request->getElggPath(), '/serve-file/')) {
				$response = $this->_services->serveFileHandler->getResponse($request);
				self::getResponseTransport()->send($response);

				return $response;
			}

			$this->bootCore();

			// re-fetch new request from services in case it was replaced by route:rewrite
			$request = $this->_services->request;

			if (!$this->_services->router->route($request)) {
				throw new PageNotFoundException();
			}
		} catch (HttpException $ex) {
			$forward_url = $ex->getRedirectUrl();
			if (!$forward_url) {
				if ($ex instanceof GatekeeperException) {
					$forward_url = elgg_is_logged_in() ? null : elgg_get_login_url();
				} else if ($request->getFirstUrlSegment() == 'action') {
					$forward_url = REFERRER;
				}
			}

			$hook_params = [
				'exception' => $ex,
			];

			$forward_url = $this->_services->hooks->trigger('forward', $ex->getCode(), $hook_params, $forward_url);

			if ($forward_url) {
				if ($ex->getMessage()) {
					$this->_services->systemMessages->addErrorMessage($ex->getMessage());
				}
				$response = new RedirectResponse($forward_url);
			} else {
				$response = new ErrorResponse($ex->getMessage(), $ex->getCode(), $forward_url);
			}

			self::respond($response);
		}

		return $this->_services->responseFactory->getSentResponse();
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
	 * @return Response|false
	 * @throws InvalidParameterException
	 */
	public static function install() {
		ini_set('display_errors', 1);

		try {
			$installer = new ElggInstaller();
			$response = $installer->run();
		} catch (Exception $ex) {
			$response = new ErrorResponse($ex->getMessage(), 500);
		}

		return self::respond($response);
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
	 * @return Response|false
	 * @throws ClassException
	 * @throws ConfigurationException
	 * @throws DatabaseException
	 * @throws InstallationException
	 * @throws InvalidParameterException
	 * @throws SecurityException
	 */
	public static function upgrade() {

		try {
			self::migrate();
			self::start();

			$request = self::$_instance->_services->request;
			$signer = self::$_instance->_services->urlSigner;

			$url = $request->getCurrentURL();
			$query = $request->getParams();

			// We need to resign the URL because the path is different
			$mac = elgg_extract(UrlSigner::KEY_MAC, $query);
			if (isset($mac) && !$signer->isValid($url)) {
				throw new \Elgg\HttpException(elgg_echo('invalid_request_signature'), ELGG_HTTP_FORBIDDEN);
			}

			unset($query[UrlSigner::KEY_MAC]);

			$base_url = elgg_normalize_site_url('upgrade/init');
			$url = elgg_http_add_url_query_elements($base_url, $query);

			if (isset($mac)) {
				$url = self::$_instance->_services->urlSigner->sign($url);
			}

			$response = new RedirectResponse($url, ELGG_HTTP_PERMANENTLY_REDIRECT);
		} catch (Exception $ex) {
			$response = new ErrorResponse($ex->getMessage(), $ex->getCode() ? : ELGG_HTTP_INTERNAL_SERVER_ERROR);
		}

		return self::respond($response);
	}

	/**
	 * Runs database migrations
	 *
	 * @throws InstallationException
	 * @return bool
	 */
	public static function migrate() {
		
		$constants = self::elggDir()->getPath('engine/lib/constants.php');
		self::requireSetupFileOnce($constants);
		
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
	 * @access private
	 * @internal
	 */
	public function allowPathRewrite() {
		$request = $this->_services->request;
		$new = $this->_services->router->allowRewrite($request);
		if ($new === $request) {
			return;
		}

		$this->_services->setValue('request', $new);
	}

	/**
	 * Is application running in CLI
	 * @return bool
	 */
	public static function isCli() {
		switch (php_sapi_name()) {
			case 'cli' :
			case 'phpdbg' :
				return true;

			default:
				return false;
		}
	}

	/**
	 * Build request object
	 * @return Request
	 */
	public static function getRequest() {
		if (self::$_instance) {
			return self::$_instance->_services->request;
		}

		return Request::createFromGlobals();
	}

	/**
	 * Load console input interface
	 * @return InputInterface
	 */
	public static function getStdIn() {
		if (self::isCli()) {
			$request = self::getRequest();
			$argv = $request->server->get('argv') ? : [];
			return new ArgvInput($argv);
		}

		return new ArrayInput([]);
	}

	/**
	 * Load console output interface
	 * @return OutputInterface
	 */
	public static function getStdOut() {
		if (self::isCli()) {
			return new ConsoleOutput();
		} else {
			return new NullOutput();
		}
	}

	/**
	 * Load console error output interface
	 * @return OutputInterface
	 */
	public static function getStdErr() {
		$std_out = self::getStdOut();
		if (is_callable([$std_out, 'getErrorOutput'])) {
			return $std_out->getErrorOutput();
		}

		return $std_out;
	}

	/**
	 * Build a transport for sending responses
	 * @return ResponseTransport
	 */
	public static function getResponseTransport() {
		if (self::isCli()) {
			return new \Elgg\Http\OutputBufferTransport();
		}

		return new \Elgg\Http\HttpProtocolTransport();
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
