<?php

namespace Elgg\Application;

use Elgg\Application;
use Elgg\AutoloadManager;
use Elgg\Database\Insert;
use Elgg\Event;
use Elgg\Mocks\Di\InternalContainer;
use Elgg\UnitTestCase;

/**
 * @group Application
 * @group Shutdown
 */
class ShutdownHandlerUnitTest extends UnitTestCase {

	/**
	 * @return Application
	 */
	function createMockApplication(array $params = []) {
		$config = self::getTestingConfig();
		$sp = InternalContainer::factory(['config' => $config]);

		// persistentLogin service needs this set to instantiate without calling DB
		$sp->config->getCookieConfig();
		$sp->config->system_cache_enabled = false;
		$sp->config->site = new \ElggSite((object) [
			'guid' => 1,
		]);
		$sp->config->site->name = 'Testing Site';

		$app = Application::factory(array_merge([
			'internal_services' => $sp,
			'handle_exceptions' => false,
			'handle_shutdown' => false,
			'set_start_time' => false,
		], $params));

		Application::setInstance($app);

		return $app;
	}

	public function testHandlesDbShutdown() {
		$app = Application::getInstance();

		$qb = Insert::intoTable('config');
		$qb->values([
			'name' => $qb->param('foo', ELGG_VALUE_STRING),
			'value' => $qb->param(serialize('bar'), ELGG_VALUE_STRING),
		]);

		$db = $app->internal_services->db;
		/* @var $db \Elgg\Mocks\Database */

		$db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
		]);

		$shutdown = new ShutdownHandler($app);
		$shutdown->shutdownDatabase();

		$delayed = $db->reflectDelayedQueries();

		$this->assertEmpty($delayed);
	}

	public function testHandlesAppShutdown() {

		$app = $this->createApplication([
			'set_start_time' => true,
		]);

		$calls = new \stdClass();
		$calls->{'shutdown:before'} = 0;
		$calls->{'shutdown'} = 0;
		$calls->{'shutdown:after'} = 0;

		$app->internal_services->events->registerHandler('all', 'system', function(Event $event) use (&$calls) {
			$type = $event->getName();
			$calls->$type += 1;
		});

		$shutdown = new ShutdownHandler($app);
		$shutdown->shutdownApplication();

		foreach ($calls as $count) {
			$this->assertEquals(1, $count);
		}
	}

	public function testPersistsCaches() {

		$app = $this->createMockApplication();

		$app->internal_services->autoloadManager->deleteCache();
		$app->internal_services->autoloadManager->loadCache();

		$cache = $app->internal_services->autoloadManager->getCache()->load(AutoloadManager::FILENAME);
		$this->assertNull($cache);

		$app->bootCore();

		$dir = $this->normalizeTestFilePath('class_scanner');
		$app->internal_services->autoloadManager->addClasses($dir);

		$shutdown = new ShutdownHandler($app);
		$shutdown->persistCaches();

		$cache = $app->internal_services->autoloadManager->getCache()->load(AutoloadManager::FILENAME);
		
		$this->assertContains($dir, $cache['scannedDirs']);
	}
}
