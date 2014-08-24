<?php

class ElggExtenderTest extends \PHPUnit_Framework_TestCase {

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
}
