<?php

class ElggRelationshipTest extends PHPUnit_Framework_TestCase {

	public function testSettingAndGettingAttribute() {
		$obj = $this->getMockForAbstractClass('ElggRelationship', array(), '', false);
		$obj->relationship = 'hasSister';
		$this->assertEquals('hasSister', $obj->relationship);
	}

	public function testGettingNonexistentAttribute() {
		$obj = $this->getMockForAbstractClass('ElggRelationship', array(), '', false);
		$this->assertNull($obj->foo);
	}
}
