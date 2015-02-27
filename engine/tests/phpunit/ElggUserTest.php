<?php

class ElggUserTest extends \PHPUnit_Framework_TestCase {

	protected function setUp() {
		// required by \ElggEntity when setting the owner/container
		_elgg_services()->setValue('session', \ElggSession::getMock());
	}
	
	public function testCanConstructWithoutArguments() {
		$this->assertNotNull(new \ElggUser());
	}

	public function testSettingUnsettableAttributes() {
		$obj = new \ElggUser();
		foreach (array('prev_last_action', 'last_login', 'prev_last_login') as $name) {
			$obj->$name = 'foo';
			$this->assertNotEquals('foo', $obj->$name);			
		}		
	}

}