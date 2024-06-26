<?php

use Elgg\Helpers\ElggExtenderExtension;
use Elgg\Helpers\ElggExtenderTestObject;

class ElggExtenderUnitTest extends \Elgg\UnitTestCase {
	
	public function testSettingAndGettingAttribute() {
		$obj = new ElggExtenderExtension();
		
		$obj->name = 'comment';
		$this->assertEquals('comment', $obj->name);
	}

	public function testGettingNonexistentAttribute() {
		$obj = new ElggExtenderExtension();
		$this->assertNull($obj->foo);
	}

	public function testSettingValueAttribute() {
		$obj = new ElggExtenderExtension();
		$obj->value = '36';
		$this->assertSame('36', $obj->value);
		$this->assertEquals('text', $obj->value_type);
		$obj->value = 78;
		$this->assertSame(78, $obj->value);
		$this->assertEquals('integer', $obj->value_type);
	}

	public function testSettingValueExplicitly() {
		$obj = new ElggExtenderExtension();
		$obj->setValue('36', 'integer');
		$this->assertSame(36, $obj->value);
		$this->assertEquals('integer', $obj->value_type);
	}

	public function testBooleansAreTypedAsBool() {
		$this->assertSame('bool', \ElggExtender::detectValueType(true));
		$this->assertSame('bool', \ElggExtender::detectValueType(false));
	}

	public function testIntsAreTypedAsInteger() {
		$this->assertSame('integer', \ElggExtender::detectValueType(-1));
		$this->assertSame('integer', \ElggExtender::detectValueType(0));
		$this->assertSame('integer', \ElggExtender::detectValueType(2));
	}

	public function testFloatsAndNumericsAndStringableObjectsAreTypedAsText() {
		$this->assertSame('text', \ElggExtender::detectValueType(3.14));
		$this->assertSame('text', \ElggExtender::detectValueType('3.14'));
		$this->assertSame('text', \ElggExtender::detectValueType(new ElggExtenderTestObject()));
	}

	public function testAcceptRecognizedTypes() {
		$this->assertSame('text', \ElggExtender::detectValueType(123, 'text'));
		$this->assertSame('integer', \ElggExtender::detectValueType(123, 'invalid'));

		$this->assertSame('integer', \ElggExtender::detectValueType('hello', 'integer'));
		$this->assertSame('text', \ElggExtender::detectValueType('hello', 'invalid'));

		$this->assertSame('integer', \ElggExtender::detectValueType(true, 'integer'));
		$this->assertSame('bool', \ElggExtender::detectValueType(true, 'invalid'));
	}
}
