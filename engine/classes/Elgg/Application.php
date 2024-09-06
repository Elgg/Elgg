<?php

namespace Elgg;

use Elgg\Application\BootHandler;
use Elgg\Application\ErrorHandler;
use Elgg\Application\ExceptionHandler;
use Elgg\Application\ShutdownHandler;
use Elgg\Database\DbConfig;
use Elgg\Di\InternalContainer;
use Elgg\Di\PublicContainer;
use Elgg\Exceptions\ConfigurationException;
use Elgg\Exceptions\Configuration\InstallationException;
use Elgg\Exceptions\HttpException;
use Elgg\Exceptions\Http\GatekeeperException;
use Elgg\Exceptions\Http\PageNotFoundException;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;
use Elgg\Http\RedirectResponse;
use Elgg\Http\Request as HttpRequest;
use Elgg\Http\ResponseBuilder;
use Elgg\Http\ResponseTransport;
use Elgg\Project\Paths;
use Elgg\Security\UrlSigner;
use Elgg\Traits\Loggable;
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
 * @internal
 * @since 2.0.0
 */
class Application {

	use Loggable;

	/**
	 * @var InternalContainer
	 *
	 * @internal DO NOT USE
	 */
	public $internal_services;

	/**
	 * @var PublicContainer
	 *
	 * @internal DO NOT USE
	 */
	public $public_services;

	/**
	 * Reference to the loaded Application
	 *
	 * @internal Do not use this
	 * @var Application
	 */
	public static $_instance;
	
	/**
	 * Stores status about the boot process
	 *
	 * @var array
	 */
	protected $boot_status = [
		'application_boot_completed' => false,
		'full_boot_completed' => false,
		'plugins_boot_completed' => false,
		'service_boot_completed' => false,
	];

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
	 * @param InternalContainer $internal_services Elgg internal services
	 */
	public function __construct(InternalContainer $internal_services) {
		$this->internal_services = $internal_services;
		$this->public_services = PublicContainer::factory();
	}

