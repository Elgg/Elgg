<?php

/**
 * @group UnitTests
 * @group ElggData
 */
class ElggGroupUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testCanConstructWithoutArguments() {
		$this->assertNotNull(new \ElggGroup());
	}

	public function testCanExport() {
		$group = $this->createGroup();

		$export = $group->toObject();

		$this->assertEquals($group->guid, $export->guid);
		$this->assertEquals($group->type, $export->type);
		$this->assertEquals($group->subtype, $export->subtype);
		$this->assertEquals($group->owner_guid, $export->owner_guid);
		$this->assertEquals($group->time_created, $export->getTimeCreated()->getTimestamp());
		$this->assertEquals($group->time_updated, $export->getTimeUpdated()->getTimestamp());
		$this->assertEquals($group->getURL(), $export->url);
	}

	public function testCanSerialize() {
		$group = $this->createGroup();

		$data = serialize($group);

		$unserialized = unserialize($data);

		$this->assertEquals($group, $unserialized);
	}

	public function testCanArrayAccessAttributes() {
		$group = $this->createGroup();

		$this->assertEquals($group->guid, $group['guid']);

		foreach ($group as $attr => $value) {
			$this->assertEquals($group->$attr, $group[$attr]);
		}

		unset($group['access_id']);
	}

	public function testIsLoggable() {
		$group = $this->createGroup();

		$this->assertEquals($group->guid, $group->getSystemLogID());
		$this->assertEquals($group, $group->getObjectFromID($group->guid));
	}
	
	public function testCantComment() {
		
		$group = $this->createGroup();
		
		$this->assertFalse($group->canComment());
		
		$user = $this->createUser();
		$session = _elgg_services()->session;
		
		$session->setLoggedInUser($user);
		
		$this->assertFalse($group->canComment());
		
		$session->removeLoggedInUser();
	}
}
