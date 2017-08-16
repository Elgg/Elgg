<?php

/**
 * @group UnitTests
 */
class ElggExtenderUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testSettingAndGettingAttribute() {
		$obj = $this->getMockForAbstractClass('\ElggExtender');
		$obj->name = 'comment';
		$this->assertEquals('comment', $obj->name);
	}

	public function testGettingNonexistentAttribute() {
		$obj = $this->getMockForAbstractClass('\ElggExtender');
		$this->assertNull($obj->foo);
	}

	public function testSettingValueAttribute() {
		$obj = $this->getMockForAbstractClass('\ElggExtender');
		$obj->value = '36';
		$this->assertSame('36', $obj->value);
		$this->assertEquals('text', $obj->value_type);
		$obj->value = 78;
		$this->assertSame(78, $obj->value);
		$this->assertEquals('integer', $obj->value_type);
	}

	public function testSettingValueExplicitly() {
		$obj = $this->getMockForAbstractClass('\ElggExtender');
		$obj->setValue('36', 'integer');
		$this->assertSame(36, $obj->value);
		$this->assertEquals('integer', $obj->value_type);
	}

	public function testIntsAreTypedAsInteger() {
		$this->assertSame('integer', \ElggExtender::detectValueType(-1));
		$this->assertSame('integer', \ElggExtender::detectValueType(0));
		$this->assertSame('integer', \ElggExtender::detectValueType(2));
	}

	public function testFloatsAndNumericsAndStringableObjectsAreTypedAsText() {
		$this->assertSame('text', \ElggExtender::detectValueType(3.14));
		$this->assertSame('text', \ElggExtender::detectValueType('3.14'));
		$this->assertSame('text', \ElggExtender::detectValueType(new ElggExtenderTest_Object));
	}

	public function testAcceptRecognizedTypes() {
		$this->assertSame('text', \ElggExtender::detectValueType(123, 'text'));
		$this->assertSame('integer', \ElggExtender::detectValueType(123, 'invalid'));

		$this->assertSame('integer', \ElggExtender::detectValueType('hello', 'integer'));
		$this->assertSame('text', \ElggExtender::detectValueType('hello', 'invalid'));
	}

}

class ElggExtenderTest_Object {
	function __toString() {
		return 'oh hey';
	}
}
