<?php

namespace Elgg\Cache;

/**
 * @group Entities
 */
class EntityCacheTest extends \Elgg\TestCase {

	protected function setUp() {
		$this->setupTestingServices();
		$this->setupMockServices();
	}

	public function testCanGetEntityAfterDbQuery() {

		$this->assertFalse(_elgg_services()->entityCache->get(0));

		$object = $this->mocks()->getObject();

		_elgg_services()->entityCache->clear();

		$object = get_entity($object->guid);

		$this->assertEquals($object, _elgg_services()->entityCache->get($object->guid));
	}

	public function testCanCacheElggObject() {

		$object = $this->mocks()->getObject();

		_elgg_services()->entityCache->clear();

		_elgg_services()->entityCache->set($object);

		$this->assertEquals($object, _elgg_services()->entityCache->get($object->guid));

		_elgg_services()->entityCache->remove($object->guid);

		$this->assertFalse(_elgg_services()->entityCache->get($object->guid));
	}
	
	public function testCanCacheElggUser() {

		$user = $this->mocks()->getUser();

		_elgg_services()->entityCache->clear();

		_elgg_services()->entityCache->set($user);

		$this->assertEquals($user, _elgg_services()->entityCache->get($user->guid));
		$this->assertEquals($user, _elgg_services()->entityCache->getByUsername($user->username));
		
		_elgg_services()->entityCache->remove($user->guid);
		
		$this->assertFalse(_elgg_services()->entityCache->get($user->guid));
		$this->assertFalse(_elgg_services()->entityCache->getByUsername($user->username));

	}

	public function testCanDisableCacheForEntity() {

		$object = $this->mocks()->getObject();

		_elgg_services()->entityCache->clear();

		_elgg_services()->entityCache->disableCachingForEntity($object->guid);

		_elgg_services()->entityCache->set($object);

		$this->assertFalse(_elgg_services()->entityCache->get($object->guid));

		_elgg_services()->entityCache->enableCachingForEntity($object->guid);

		_elgg_services()->entityCache->set($object);

		$this->assertEquals($object, _elgg_services()->entityCache->get($object->guid));

	}

	public function testRemovesDeletedEntityFromCache() {

		$user = $this->mocks()->getUser();
		$object = $this->mocks()->getObject([
			'owner_guid' => $user->guid,
		]);

		_elgg_services()->session->setLoggedInUser($user);

		$this->assertEquals($object, _elgg_services()->entityCache->get($object->guid));

		$this->assertTrue($object->delete());
		
		$this->assertFalse(_elgg_services()->entityCache->get($object->guid));

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testRemovesDisabledEntityFromCache() {

		$user = $this->mocks()->getUser();
		$object = $this->mocks()->getObject([
			'owner_guid' => $user->guid,
		]);

		_elgg_services()->session->setLoggedInUser($user);

		$this->assertEquals($object, _elgg_services()->entityCache->get($object->guid));

		$this->assertTrue($object->disable());

		$this->assertFalse(_elgg_services()->entityCache->get($object->guid));

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testRemovesEntityFromCacheAfterLastActionChange() {
		// EntityTable::updateLastAction() should invalidate cache
		$this->markTestSkipped();
	}

	public function testRemovesBannedUserFromCache() {
		// UsersTable::ban() should invalidate cache
		$this->markTestSkipped();
	}

	public function testRemovesUnbannedUserFromCache() {
		// UsersTable::unban() should invalidate cache
		$this->markTestSkipped();
	}

	public function testRemovesNewAdminUserFromCache() {
		// UsersTable::makeAdmin() should invalidate cache
		$this->markTestSkipped();
	}

	public function testRemovesRemovedAdminUserFromCache() {
		// UsersTable::removeAdmin() should invalidate cache
		$this->markTestSkipped();
	}
	
	

}
