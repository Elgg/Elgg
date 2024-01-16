<?php

use Elgg\UnitTestCase;

class ElggAccessCollectionUnitTest extends UnitTestCase {
	
	/**
	 * @dataProvider invalidValuesProvider
	 */
	public function testSetterWithInvalidValues($name, $value) {
		$acl = new \ElggAccessCollection();
		
		$this->expectException(\Elgg\Exceptions\ExceptionInterface::class);
		$acl->$name = $value;
	}
	
	public static function invalidValuesProvider() {
		return [
			['subtype', str_repeat('a', 300)],
			['name', ''],
			['owner_guid', -1],
		];
	}
	
	public function testIsLoggable() {
		$unsaved = new \ElggAccessCollection();
		$this->assertEmpty($unsaved->getSystemLogID());
	}
}
