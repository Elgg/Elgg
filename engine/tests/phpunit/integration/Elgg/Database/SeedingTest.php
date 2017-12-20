<?php

namespace Elgg\Database;

use Elgg\IntegrationTestCase;

/**
 * @group IntegrationTests
 * @group Seeding
 */
class SeedingTest extends IntegrationTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testCanGetRandomUser() {
		$user = $this->createUser();

		$this->assertInstanceOf(\ElggUser::class, $this->getRandomUser());

		$user->delete();
	}
	public function testSeededUserDefaults() {

		$user = $this->createUser();

		$this->assertTrue(validate_email_address($user->email));
		$this->assertNotEmpty($user->name);
		$this->assertNotEmpty($user->username);
		$this->assertEquals(0, $user->owner_guid);
		$this->assertEquals(0, $user->container_guid);
		$this->assertEquals(ACCESS_PUBLIC, $user->access_id);
		$this->assertEquals('user', $user->getSubtype());
		$this->assertFalse($user->isAdmin());
		$this->assertFalse($user->isBanned());

		$user->delete();
	}

	public function testCanCreateUserWithSubtype() {
		$user = $this->createUser([
			'subtype' => 'test_subtype',
		]);

		$this->assertInstanceOf(\ElggUser::class, $user);
		$this->assertEquals('test_subtype', $user->getSubtype());

		$user->delete();
	}

	public function testCanCreateAdminUser() {

		$user = $this->createUser([], [
			'admin' => 'yes',
		]);

		$this->assertTrue($user->isAdmin());
		$this->assertTrue(get_entity($user->guid)->isAdmin());

		$user->invalidateCache();

		$this->assertTrue(get_entity($user->guid)->isAdmin());

		$user->delete();
	}

	public function testCanCreateBannedUser() {

		$user = $this->createUser([], [
			'banned' => true,
		]);

		$this->assertTrue($user->isBanned());
		$this->assertTrue(get_entity($user->guid)->isBanned());

		$user->invalidateCache();

		$this->assertTrue(get_entity($user->guid)->isBanned());

		$user->delete();
	}

	public function testCanSetUserLanguage() {

		$user = $this->createUser([], [
			'language' => 'de',
		]);

		$this->assertEquals('de', $user->language);
		$this->assertEquals('de', $user->getLanguage());
		$this->assertEquals('de', get_entity($user->guid)->getLanguage());

		$ia = elgg_set_ignore_access(true);
		$user->language = 'af';
		$this->assertTrue($user->save());
		elgg_set_ignore_access($ia);

		$this->assertEquals('af', $user->language);
		$this->assertEquals('af', $user->getLanguage());
		$this->assertEquals('af', get_entity($user->guid)->getLanguage());

		$user->invalidateCache();

		$this->assertEquals('af', get_entity($user->guid)->getLanguage());

	}

	public function testCanCreateGroup() {

		$group = $this->createOne('group');

		$this->assertNotEmpty($group->name);
		$this->assertNotEmpty($group->description);
		$this->assertTrue($group->owner_guid > 0);
		$this->assertTrue($group->container_guid > 0);
		$this->assertEquals(ACCESS_PUBLIC, $group->access_id);
		$this->assertNotEmpty($group->getSubtype());

		$this->assertEquals(\ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED, $group->getContentAccessMode());
		$this->assertTrue($group->isPublicMembership());

		$group->delete();
	}

	public function testCanCreateObject() {

		$object = $this->createObject();

		$this->assertNotEmpty($object->title);
		$this->assertNotEmpty($object->description);
		$this->assertTrue($object->owner_guid > 0);
		$this->assertTrue($object->container_guid > 0);
		$this->assertEquals(ACCESS_PUBLIC, $object->access_id);
		$this->assertNotEmpty($object->getSubtype());

		$object->delete();
	}
}