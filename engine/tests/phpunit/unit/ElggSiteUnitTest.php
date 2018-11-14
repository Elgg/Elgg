<?php

/**
 * @group UnitTests
 * @group ElggData
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
			'level' => 'warning',
		];
		$this->assertEquals($errors[0], $expected_error);
	}

	public function testNoreplyEmailAddressBasedOnUrl() {
		$site = new \ElggSite();
		$domain = $site->getDomain();
		$this->assertRegExp("/noreply[\w-]+@{$domain}/", $site->getEmailAddress());
	}
	
	public function testGetEmailAddress() {
		$site = new \ElggSite();
		$site->email = 'someemail@example.com';
		
		$this->assertEquals('someemail@example.com', $site->getEmailAddress());
	}

	public function testCanExport() {
		$site = elgg_get_site_entity();

		$export = $site->toObject();

		$this->assertEquals($site->guid, $export->guid);
		$this->assertEquals($site->type, $export->type);
		$this->assertEquals($site->subtype, $export->subtype);
		$this->assertEquals($site->owner_guid, $export->owner_guid);
		$this->assertEquals($site->time_created, $export->getTimeCreated()->getTimestamp());
		$this->assertEquals($site->getURL(), $export->url);
	}

	public function testCanSerialize() {
		$site = elgg_get_site_entity();

		$data = serialize($site);

		$unserialized_site = unserialize($data);

		$this->assertEquals($site, $unserialized_site);
	}

	public function testCanArrayAccessAttributes() {
		$site = elgg_get_site_entity();

		$this->assertEquals($site->guid, $site['guid']);

		foreach ($site as $attr => $value) {
			$this->assertEquals($site->$attr, $site[$attr]);
		}

		unset($site['access_id']);
	}

	public function testIsLoggable() {
		$site = elgg_get_site_entity();

		$this->assertEquals($site->guid, $site->getSystemLogID());
		$this->assertEquals($site, $site->getObjectFromID($site->guid));
	}
}
