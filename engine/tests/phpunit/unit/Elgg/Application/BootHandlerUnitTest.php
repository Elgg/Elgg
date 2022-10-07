<?php

namespace Elgg\Application;

use Elgg\Application;
use Elgg\Event;
use Elgg\Mocks\Di\InternalContainer;
use Elgg\UnitTestCase;
use Elgg\Helpers\CustomUser;

/**
 * @group Boot
 * @group Application
 */
class BootHandlerUnitTest extends UnitTestCase {

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

	public function testCanDoFullBoot() {

		$app = $this->createMockApplication();

		$app->bootCore();

		$this->assertTrue($app->getBootStatus('full_boot_completed'));
		$this->assertTrue($app->getBootStatus('service_boot_completed'));
		$this->assertTrue($app->getBootStatus('plugins_boot_completed'));
		$this->assertTrue($app->getBootStatus('application_boot_completed'));
	}

	public function testCanDoFullBootWithoutDb() {

		// this needs fixing
		// https://github.com/Elgg/Elgg/issues/12029
		$this->markTestSkipped();

		$app = $this->createMockApplication();

		$app->internal_services->set('db', null);

		$app->bootCore();

		$this->assertTrue($app->getBootStatus('full_boot_completed'));

		$this->assertFalse($app->getBootStatus('service_boot_completed'));
		$this->assertFalse($app->getBootStatus('plugins_boot_completed'));
		$this->assertFalse($app->getBootStatus('application_boot_completed'));
	}

	public function testCanBootServices() {

		$app = $this->createMockApplication();

		$boot = new BootHandler($app);
		$boot->bootServices();

		$this->assertTrue($app->getBootStatus('service_boot_completed'));
	}

	public function testCanBootPlugins() {

		$app = $this->createMockApplication();

		$boot = new BootHandler($app);
		$boot->bootServices();
		$boot->bootPlugins();

		$this->assertTrue($app->getBootStatus('plugins_boot_completed'));
	}

	public function testCanBootApplication() {
		$app = $this->createMockApplication();

		$boot = new BootHandler($app);
		$boot->bootServices();
		$boot->bootPlugins();
		$boot->bootApplication();

		$this->assertTrue($app->getBootStatus('application_boot_completed'));
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

		$app->internal_services->events->registerHandler('all', 'system', function(Event $event) use (&$calls) {
			$type = $event->getName();

			$calls->$type += 1;
		});

		$app->bootCore();

		$this->assertTrue($app->getBootStatus('full_boot_completed'));

		foreach ($calls as $count) {
			$this->assertEquals(1, $count);
		}
	}

	public function testCanSetCustomUserClassDuringBootSequence() {

		$app = $this->createMockApplication();

		$app->internal_services->events->registerHandler('plugins_load', 'system', function(Event $event) {
			elgg_set_entity_class('user', 'custom_user', CustomUser::class);
		});

		$user = $this->createUser([
			'subtype' => 'custom_user',
		]);

		$app->internal_services->session->set('guid', $user->guid);
		$app->internal_services->session->setUserToken($user); // normally this is done during login()

		$user->invalidateCache();

		$app->bootCore();

		$this->assertInstanceOf(CustomUser::class, $app->internal_services->session->getLoggedInUser());
	}
}
