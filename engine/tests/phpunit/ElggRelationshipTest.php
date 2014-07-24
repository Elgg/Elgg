<?php

class ElggRelationshipTest extends \PHPUnit_Framework_TestCase {

	public function testSettingAndGettingAttribute() {
		$obj = $this->getRelationshipMock();
		$obj->relationship = 'hasSister';
		$this->assertEquals('hasSister', $obj->relationship);
	}

	public function testGettingNonexistentAttribute() {
		$obj = $this->getRelationshipMock();
		$this->assertNull($obj->foo);
	}

	protected function getRelationshipMock() {
		// do not call constructor because it would cause deprecation warnings
		// and deprecation is not test-friendly yet.
		return $this->getMockForAbstractClass('\ElggRelationship', array(), '', false);
	}
}
