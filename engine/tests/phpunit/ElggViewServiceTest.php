<?php

class ElggViewServiceTest extends PHPUnit_Framework_TestCase {
	
	public function testCanExtendViews() {
		$views = new ElggViewService();
				
		$views->extendView('foo', 'bar');
		
		// Unextending valid extension succeeds.
		$this->assertTrue($views->unextendView('foo', 'bar'));

		// Unextending non-existent extension "fails."
		$this->assertFalse($views->unextendView('foo', 'bar'));
	}   
}
