<?php

namespace Elgg;

use Elgg\Mocks\Di\MockServiceProvider;

/**
 * @group UnitTests
 */
class ApplicationUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	function testElggReturnsApp() {
		$this->assertInstanceOf(Application::class, elgg());
	}

	function testStartsTimer() {
		global $GLOBALS;

		unset($GLOBALS['START_MICROTIME']);

		$config = self::getTestingConfig();
		$sp = new MockServiceProvider($config);

		Application::factory([
			'service_provider' => $sp,
			'handle_exceptions' => false,
			'handle_shutdown' => false,
			'kernel' => function (ApplicationContainer $c) {
				return new TestingKernel($c->application, $c->cacheHandler, $c->serveFileHandler);
			},
		]);

		$this->assertTrue(is_float($GLOBALS['START_MICROTIME']));
	}

	function testServices() {
		$services = _elgg_services();
		$app = new Application($services);

		$names = [
			'menus',
			'table_columns',
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
