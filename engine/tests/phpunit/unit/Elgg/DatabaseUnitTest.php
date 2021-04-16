<?php

namespace Elgg;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Matcher;
use Elgg\Helpers\Database\DatabaseTestObj;

/**
 * @group UnitTests
 * @group Database
 */
class DatabaseUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testFingerprintingOfCallbacks() {
		$db = $this->getDbMock();
		
		$reflection_method = new \ReflectionMethod($db, 'fingerprintCallback');
		$reflection_method->setAccessible(true);
		
		$prints = [];
		$uniques = 0;

		$prints[$reflection_method->invoke($db, 'foo')] = true;
		$uniques++;

		$prints[$reflection_method->invoke($db, 'foo::bar')] = true;
		$prints[$reflection_method->invoke($db, [
			'foo',
			'bar'
		])] = true;
		$uniques++;

		$obj1 = new DatabaseTestObj();
		$prints[$reflection_method->invoke($db, [
			$obj1,
			'__invoke'
		])] = true;
		$prints[$reflection_method->invoke($db, $obj1)] = true;
		$uniques++;

		$obj2 = new DatabaseTestObj();
		$prints[$reflection_method->invoke($db, [
			$obj2,
			'__invoke'
		])] = true;
		$uniques++;

		$this->assertEquals($uniques, count($prints));
	}

	public function testInvalidCallbacksThrow() {
		$db = $this->getDbMock();
		
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('$callback must be a callable function. Given blorg!');
		$db->getData("SELECT 1", 'blorg!');
	}
	
	/**
	 * @return \Elgg\Database|MockObject
	 */
	private function getDbMock() {
		return $this->getMockBuilder(\Elgg\Database::class)
			->onlyMethods(['updateData'])
			->setConstructorArgs([
				new \Elgg\Database\DbConfig((object) ['dbprefix' => 'test_']),
				_elgg_services()->queryCache
			])
			->getMock();
	}
}
