<?php

namespace Elgg;

use Elgg\Mocks\Database\Plugins;
use Elgg\Mocks\Di\MockServiceProvider;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Use this test case if the state of services is not important
 * This test case reuses a single application thus considerably speeding up things
 * The downside is the state of hooks, actions etc overflows into other test cases
 */
abstract class IntegratedUnitTestCase extends UnitTestCase {

	static $_testing_app;

	/**
	 * {@inheritdoc}
	 */
	public static function createApplication() {

		if (isset(self::$_testing_app)) {
			Application::setInstance(self::$_testing_app);

			return self::$_testing_app;
		}

		Application::setInstance(null);

		$config = self::getTestingConfig();
		$sp = new MockServiceProvider($config);

		// persistentLogin service needs this set to instantiate without calling DB
		$sp->config->getCookieConfig();
		$sp->config->boot_complete = false;
		$sp->config->system_cache_enabled = true;

		$app = Application::factory([
			'service_provider' => $sp,
			'handle_exceptions' => false,
			'handle_shutdown' => false,
			'set_start_time' => false,
		]);

		Application::setInstance($app);

		if (in_array('--verbose', $_SERVER['argv'])) {
			Logger::$verbosity = ConsoleOutput::VERBOSITY_VERY_VERBOSE;
		} else {
			Logger::$verbosity = ConsoleOutput::VERBOSITY_NORMAL;
		}

		_elgg_services()->config->site = new \ElggSite((object) [
			'guid' => 1,
		]);

		$app->bootCore();

		$app->_services->hooks->getEvents()->unregisterHandler('all', 'all', 'system_log_listener');
		$app->_services->hooks->getEvents()->unregisterHandler('log', 'systemlog', 'system_log_default_logger');

		self::$_testing_app = $app;

		return $app;
	}
}