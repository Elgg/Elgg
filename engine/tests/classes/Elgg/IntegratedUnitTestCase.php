<?php

namespace Elgg;

use Elgg\Mocks\Di\MockServiceProvider;
use Psr\Log\LogLevel;

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
	public static function createApplication(array $params = []) {

		if (isset(self::$_testing_app)) {
			$app = self::$_testing_app;
			
			Application::setInstance($app);

			// Invalidate caches
			$app->_services->dataCache->clear();
			$app->_services->sessionCache->clear();

			return $app;
		}

		Application::setInstance(null);

		$config = self::getTestingConfig();
		$sp = new MockServiceProvider($config);

		// persistentLogin service needs this set to instantiate without calling DB
		$sp->config->getCookieConfig();
		$sp->config->boot_complete = false;
		$sp->config->system_cache_enabled = true;

		$app = Application::factory(array_merge([
			'service_provider' => $sp,
			'handle_exceptions' => false,
			'handle_shutdown' => false,
			'set_start_time' => false,
		], $params));

		Application::setInstance($app);

		if (in_array('--verbose', $_SERVER['argv'])) {
			$app->_services->logger->setLevel(LogLevel::DEBUG);
		} else {
			$app->_services->logger->setLevel(LogLevel::ERROR);
		}

		_elgg_services()->config->site = new \ElggSite((object) [
			'guid' => 1,
		]);

		$app->bootCore();

		$app->_services->events->unregisterHandler('all', 'all', 'Elgg\SystemLog\Logger::listen');
		$app->_services->events->unregisterHandler('log', 'systemlog', 'Elgg\SystemLog\Logger::log');

		self::$_testing_app = $app;

		return $app;
	}
}