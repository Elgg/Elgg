<?php

namespace Elgg\Database;

class AccessCollectionsUnitTest extends \Elgg\UnitTestCase {
	/**
	 * @var AccessCollections
	 */
	protected $service;
	
	public function up() {
		$this->service = _elgg_services()->accessCollections;
	}

	/**
	 * Getting the readable name of a default access_id should return the expected value.
	 */
	public function testAclReadableNameForDefaultAccessID() {
		$this->assertEquals(elgg_echo('access:label:private'), $this->service->getReadableAccessLevel(ACCESS_PRIVATE));
	}

	/**
	 * Getting the readable name of a custom access_id should return the expected value.
	 */
	public function testAclReadableNameForCustomAccessID() {
		$user = $this->createUser();
		
		$acl = new \ElggAccessCollection();
		$acl->name = 'foo';
		$acl->owner_guid = $user->guid;
		
		$this->service->create($acl);
		
		$this->assertIsInt($acl->id);
		$this->assertEquals($acl->owner_guid, $user->guid);
		
		$this->assertEquals(elgg_echo('access:limited:label'), $this->service->getReadableAccessLevel($acl->id));

		_elgg_services()->session_manager->setLoggedInUser($user);
		
		$this->assertEquals('foo', $this->service->getReadableAccessLevel($acl->id));
	}
}
