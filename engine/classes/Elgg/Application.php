<?php

namespace Elgg;

use ConfigurationException;
use Doctrine\DBAL\Connection;
use Elgg\Database\DbConfig;
use Elgg\Di\ApplicationContainer;
use Elgg\Di\ServiceProvider;
use Elgg\Filesystem\Directory;
use Elgg\Filesystem\Directory\Local;
use Elgg\Http\Request;
use Elgg\Project\Paths;
use InstallationException;
use InvalidArgumentException;

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
 * @property-read \Elgg\Menu\Service                    $menus
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
	 * Start and boot the core
	 *
	 * @return self
	 * @throws InstallationException
	 */
	public static function start() {
		$app = ApplicationContainer::getInstance()->application;
		$app->boot();

		return $app;
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
		$container = ApplicationContainer::factory($spec);

		return $container->application;
	}

	/**
	 * Get container instance
	 *
	 * @return ApplicationContainer
	 */
	public static function getContainer() {
		return ApplicationContainer::getInstance();
	}

	/**
	 * Fully boots the application, trigger boot and init system events
	 * @return void
	 * @throws InstallationException
	 */
	public static function boot() {
		return self::getContainer()->boot->boot();
	}

	/**
	 * Bootstraps the application without triggering the boot sequence
	 *
	 * @return void
	 * @throws InstallationException
	 * @throws \ClassException
	 * @throws \DatabaseException
	 * @throws \InvalidParameterException
	 * @throws \SecurityException
	 */
	public static function bootstrap() {
		return self::getContainer()->boot->bootstrap();
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

		return $app->run();
	}

	/**
	 * Routes the request, booting core if not yet booted
	 *
	 * @return bool False if Elgg wants the PHP CLI server to handle the request
	 */
	public function run() {
		return self::getContainer()->kernel->run();
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
		return self::getContainer()->installationHandler->handleInstall();
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
		return self::getContainer()->upgradeHandler->handleUpgrade();
	}

	/**
	 * Runs database migrations
	 *
	 * @throws InstallationException
	 * @return bool
	 */
	public static function migrate() {
		return self::getContainer()->migrationHandler->handleMigrations();
	}

	/**
	 * Destroy current application instance
	 * @return void
	 * @access private
	 */
	public static function destroy() {
		ApplicationContainer::setInstance(null);
	}
}
