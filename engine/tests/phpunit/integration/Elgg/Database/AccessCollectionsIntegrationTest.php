<?php

namespace Elgg\Database;

use Elgg\IntegrationTestCase;

class AccessCollectionsIntegrationTest extends IntegrationTestCase {

	/**
	 * @var AccessCollections
	 */
	protected $service;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->service = _elgg_services()->accessCollections;
	}

	public function testAccessPublicNotInWriteAccessArrayWhenInWalledGarden() {
		$user = $this->createUser();
		$config = _elgg_services()->config;
		
		// ensure walled garden is disabled
		$config->walled_garden = false;
		$write_access = $this->service->getWriteAccessArray($user->guid, true);
		
		$this->assertIsArray($write_access);
		$this->assertArrayHasKey(ACCESS_PUBLIC, $write_access);
		
		// enable walled garden
		$config->walled_garden = true;
		$write_access = $this->service->getWriteAccessArray($user->guid, true);
		
		$this->assertIsArray($write_access);
		$this->assertArrayNotHasKey(ACCESS_PUBLIC, $write_access);
	}
	
	public function testInvertedCreateUpdate() {
		$user = $this->createUser();
		$acl = new \ElggAccessCollection();
		$acl->owner_guid = $user->guid;
		$acl->name = 'foo';
		$acl->subtype = 'bar';
		
		$this->assertTrue($this->service->update($acl));
		$this->assertNotEmpty($acl->id);
		
		/* @var $loaded \ElggAccessCollection */
		$loaded = $this->service->get($acl->id);
		$this->assertInstanceOf(\ElggAccessCollection::class, $loaded);
		$this->assertEquals($acl->owner_guid, $loaded->owner_guid);
		$this->assertEquals($acl->name, $loaded->name);
		$this->assertEquals($acl->subtype, $loaded->subtype);
		
		$acl->subtype = 'foo';
		$acl->name = 'bar';
		
		$this->assertTrue($this->service->create($acl));
		
		/* @var $loaded \ElggAccessCollection */
		$loaded = $this->service->get($acl->id);
		$this->assertInstanceOf(\ElggAccessCollection::class, $loaded);
		$this->assertEquals($acl->owner_guid, $loaded->owner_guid);
		$this->assertEquals($acl->name, $loaded->name);
		$this->assertEquals($acl->subtype, $loaded->subtype);
	}

	/**
	 * Ignoring access permissions globally shouldn't affect the results
	 * when fetching the ACLs that user belongs to.
	 */
	public function testIgnoringAccessDoesntAffectFetchingReadACLs() {
		$user = $this->createUser();
		$user2 = $this->createUser();

		$acl = new \ElggAccessCollection();
		$acl->owner_guid = $user2->guid;
		$acl->name = 'foo';
		$acl->subtype = 'bar';
		$acl->save();
		$this->assertTrue($acl->addMember($user->guid));

		$acl2 = new \ElggAccessCollection();
		$acl2->owner_guid = $user2->guid;
		$acl2->name = 'foo2';
		$acl2->subtype = 'bar2';
		$acl2->save();
		
		$access_array = $this->service->getAccessArray($user->guid, true);
		$this->assertContains($acl->id, $access_array);
		$this->assertNotContains($acl2->id, $access_array);
		
		elgg_call(ELGG_IGNORE_ACCESS, function() use ($user, $acl, $acl2) {
			$access_array = $this->service->getAccessArray($user->guid, true);
			$this->assertContains($acl->id, $access_array);
			$this->assertNotContains($acl2->id, $access_array);
		});
	}

	/**
	 * Ignoring access permissions globally shouldn't affect the results
	 * when checking whether a specific user has read access to an entity.
	 */
	public function testIgnoringAccessDoesntAffectReadPermissionCheck() {
		$user = $this->createUser();
		$user2 = $this->createUser();

		$acl = new \ElggAccessCollection();
		$acl->owner_guid = $user2->guid;
		$acl->name = 'foo';
		$acl->subtype = 'bar';
		$acl->save();
		
		$entity = $this->createObject([
			'owner_guid' => $user2->guid,
			'access_id' => $acl->id,
		]);
		
		$this->assertFalse($this->service->hasAccessToEntity($entity, $user->guid));

		_elgg_services()->session_manager->setLoggedInUser($user);
		$this->assertFalse($this->service->hasAccessToEntity($entity, $user->guid));

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($entity, $user) {
			$this->assertFalse($this->service->hasAccessToEntity($entity, $user->guid));
		});
	}

	/**
	 * Fetching ACLs that a user belongs to should return the same results
	 * regardless if that particular user is logged in or not.
	 */
	public function testReadAclsAreCorrectForLoggedOutUser() {
		$user = $this->createUser();
		
		$acl = new \ElggAccessCollection();
		$acl->owner_guid = $user->guid;
		$acl->name = 'foo';
		$acl->subtype = 'bar';
		$acl->save();
		
		$access_array = $this->service->getAccessArray($user->guid, true);
		
		$this->assertContains($acl->id, $access_array);

		_elgg_services()->session_manager->setLoggedInUser($user);
		$access_array_logged_in = $this->service->getAccessArray($user->guid, true);
		$this->assertEquals($access_array, $access_array_logged_in);

		_elgg_services()->session_manager->removeLoggedInUser();
		$access_array_logged_out = $this->service->getAccessArray($user->guid, true);
		$this->assertEquals($access_array, $access_array_logged_out);
	}
}
