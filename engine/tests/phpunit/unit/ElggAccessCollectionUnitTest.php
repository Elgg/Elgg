<?php

use Elgg\UnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class ElggAccessCollectionUnitTest extends UnitTestCase {
	
	#[DataProvider('invalidValuesProvider')]
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
