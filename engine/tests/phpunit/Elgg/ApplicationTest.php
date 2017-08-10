<?php

namespace Elgg;

class ApplicationTest extends \Elgg\TestCase {

	function testElggReturnsApp() {
		$this->assertInstanceOf(Application::class, elgg());
	}

	function testStartsTimer() {
		$app = Application::$_instance;

		Application::$_instance = null;
		unset($GLOBALS['START_MICROTIME']);

		Application::factory([
			'handle_shutdown' => false,
			'handle_exceptions' => false,
			'set_global_config' => false,
			'config' => new Config(TestCase::getTestingConfigArray()),
		]);
		Application::$_instance = $app;

		$this->assertTrue(is_float($GLOBALS['START_MICROTIME']));
	}

	function testServices() {
		$services = _elgg_services();
		$app = new Application($services);

		$names = [
				//'config',
		];

		foreach ($names as $name) {
			$this->assertSame($services->{$name}, $app->{$name});
		}
	}

	function testCanLoadSettings() {
		$this->markTestIncomplete();
	}

	function testCanGetDb() {
		$this->markTestIncomplete();
	}

	function testGettingDbLoadsSettings() {
		$this->markTestIncomplete();
	}

	function testCanLoadCore() {
		$this->markTestIncomplete();
	}

	function testCanBootCore() {
		$this->markTestIncomplete();
	}

	function testBootLoadsCore() {
		$this->markTestIncomplete();
	}

	function testRunCanRouteCliServer() {
		$this->markTestIncomplete();
	}

	function testRunBootsAndRoutes() {
		$this->markTestIncomplete();
	}

}
