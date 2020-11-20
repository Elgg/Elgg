<?php

namespace Elgg\Cache;
use Elgg\Values;

/**
 * @group EntityCache
 * @group Cache
 * @group UnitTests
 */
class EntityCacheUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testCanGetEntityAfterDbQuery() {

		$this->assertNull(_elgg_services()->entityCache->load(0));

		$object = $this->createObject();

		$object->invalidateCache();

		$object = get_entity($object->guid);

		$this->assertEquals($object, _elgg_services()->entityCache->load($object->guid));
	}

	public function testCanCacheElggObject() {

		$object = $this->createObject();

		$object->invalidateCache();

		$object->cache();

		$this->assertEquals($object, _elgg_services()->entityCache->load($object->guid));

		$object->invalidateCache();

		$this->assertNull(_elgg_services()->entityCache->load($object->guid));
	}
	
	public function testCanCacheElggUser() {

		$user = $this->createUser();

		$user->invalidateCache();

		$user->cache();

		$this->assertEquals($user, _elgg_services()->entityCache->load($user->guid));
		
		$user->invalidateCache();

		$this->assertNull(_elgg_services()->entityCache->load($user->guid));
	}
	
	public function testCacheUserByUsernameCaseInsensitive() {
		$user = $this->createUser([
			'username' => 'CaseInsensitiveTestUser',
		]);
		
		$user->invalidateCache();
		
		$user->cache();
		
		$this->assertEquals($user, _elgg_services()->entityCache->load($user->guid));
		
		$user->invalidateCache();

		$this->assertNull(_elgg_services()->entityCache->load($user->guid));
		
		$user->delete();
	}

	public function testCanDisableCacheForEntity() {

		$object = $this->createObject();

		$object->invalidateCache();

		$object->disableCaching();

		$object->cache();

		$this->assertNull(_elgg_services()->entityCache->load($object->guid));

		$object->enableCaching();

		$object->cache();

		$this->assertEquals($object, _elgg_services()->entityCache->load($object->guid));

	}

	public function testRemovesDeletedEntityFromCache() {

		$user = $this->createUser();

		_elgg_services()->session->setLoggedInUser($user);

		$object = $this->createObject([
			'owner_guid' => $user->guid,
		]);

		$this->assertEquals($object, _elgg_services()->entityCache->load($object->guid));

		$this->assertTrue($object->delete());

		$this->assertNull(_elgg_services()->entityCache->load($object->guid));

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testRemovesDisabledEntityFromCache() {

		$user = $this->createUser();

		_elgg_services()->session->setLoggedInUser($user);

		$object = $this->createObject([
			'owner_guid' => $user->guid,
		]);

		$this->assertEquals($object, _elgg_services()->entityCache->load($object->guid));

		$this->assertTrue($object->disable());

		$this->assertNull(_elgg_services()->entityCache->load($object->guid));

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testBypassesCacheWithIgnoredAccess() {

		$user = $this->createUser();
		$object = null;
		
		_elgg_services()->session->setLoggedInUser($user);

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($user, &$object) {
		
			$object = $this->createObject([
				'owner_guid' => $user->guid,
				'access_id' => ACCESS_PRIVATE,
			]);
	
			$this->assertNull(_elgg_services()->entityCache->load($object->guid));
	
			$this->assertEquals($object, get_entity($object->guid));
	
			_elgg_services()->session->removeLoggedInUser();
	
			$this->assertEquals($object, get_entity($object->guid));
			
			return $object;
		});
		
		$this->assertNull(_elgg_services()->entityCache->load($object->guid));

		$this->assertFalse(get_entity($object->guid));

	}

	public function testVInvalidatesCacheOnSessionChange() {

		$user = $this->createUser();

		_elgg_services()->session->setLoggedInUser($user);

		$object = $this->createObject([
			'owner_guid' => $user->guid,
			'access_id' => ACCESS_PRIVATE,
		]);

		$this->assertEquals($object, _elgg_services()->entityCache->load($object->guid));

		$this->assertEquals($object, get_entity($object->guid));

		_elgg_services()->session->removeLoggedInUser();

		$this->assertNull(_elgg_services()->entityCache->load($object->guid));

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
		
		$this->assertEquals($object, _elgg_services()->entityCache->load($object->guid));

		$posted = $object->updateLastAction();

		$object = get_entity($object->guid);
		
		$this->assertNotEquals($posted, $time);
		$this->assertEquals($posted, $object->last_action);
	}

	public function testRemovesBannedUserFromCache() {
		$user = $this->createUser();

		elgg_register_plugin_hook_handler('permissions_check', 'user', [Values::class, 'getTrue']);

		$user = get_entity($user->guid);
		/* @var $user \ElggUser */

		$this->assertEquals($user, _elgg_services()->entityCache->load($user->guid));

		$user->ban();

		$this->assertNull(_elgg_services()->entityCache->load($user->guid));

		elgg_unregister_plugin_hook_handler('permissions_check', 'user', [Values::class, 'getTrue']);
	}

	public function testRemovesUnbannedUserFromCache() {
		$user = $this->createUser([], [
			'banned' => 'yes',
		]);

		elgg_register_plugin_hook_handler('permissions_check', 'user', [Values::class, 'getTrue']);

		$user = get_entity($user->guid);

		$this->assertEquals($user, _elgg_services()->entityCache->load($user->guid));

		$user->unban();

		$this->assertNull(_elgg_services()->entityCache->load($user->guid));

		elgg_unregister_plugin_hook_handler('permissions_check', 'user', [Values::class, 'getTrue']);
	}

	public function testRemovesNewAdminUserFromCache() {
		$user = $this->createUser();

		$user = get_entity($user->guid);
		/* @var $user \ElggUser */

		$this->assertEquals($user, _elgg_services()->entityCache->load($user->guid));

		$user->makeAdmin();

		$this->assertNull(_elgg_services()->entityCache->load($user->guid));
	}

	public function testRemovesRemovedAdminUserFromCache() {
		$user = $this->createUser([], [
			'admin' => 'yes',
		]);

		$user = get_entity($user->guid);

		$this->assertEquals($user, _elgg_services()->entityCache->load($user->guid));

		$user->removeAdmin();

		$this->assertNull(_elgg_services()->entityCache->load($user->guid));
	}
	
	

}
