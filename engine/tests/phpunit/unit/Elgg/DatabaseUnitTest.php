<?php

namespace Elgg;

use Elgg\Helpers\Database\DatabaseTestObj;
use PHPUnit\Framework\MockObject\MockObject;

class DatabaseUnitTest extends \Elgg\UnitTestCase {

	public function testFingerprintingOfCallbacks() {
		$db = $this->getDbMock();
		
		$prints = [];
		$uniques = 0;

		$prints[$this->invokeInaccessableMethod($db, 'fingerprintCallback', 'foo')] = true;
		$uniques++;

		$prints[$this->invokeInaccessableMethod($db, 'fingerprintCallback', 'foo::bar')] = true;
		$prints[$this->invokeInaccessableMethod($db, 'fingerprintCallback', [
			'foo',
			'bar'
		])] = true;
		$uniques++;

		$obj1 = new DatabaseTestObj();
		$prints[$this->invokeInaccessableMethod($db, 'fingerprintCallback', [
			$obj1,
			'__invoke'
		])] = true;
		$prints[$this->invokeInaccessableMethod($db, 'fingerprintCallback', $obj1)] = true;
		$uniques++;

		$obj2 = new DatabaseTestObj();
		$prints[$this->invokeInaccessableMethod($db, 'fingerprintCallback', [
			$obj2,
			'__invoke'
		])] = true;
		$uniques++;

		$this->assertCount($uniques, $prints);
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
