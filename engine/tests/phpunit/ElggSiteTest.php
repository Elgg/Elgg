<?php

class ElggSiteTest extends PHPUnit_Framework_TestCase {
	
	function testCanConstructWithoutArguments() {
		$this->assertNotNull(new ElggSite());
	}

}