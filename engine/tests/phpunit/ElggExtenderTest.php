<?php

class ElggExtenderTest extends \Elgg\TestCase {

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
		$this->assertSame('integer', detect_extender_valuetype(-1));
		$this->assertSame('integer', detect_extender_valuetype(0));
		$this->assertSame('integer', detect_extender_valuetype(2));
	}

	public function testFloatsAndNumericsAndStringableObjectsAreTypedAsText() {
		$this->assertSame('text', detect_extender_valuetype(3.14));
		$this->assertSame('text', detect_extender_valuetype('3.14'));
		$this->assertSame('text', detect_extender_valuetype(new ElggExtenderTest_Object));
	}

	public function testOthersTypedAsTextWithWarning() {
		_elgg_services()->logger->disable();

		$this->assertSame('text', detect_extender_valuetype(null));
		$this->assertSame('text', detect_extender_valuetype(true));
		$this->assertSame('text', detect_extender_valuetype((object) []));

		$expected = [
			[
				'message' => 'Metadata and annotations store only integers and strings. NULL given.',
        		'level' => 300,
			],
			[
				'message' => 'Metadata and annotations store only integers and strings. boolean given.',
				'level' => 300,
			],
			[
				'message' => 'Metadata and annotations store only integers and strings. object given.',
        		'level' => 300,
			]
		];
		$this->assertSame($expected, _elgg_services()->logger->enable());
	}

	public function testAcceptRecognizedTypes() {
		$this->assertSame('text', detect_extender_valuetype(123, 'text'));
		$this->assertSame('integer', detect_extender_valuetype(123, 'invalid'));

		$this->assertSame('integer', detect_extender_valuetype('hello', 'integer'));
		$this->assertSame('text', detect_extender_valuetype('hello', 'invalid'));
	}

}

class ElggExtenderTest_Object {
	function __toString() {
		return 'oh hey';
	}
}
