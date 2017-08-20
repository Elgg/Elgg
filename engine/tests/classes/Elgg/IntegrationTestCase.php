<?php

namespace Elgg;

use Elgg\Database\Seeds\Seeding;
use Elgg\Di\ServiceProvider;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Integration test abstraction
 *
 * Extend this class to run tests against an actual database
 * DO NOT RUN ON PRODUCTION
 */
abstract class IntegrationTestCase extends BaseTestCase {

	use Seeding;

	protected $_testing_hooks;
	protected $_testing_events;

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

		// persistentLogin service needs this set to instantiate without calling DB
		$sp->config->getCookieConfig();

		$sp->setFactory('session', function() {
			return \ElggSession::getMock();
		});

		$app = Application::factory([
			'config' => $config,
			'service_provider' => $sp,
			'handle_exceptions' => false,
			'handle_shutdown' => false,
			'set_start_time' => false,
		]);

		$app->_services->setValue('testCase', null);

		Application::setInstance($app);

		if (in_array('--verbose', $_SERVER['argv'])) {
			Logger::$verbosity = ConsoleOutput::VERBOSITY_VERY_VERBOSE;
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
	}

	/**
	 * {@inheritdoc}
	 */
	public static function tearDownAfterClass() {
		parent::tearDownAfterClass();
	}

	/**
	 * {@inheritdoc}
	 */
	final protected function setUp() {
		parent::setUp();

		// Backup events and hooks so we can assert that the has clean up after iteself
		$this->_testing_hooks = _elgg_services()->hooks->getAllHandlers();
		$this->_testing_events = _elgg_services()->hooks->getEvents()->getAllHandlers();

		// @todo: backup context stack
		// @todo: backup page handlers
		// @todo: count entities before the test

		if (!Application::$_instance->getDbConnection()) {
			$this->markTestSkipped("IntegrationTestCase requires an active database connection");
		}

		if ($this instanceof LegacyIntegrationTestCase) {
			_elgg_services()->session->setLoggedInUser($this->getAdmin());
		}

		$this->up();
	}

	/**
	 * {@inheritdoc}
	 */
	final protected function tearDown() {
		$this->down();

		if ($this instanceof LegacyIntegrationTestCase) {
			_elgg_services()->session->removeLoggedInUser();
		}


		$this->assertEquals($this->_testing_hooks, _elgg_services()->hooks->getAllHandlers(), "
			The test has failed to clean up hook registrations after itself.
		");

		$this->assertEquals($this->_testing_events, _elgg_services()->hooks->getEvents()->getAllHandlers(), "
			The test has failed to clean up event registrations after itself.
		");

		// @todo: assert that context stack hasn't been messed with
		// @todo: assert that page handlers have been unregistered after tests
		// @todo: make an assertion that entity count after the test is as before
		// Test should really clean up after themselves!

		parent::tearDown();
	}
}
