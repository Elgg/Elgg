<?php

class ElggSiteTest extends \Elgg\TestCase {

	protected function setUp() {
		// required by \ElggEntity when setting the owner/container
		_elgg_services()->setValue('session', \ElggSession::getMock());
	}

	public function testCanConstructWithoutArguments() {
		$this->assertNotNull(new \ElggSite());
	}

	public function testSettingUrlHasNoEffect() {
		$site = new \ElggSite();

		$url = $site->url;

		_elgg_services()->logger->disable();
		$site->url = 'https://google.com/';
		$this->assertEquals($url, $site->url);

		$errors = _elgg_services()->logger->enable();
		$expected_error = [
			'message' => 'ElggSite::url cannot be set',
			'level' => 300,
		];
		$this->assertEquals($errors[0], $expected_error);
	}

	public function testNoreplyEmailAddressBasedOnUrl() {
		$site = new \ElggSite();
		// no email set

		/**
		 * @see \Elgg\TestCase::getTestingConfigArray Sets URL
		 */
		$this->assertEquals('noreply@localhost', $site->getEmailAddress());
	}
	
	public function testGetEmailAddress() {
		$site = new \ElggSite();
		$site->email = 'someemail@example.com';
		
		$this->assertEquals('someemail@example.com', $site->getEmailAddress());
	}
}
