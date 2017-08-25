<?php

namespace Elgg\Cache;

/**
 * @group EntityCache
 * @group UnitTests
 */
class EntityCacheUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testCanGetEntityAfterDbQuery() {

		$this->assertFalse(elgg_get_session()->entityCache->get(0));

		$object = $this->createObject();

		elgg_get_session()->entityCache->clear();

		$object = get_entity($object->guid);

		$this->assertEquals($object, elgg_get_session()->entityCache->get($object->guid));
	}

	public function testCanCacheElggObject() {

		$object = $this->createObject();

		elgg_get_session()->entityCache->clear();

		elgg_get_session()->entityCache->set($object);

		$this->assertEquals($object, elgg_get_session()->entityCache->get($object->guid));

		elgg_get_session()->entityCache->remove($object->guid);

		$this->assertFalse(elgg_get_session()->entityCache->get($object->guid));
	}
	
	public function testCanCacheElggUser() {

		$user = $this->createUser();

		elgg_get_session()->entityCache->clear();

		elgg_get_session()->entityCache->set($user);

		$this->assertEquals($user, elgg_get_session()->entityCache->get($user->guid));
		$this->assertEquals($user, elgg_get_session()->entityCache->getByUsername($user->username));
		
		elgg_get_session()->entityCache->remove($user->guid);
		
		$this->assertFalse(elgg_get_session()->entityCache->get($user->guid));
		$this->assertFalse(elgg_get_session()->entityCache->getByUsername($user->username));

	}

	public function testCanDisableCacheForEntity() {

		$object = $this->createObject();

		elgg_get_session()->entityCache->clear();

		elgg_get_session()->entityCache->disableCachingForEntity($object->guid);

		elgg_get_session()->entityCache->set($object);

		$this->assertFalse(elgg_get_session()->entityCache->get($object->guid));

		elgg_get_session()->entityCache->enableCachingForEntity($object->guid);

		elgg_get_session()->entityCache->set($object);

		$this->assertEquals($object, elgg_get_session()->entityCache->get($object->guid));

	}

	public function testRemovesDeletedEntityFromCache() {

		$user = $this->createUser();

		elgg_get_session()->setLoggedInUser($user);

		$object = $this->createObject([
			'owner_guid' => $user->guid,
		]);

		$this->assertEquals($object, elgg_get_session()->entityCache->get($object->guid));

		$this->assertTrue($object->delete());
		
		$this->assertFalse(elgg_get_session()->entityCache->get($object->guid));

		elgg_get_session()->removeLoggedInUser();
	}

	public function testRemovesDisabledEntityFromCache() {

		$user = $this->createUser();

		elgg_get_session()->setLoggedInUser($user);

		$object = $this->createObject([
			'owner_guid' => $user->guid,
		]);

		$this->assertEquals($object, elgg_get_session()->entityCache->get($object->guid));

		$this->assertTrue($object->disable());

		$this->assertFalse(elgg_get_session()->entityCache->get($object->guid));

		elgg_get_session()->removeLoggedInUser();
	}

	public function testPopulatesCacheWithIgnoredAccess() {

		$ia = elgg_set_ignore_access(true);

		$user = $this->createUser();

		elgg_get_session()->setLoggedInUser($user);

		$object = $this->createObject([
			'owner_guid' => $user->guid,
			'access_id' => ACCESS_PRIVATE,
		]);

		$this->assertEquals($object, elgg_get_session()->entityCache->get($object->guid));

		$this->assertEquals($object, get_entity($object->guid));

		elgg_get_session()->removeLoggedInUser();

		elgg_set_ignore_access($ia);

		$this->assertFalse(get_entity($object->guid));

	}

	public function testVInvalidatesCacheOnSessionChange() {

		$user = $this->createUser();

		elgg_get_session()->setLoggedInUser($user);

		$object = $this->createObject([
			'owner_guid' => $user->guid,
			'access_id' => ACCESS_PRIVATE,
		]);

		$this->assertEquals($object, elgg_get_session()->entityCache->get($object->guid));

		$this->assertEquals($object, get_entity($object->guid));

		elgg_get_session()->removeLoggedInUser();

		$this->assertFalse(elgg_get_session()->entityCache->get($object->guid));

		$this->assertFalse(get_entity($object->guid));
	}

	public function testPropagatesLastActionChangesToCache() {

		_elgg_services()->entityTable->setCurrentTime();

		$time = strtotime('-1 day');

		$object = $this->createObject([
			'time_created' => $time,
			'time_updated' => $time,
			'last_action' => $time,
		]);
		/** @var \ElggObject $object */
		
		$this->assertEquals($object, elgg_get_session()->entityCache->get($object->guid));

		$posted = $object->updateLastAction();

		$object = get_entity($object->guid);
		
		$this->assertNotEquals($posted, $time);
		$this->assertEquals($posted, $object->last_action);
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
