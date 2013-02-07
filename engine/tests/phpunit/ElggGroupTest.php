<?php

class ElggGroupTest extends PHPUnit_Framework_TestCase {
	
	function testCanConstructWithoutArguments() {
		$this->assertNotNull(new ElggGroup());
	}

}