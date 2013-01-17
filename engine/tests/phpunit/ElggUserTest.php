<?php

class ElggUserTest extends PHPUnit_Framework_TestCase {
	
	function testCanConstructWithoutArguments() {
		$this->assertNotNull(new ElggUser());
	}

}