	/**
	 * Define all Elgg global functions and constants, wire up boot events, but don't boot
	 *
	 * This includes all the .php files in engine/lib (not upgrades). If a script returns a function,
	 * it is queued and executed at the end.
	 *
	 * @return void
	 *
	 * @internal
	 * @throws InstallationException
	 */
	public static function loadCore() {
		$path = Paths::elgg() . 'engine/lib';

		// include library files, capturing setup functions
		foreach (self::getEngineLibs() as $file) {
			try {
				Includer::requireFileOnce("$path/$file");
			} catch (\Error $e) {
				throw new InstallationException("Elgg lib file failed include: $path/$file");
			}
		}
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
	 *    - plugins_load system
	 *    - plugins_boot system
	 *    - init system
	 *    - ready system
	 *
	 * Please note that the Elgg session is started after all plugins are loader, there will therefore
	 * be no information about a logged user available until plugins_load,system event is complete.
	 *
	 * If Elgg is not fully installed, the browser will be redirected to an installation page.
	 *
	 * @return void
	 *
	 * @internal
	 */
	public function bootCore() {
		$boot = new BootHandler($this);
		$boot();
	}
	
	/**
	 * Retrieve the boot status of the application
	 *
	 * @param string $type status to check
	 *
	 * @return bool
	 * @since 4.3
	 */
	public function getBootStatus(string $type): bool {
		return $this->boot_status[$type] ?? false;
	}

	/**
	 * Sets the boot status
	 *
	 * @param string $type   type of status to set
	 * @param bool   $status value of the status
	 *
	 * @return void
	 * @since 4.3
	 */
	public function setBootStatus(string $type, bool $status): void {
		$this->boot_status[$type] = $status;
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
		return $this->internal_services->publicDb;
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
		$CONFIG = $application->internal_services->config;
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
	 * @throws InvalidArgumentException
	 */
	public static function factory(array $spec = []) {

		$defaults = [
			'config' => null,
			'handle_exceptions' => true,
			'handle_shutdown' => true,
			'request' => null,
			'internal_services' => null,
			'set_start_time' => true,
			'settings_path' => '',
		];
		$spec = array_merge($defaults, $spec);

		if ($spec['set_start_time']) {
			// The time with microseconds when the Elgg engine was started.
			if (!isset($GLOBALS['START_MICROTIME'])) {
				$GLOBALS['START_MICROTIME'] = microtime(true);
			}
		}

		if ($spec['handle_exceptions']) {
			set_error_handler(new ErrorHandler());
			set_exception_handler(new ExceptionHandler());
		}

		self::loadCore();

		if (!$spec['internal_services']) {
			if (!$spec['config']) {
				$spec['config'] = Config::factory($spec['settings_path']);
			}
			
			$spec['internal_services'] = InternalContainer::factory(['config' => $spec['config']]);
		}

		if ($spec['request']) {
			if (!$spec['request'] instanceof HttpRequest) {
				throw new InvalidArgumentException('Given request is not a ' . HttpRequest::class);
			}

			$spec['request']->initializeTrustedProxyConfiguration($spec['internal_services']->config);
			$spec['request']->correctBaseURL($spec['internal_services']->config);
			$spec['internal_services']->set('request', $spec['request']);
		}

		$app = new self($spec['internal_services']);

		if ($spec['handle_shutdown']) {
			register_shutdown_function(new ShutdownHandler($app));
		}

		return $app;
	}

	/**
	 * Route a request
	 *
	 * @param HttpRequest $request Request
	 *
	 * @return Response|false
	 */
	public static function route(HttpRequest $request) {
		self::loadCore();

		if ($request->isRewriteCheck()) {
			$response = new OkResponse(HttpRequest::REWRITE_TEST_OUTPUT);
			return self::respond($response);
		}

		if (self::$_instance) {
			$app = self::$_instance;
			$app->internal_services->set('request', $request);
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
	 * @return Response|null Sent response
	 */
	public static function respond(ResponseBuilder $builder): ?Response {
		if (self::$_instance) {
			self::$_instance->internal_services->responseFactory->respond($builder);

			return self::$_instance->internal_services->responseFactory->getSentResponse();
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
		} catch (\Exception $ex) {
			$response = new Response($ex->getMessage(), 500);
		}

		$response->headers->set('Pragma', 'public');
		$response->headers->set('Cache-Control', 'no-store, must-revalidate');
		$response->headers->set('Expires', 'Fri, 05 Feb 1982 00:00:00 -0500');

		self::getResponseTransport()->send($response);

		return $response;
	}

	/**
	 * Elgg's front controller. Handles basically all incoming URL requests.
	 *
	 * @return Response|false True if Elgg will handle the request, false if the server should (PHP-CLI server)
	 */
	public static function index() {
		return self::route(self::getRequest());
	}

	/**
	 * Routes the request, booting core if not yet booted
	 *
	 * @return Response|null|false False if Elgg wants the PHP CLI server to handle the request
	 */
	public function run() {
		$config = $this->internal_services->config;
		$request = $this->internal_services->request;

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

			if (str_starts_with($request->getElggPath(), '/cache/')) {
				$config->_disable_session_save = true;
				$response = $this->internal_services->cacheHandler->handleRequest($request, $this)->prepare($request);
				self::getResponseTransport()->send($response);

				return $response;
			}
			
			if ($request->getElggPath() === '/refresh_token') {
				$config->_disable_session_save = true;
				$token = new \Elgg\Controllers\RefreshCsrfToken();
				$response = $token($request);
				self::getResponseTransport()->send($response);

				return $response;
			}

			if (str_starts_with($request->getElggPath(), '/serve-file/')) {
				$config->_disable_session_save = true;
				$response = $this->internal_services->serveFileHandler->getResponse($request);
				self::getResponseTransport()->send($response);

				return $response;
			}
			
			if ($this->isCli()) {
				$config->_disable_session_save = true;
			}

			$this->bootCore();

			// re-fetch new request from services in case it was replaced by route:rewrite
			$request = $this->internal_services->request;

			if (!$this->internal_services->router->route($request)) {
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

			$forward_url = (string) $this->internal_services->events->triggerResults('forward', $ex->getCode(), ['exception' => $ex], $forward_url);

			if ($forward_url && !$request->isXmlHttpRequest()) {
				if ($ex->getMessage()) {
					$this->internal_services->system_messages->addErrorMessage($ex->getMessage());
				}
				
				$response = new RedirectResponse($forward_url);
			} else {
				$response = new ErrorResponse($ex->getMessage(), $ex->getCode(), $forward_url);
			}
			
			$response->setException($ex);

			self::respond($response);
		}

		return $this->internal_services->responseFactory->getSentResponse();
	}

	/**
	 * Renders a web UI for installing Elgg.
	 *
	 * @return Response|false
	 */
	public static function install() {
		ini_set('display_errors', 1);

		try {
			$installer = new \ElggInstaller();
			$response = $installer->run();
		} catch (\Exception $ex) {
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
	 */
	public static function upgrade() {

		try {
			self::migrate();
			self::start();

			$request = self::$_instance->internal_services->request;
			$signer = self::$_instance->internal_services->urlSigner;

			$url = $request->getCurrentURL();
			$query = $request->getParams();

			// We need to resign the URL because the path is different
			$mac = elgg_extract(UrlSigner::KEY_MAC, $query);
			if (isset($mac) && !$signer->isValid($url)) {
				throw new HttpException(elgg_echo('invalid_request_signature'), ELGG_HTTP_FORBIDDEN);
			}

			unset($query[UrlSigner::KEY_MAC]);

			$base_url = elgg_normalize_site_url('upgrade/init');
			$url = elgg_http_add_url_query_elements($base_url, $query);

			if (isset($mac)) {
				$url = self::$_instance->internal_services->urlSigner->sign($url);
			}

			$response = new RedirectResponse($url, ELGG_HTTP_PERMANENTLY_REDIRECT);
		} catch (\Exception $ex) {
			$response = new ErrorResponse($ex->getMessage(), $ex->getCode() ?: ELGG_HTTP_INTERNAL_SERVER_ERROR);
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
		
		$constants = Paths::elgg() . 'engine/lib/constants.php';
		Includer::requireFileOnce($constants);
		
		$conf = Paths::elgg() . 'engine/schema/migrations.php';
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
	 *
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
			'paths' => [
				'migrations' => Paths::elgg() . 'engine/schema/migrations/',
			],
			'environments' => [
				'default_migration_table' => "{$conn['prefix']}migrations",
				'default_environment' => 'prod',
				'prod' => [
					'adapter' => 'mysql',
					'host' => $conn['host'],
					'port' => $conn['port'],
					'name' => $conn['database'],
					'user' => $conn['user'],
					'pass' => $conn['password'],
					'charset' => $conn['encoding'],
					'table_prefix' => $conn['prefix'],
				],
			],
		];
	}

	/**
	 * Allow plugins to rewrite the path.
	 *
	 * @return void
	 * @internal
	 */
	public function allowPathRewrite() {
		$request = $this->internal_services->request;
		$new = $this->internal_services->router->allowRewrite($request);
		if ($new === $request) {
			return;
		}

		$this->internal_services->set('request', $new);
	}

	/**
	 * Is application running in CLI
	 *
	 * @return bool
	 */
	public static function isCli() {
		switch (PHP_SAPI) {
			case 'cli':
			case 'phpdbg':
				return true;

			default:
				return false;
		}
	}

	/**
	 * Build request object
	 *
	 * @return \Elgg\Http\Request
	 */
	public static function getRequest() {
		if (self::$_instance) {
			return self::$_instance->internal_services->request;
		}

		return HttpRequest::createFromGlobals();
	}

	/**
	 * Load console input interface
	 *
	 * @return InputInterface
	 */
	public static function getStdIn() {
		if (self::isCli()) {
			$request = self::getRequest();
			$argv = $request->server->get('argv') ?: [];
			return new ArgvInput($argv);
		}

		return new ArrayInput([]);
	}

	/**
	 * Load console output interface
	 *
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
	 *
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
	 *
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
			'events.php',
			'access.php',
			'actions.php',
			'admin.php',
			'annotations.php',
			'breadcrumbs.php',
			'cache.php',
			'configuration.php',
			'constants.php',
			'context.php',
			'deprecated-6.1.php',
			'entities.php',
			'external_files.php',
			'filestore.php',
			'gatekeepers.php',
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
			'sessions.php',
			'users.php',
			'views.php',
			'widgets.php',
		];
	}
}
