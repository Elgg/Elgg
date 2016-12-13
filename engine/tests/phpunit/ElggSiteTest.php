<?php

class ElggSiteTest extends \Elgg\TestCase {

	protected function setUp() {
		// required by \ElggEntity when setting the owner/container
		_elgg_services()->setValue('session', \ElggSession::getMock());
	}

	public function testCanConstructWithoutArguments() {
		$this->assertNotNull(new \ElggSite());
	}

	public function testGetNoreplyEmailAddress() {
		
		$site = new \ElggSite();
		$site->url = 'https://example.com/';
		
		$this->assertEquals('noreply@example.com', $site->getEmailAddress());
	}
	
	public function testGetEmailAddress() {
		
		$site = new \ElggSite();
		$site->url = 'https://example.com/';
		$site->email = 'someemail@example.com';
		
		$this->assertEquals('someemail@example.com', $site->getEmailAddress());
	}
}
