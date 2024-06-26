<?php

namespace Elgg;

use Elgg\Helpers\Database\DatabaseTestObj;
use PHPUnit\Framework\MockObject\MockObject;

class DatabaseUnitTest extends \Elgg\UnitTestCase {

	public function testFingerprintingOfCallbacks() {
		$db = $this->getDbMock();
		
		$reflection_method = new \ReflectionMethod($db, 'fingerprintCallback');
		
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
		$db->getData(\Elgg\Database\Select::fromTable('foo')->select('*'), 'blorg!');
	}
	
	/**
	 * @return \Elgg\Database|MockObject
	 */
	private function getDbMock() {
		return $this->getMockBuilder(\Elgg\Database::class)
			->onlyMethods(['updateData'])
			->setConstructorArgs([
				new \Elgg\Database\DbConfig((object) ['dbprefix' => 'test_']),
				_elgg_services()->queryCache,
				_elgg_services()->config,
			])
			->getMock();
	}
}
