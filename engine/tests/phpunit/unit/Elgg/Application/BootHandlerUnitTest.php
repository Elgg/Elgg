<?php

namespace Elgg\Application;

use Elgg\Application;
use Elgg\Event;
use Elgg\Mocks\Di\MockServiceProvider;
use Elgg\UnitTestCase;

/**
 * @group Boot
 * @group Application
 */
class BootHandlerUnitTest extends UnitTestCase {

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

	public function testCanDoFullBoot() {

		$app = $this->createMockApplication();

		$app->bootCore();

		$this->assertTrue($app->_services->config->boot_complete);
	}

	public function testCanDoFullBootWithoutDb() {

		// this needs fixing
		// https://github.com/Elgg/Elgg/issues/12029
		$this->markTestSkipped();

		$app = $this->createMockApplication();

		$app->_services->setValue('db', null);

		$app->bootCore();

		$this->assertTrue($app->_services->config->boot_complete);

		$this->assertFalse($app->_services->config->_service_boot_complete);
		$this->assertFalse($app->_services->config->_plugins_boot_complete);
		$this->assertFalse($app->_services->config->_application_boot_complete);
	}

	public function testCanBootServices() {

		$app = $this->createMockApplication();

		$boot = new BootHandler($app);
		$boot->bootServices();

		$this->assertTrue($app->_services->config->_service_boot_complete);
	}

	public function testCanBootPlugins() {

		$app = $this->createMockApplication();

		$boot = new BootHandler($app);
		$boot->bootServices();
		$boot->bootPlugins();

		$this->assertTrue($app->_services->config->_plugins_boot_complete);
	}

	public function testCanBootApplication() {
		$app = $this->createMockApplication();

		$boot = new BootHandler($app);
		$boot->bootServices();
		$boot->bootPlugins();
		$boot->bootApplication();

		$this->assertTrue($app->_services->config->_application_boot_complete);
	}

	public function testBootEventCalls() {

		$calls = new \stdClass();
		$calls->{'plugins_load:before'} = 0;
		$calls->{'plugins_load'} = 0;
		$calls->{'plugins_load:after'} = 0;
		$calls->{'plugins_boot:before'} = 0;
		$calls->{'plugins_boot'} = 0;
		$calls->{'plugins_boot:after'} = 0;
		$calls->{'init:before'} = 0;
		$calls->{'init'} = 0;
		$calls->{'init:after'} = 0;
		$calls->{'ready:before'} = 0;
		$calls->{'ready'} = 0;
		$calls->{'ready:after'} = 0;

		$app = $this->createMockApplication();

		$app->_services->events->registerHandler('all', 'system', function(Event $event) use (&$calls) {
			$type = $event->getName();

			$calls->$type += 1;
		});

		$app->bootCore();

		$this->assertTrue($app->_services->config->boot_complete);

		foreach ($calls as $event => $count) {
			$this->assertEquals(1, $count);
		}
	}

	public function testCanSetCustomUserClassDuringBootSequence() {

		$app = $this->createMockApplication();

		$app->_services->events->registerHandler('plugins_load', 'system', function(Event $event) {
			elgg_set_entity_class('user', 'custom_user', CustomUser::class);
		});

		$user = $this->createUser([
			'subtype' => 'custom_user',
		]);

		$app->_services->session->set('guid', $user->guid);

		$user->invalidateCache();

		$app->bootCore();

		$this->assertInstanceOf(CustomUser::class, $app->_services->session->getLoggedInUser());

		$app->_services->session->removeLoggedInUser();
	}

}

class CustomUser extends \ElggUser {

}