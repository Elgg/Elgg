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

	use TestSeeding;

	/**
	 * {@inheritdoc}
	 */
	public static function createApplication() {

		$settings_path = Application::elggDir()->getPath('engine/tests/elgg-config/settings.php');
		$config = Config::factory($settings_path, true);

		$sp = new ServiceProvider($config);

		// persistentLogin service needs this set to instantiate without calling DB
		$sp->config->getCookieConfig();
		$sp->config->quick_seeding = true;

		$sp->setFactory('session', function () {
			return \ElggSession::getMock();
		});

		$app = Application::factory([
			'config' => $config,
			'service_provider' => $sp,
			'handle_exceptions' => false,
			'handle_shutdown' => false,
			'set_start_time' => false,
		]);

		if (!$app->getDbConnection()) {
			return false;
		}

		Application::setInstance($app);

		if (in_array('--verbose', $_SERVER['argv'])) {
			Logger::$verbosity = ConsoleOutput::VERBOSITY_VERY_VERBOSE;
		} else {
			Logger::$verbosity = ConsoleOutput::VERBOSITY_NORMAL;
		}

		// turn off system log
		$app->_services->hooks->getEvents()->unregisterHandler('all', 'all', 'system_log_listener');
		$app->_services->hooks->getEvents()->unregisterHandler('log', 'systemlog', 'system_log_default_logger');

		// To speed up integration tests a little we will add __testing metadata and wipe entities on shutdown rather within test cases
		$app->_services->hooks->getEvents()->registerHandler('create', 'all', function(\Elgg\Event $event) {
			$entity = $event->getObject();
			if (!$entity instanceof \ElggEntity) {
				return;
			}
			$entity->__testing = true;
		});

		$app->bootCore();

		//elgg_flush_caches();

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

		$this->seedTestEntities();

		$app = Application::getInstance();

		if ($this instanceof LegacyIntegrationTestCase) {
			$app->_services->session->setLoggedInUser($this->getAdmin());
		}

		$this->up();
	}

	/**
	 * {@inheritdoc}
	 */
	final protected function tearDown() {
		$this->down();

		//$this->unseedTestEntities();

		$app = Application::getInstance();

		if ($this instanceof LegacyIntegrationTestCase) {
			$app->_services->session->removeLoggedInUser();
		}

		parent::tearDown();
	}
}
