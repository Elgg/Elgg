<?php

namespace Elgg;

use Elgg\Database\Seeds\Seeding;
use Elgg\Database\Seeds\Users;
use Elgg\Di\ServiceProvider;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Integration test abstraction
 *
 * Extend this class to run tests against an actual database
 * DO NOT RUN ON PRODUCTION
 */
abstract class IntegrationTestCase extends BaseTestCase {

	use Seeding;

	/**
	 * @var array
	 */
	static $activated_plugins;

	/**
	 * {@inheritdoc}
	 */
	public static function getSettingsPath() {
		return Application::elggDir()->getPath('engine/tests/elgg-config/integration.php');
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getResettableServices() {
		return [];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function createApplication() {

		$settings_path = self::getSettingsPath();
		$config = Config::fromFile($settings_path);

		$sp = new ServiceProvider($config);

		$app = Application::factory([
			'config' => $config,
			'service_provider' => $sp,
			'handle_exceptions' => false,
			'handle_shutdown' => false,
			'set_start_time' => false,
		]);

		$app->_services->setFactory('session', function(ServiceProvider $sp) {
			return \ElggSession::getMock();
		});

		$app->_services->setFactory('mailer', function(ServiceProvider $sp) {
			return new \Zend\Mail\Transport\InMemory();
		});

		Application::setInstance($app);

		if (in_array('--verbose', $_SERVER['argv'])) {
			Logger::$verbosity = ConsoleOutput::VERBOSITY_VERBOSE;
		} else {
			Logger::$verbosity = ConsoleOutput::VERBOSITY_NORMAL;
		}

		$app->bootCore();

		_elgg_services()->logger->notice('Bootstrapped a new Application instance from settings in ' . $settings_path);

		return $app;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();

		self::$activated_plugins = [];

		if (Application::$_instance->getDbConnection()) {
			$plugins = elgg_get_plugins('inactive');
			foreach ($plugins as $plugin) {
				if ($plugin->activate()) {
					self::$activated_plugins[] = $plugin->getID();
				}
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public static function tearDownAfterClass() {
		foreach (self::$activated_plugins as $id) {
			$plugin = elgg_get_plugin_from_id($id);
			if ($plugin) {
				$plugin->deactivate();
			}
		}

		parent::tearDownAfterClass();
	}

	/**
	 * {@inheritdoc}
	 */
	final protected function setUp() {
		parent::setUp();

		if (!Application::$_instance->getDbConnection()) {
			$this->markTestSkipped("IntegrationTestCase requires an active database connection");
		}
	}

	/**
	 * {@inheritdoc}
	 */
	final protected function tearDown() {
		parent::tearDown();
	}
}
