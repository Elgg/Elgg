<?php

/**
 * @group UnitTests
 */
class ElggSiteUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

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
		$domain = $site->getDomain();
		$this->assertEquals("noreply@$domain", $site->getEmailAddress());
	}
	
	public function testGetEmailAddress() {
		$site = new \ElggSite();
		$site->email = 'someemail@example.com';
		
		$this->assertEquals('someemail@example.com', $site->getEmailAddress());
	}
}
