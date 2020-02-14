<?php

namespace Elgg\Application;

use Elgg\Application;
use Elgg\AutoloadManager;
use Elgg\Database\Insert;
use Elgg\Event;
use Elgg\Mocks\Di\MockServiceProvider;
use Elgg\UnitTestCase;

/**
 * @group Application
 * @group Shutdown
 */
class ShutdownHandlerUnitTest extends UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	/**
	 * @return Application
	 */
	function createMockApplication(array $params = []) {
		$config = self::getTestingConfig();
		$sp = new MockServiceProvider($config);

		// persistentLogin service needs this set to instantiate without calling DB
		$sp->config->getCookieConfig();
		$sp->config->boot_complete = false;
		$sp->config->system_cache_enabled = false;
		$sp->config->site = new \ElggSite((object) [
			'guid' => 1,
		]);
		$sp->config->site->name = 'Testing Site';

		$app = Application::factory(array_merge([
			'service_provider' => $sp,
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

		$db = $app->_services->db;
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

		$app->_services->events->registerHandler('all', 'system', function(Event $event) use (&$calls) {
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

		$app->_services->autoloadManager->deleteCache();
		$app->_services->autoloadManager->loadCache();

		$cache = $app->_services->autoloadManager->getCache()->load(AutoloadManager::FILENAME);
		$this->assertNull($cache);

		$app->bootCore();

		$dir = $this->normalizeTestFilePath('class_scanner');
		$app->_services->autoloadManager->addClasses($dir);

		$shutdown = new ShutdownHandler($app);
		$shutdown->persistCaches();

		$cache = $app->_services->autoloadManager->getCache()->load(AutoloadManager::FILENAME);
		
		$this->assertContains($dir, $cache['scannedDirs']);
	}
}
