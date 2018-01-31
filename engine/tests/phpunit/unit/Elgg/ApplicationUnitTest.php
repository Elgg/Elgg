<?php

namespace Elgg;

use DI\Container;
use Elgg\Application\Database;
use Elgg\Database\Select;
use Elgg\Menu\Service;
use Elgg\Views\TableColumn\ColumnFactory;

/**
 * @group UnitTests
 * @group Application
 */
class ApplicationUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	function testElggReturnsContainer() {
		$this->assertInstanceOf(Container::class, elgg());
	}

	/**
	 * @dataProvider publicServiceProvider
	 */
	function testCanAccessDiServices($svc, $class) {
		$this->assertNotNull(elgg()->$svc);
		$this->assertInstanceOf($class, elgg()->$svc);
		$this->assertEquals(elgg()->$svc, elgg()->get($svc));
	}

	function publicServiceProvider() {
		return [
			['db', Database::class],
			['menus', Service::class],
			['table_columns', ColumnFactory::class],
		];
	}

	function testPublicServiceReferencesCoreService() {
		$this->assertSame(elgg()->db, _elgg_services()->publicDb);
	}

	function testCanCallService() {
		$qb = Select::fromTable('entities');
		$qb->select('1');

		_elgg_services()->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'results' => [1],
		]);

		$result = elgg()->call(function(Database $db) use ($qb) {
			return $db->getDataRow($qb);
		});

		$this->assertEquals(1, $result);
	}

	function testStartsTimer() {
		unset($GLOBALS['START_MICROTIME']);

		Application::factory([
			'handle_shutdown' => false,
			'handle_exceptions' => false,
			'config' => _elgg_config(),
		]);

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
