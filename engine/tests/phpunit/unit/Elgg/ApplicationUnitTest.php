<?php

namespace Elgg;

use Elgg\Di\ServiceProvider;

class ApplicationUnitTest extends \Elgg\UnitTestCase {

	public function setUp() {
		parent::setUp();
	}

	public function tearDown() {
		// Make sure we have the testing app that hasn't been altered
		self::bootstrap();
	}

	function testElggReturnsApp() {
		$this->assertInstanceOf(Application::class, elgg());
	}

	function testStartsTimer() {

		$config = $this->getTestingConfigArray();

		Application::$_instance = null;
		unset($GLOBALS['START_MICROTIME']);

		Application::factory([
			'handle_shutdown' => false,
			'handle_exceptions' => false,
			'set_global_config' => false,
			'config' => new Config($config),
		]);

		$this->assertTrue(is_float($GLOBALS['START_MICROTIME']));
	}

	function testServices() {
		Application::$_instance = null;

		$config = $this->getTestingConfigArray();

		$app = Application::factory([
			'handle_shutdown' => false,
			'handle_exceptions' => false,
			'set_global_config' => false,
			'config' => new Config($config),
		]);

		$names = [
			'menus',
			'table_columns',
		];

		foreach ($names as $name) {
			$this->assertSame(_elgg_services()->{$name}, $app->{$name});
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
