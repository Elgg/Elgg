<?php

/**
 * @group ElggEntity
 * @group ElggObject
 * @group UnitTests
 * @group ElggData
 */
class ElggObjectUnitTest extends \Elgg\UnitTestCase {

	public function up() {
		_elgg_services()->hooks->backup();
	}

	public function down() {
		_elgg_services()->hooks->restore();
	}

	public function testCanCommentOnGroupContent() {

		_elgg_groups_init();

		$user = $this->createUser();
		$user2 = $this->createUser();

		_elgg_services()->session->setLoggedInUser($user);

		$group = $this->createGroup([
			'owner_guid' => $user2->guid,
		]);

		$object = $this->createObject([
			'owner_guid' => $user2->guid,
			'container_guid' => $group->guid,
		]);

		$this->assertFalse($object->canComment());

		$this->assertTrue($group->join($user));

		$this->assertTrue($object->canComment());

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testCanConstructWithoutArguments() {
		$this->assertNotNull(new \ElggObject());
	}

	public function testCanSetDisplayName() {
		$object = $this->createObject();

		$name = 'Foo Bar';
		$object->setDisplayName($name);
		$this->assertEquals($name, $object->getDisplayName());
	}

	public function testCanSetVolatileData() {

		$object = $this->createObject();
		$object->setVolatileData('foo', 'bar');
		$this->assertEquals('bar', $object->getVolatileData('foo'));
	}

	public function testCanGetOriginalAttributes() {

		$object = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
		]);
		$object->access_id = ACCESS_PRIVATE;

		$this->assertEquals(['access_id' => ACCESS_PUBLIC], $object->getOriginalAttributes());
	}

	/**
	 * @group current
	 */
	public function testCanSaveNewObject() {

		$subtype = 'test_subtype';

		$user = $this->createUser();
		_elgg_services()->session->setLoggedInUser($user);
		
		$object = new \ElggObject();
		$object->subtype = $subtype;
		$object->title = 'Foo';
		$object->description = 'Bar';
		$object->owner_guid = $user->guid;
		$object->container_guid = $user->guid;
		$object->access_id = ACCESS_LOGGED_IN;
		$object->time_created = time();

		$object->setCurrentTime(); // We should be able to match timestamps
		$now = $object->getCurrentTime()->getTimestamp();

		$guid = $object->save();
		$this->assertNotFalse($guid);

		$object = get_entity($guid);

		$this->assertEquals('object', $object->type);
		$this->assertEquals($subtype, $object->subtype);

		$this->assertEquals('Foo', $object->title);
		$this->assertEquals('Foo', $object->getDisplayName());
		$this->assertEquals('Bar', $object->description);

		$this->assertEquals($user->guid, $object->getOwnerGUID());
		$this->assertEquals($user, $object->getOwnerEntity());
		$this->assertEquals($user->guid, $object->getContainerGUID());
		$this->assertEquals($user, $object->getContainerEntity());
		$this->assertEquals(ACCESS_LOGGED_IN, $object->access_id);

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testCanUpdateObject() {

		$user = $this->createUser();
		_elgg_services()->session->setLoggedInUser($user);

		$object = $this->createObject([
			'owner_guid' => $user->guid,
			'container_guid' => $user->guid,
			'access_id' => ACCESS_LOGGED_IN,
		]);
		$object->access_id = ACCESS_PUBLIC;
		$object->setCurrentTime();

		// Update river
		$dbprefix = _elgg_config()->dbprefix;
		$query = "
			UPDATE {$dbprefix}river
				SET access_id = :access_id
				WHERE object_guid = :object_guid
		";

		$params = [
			':access_id' => (int) $object->access_id,
			':object_guid' => (int) $object->guid,
		];

		_elgg_services()->db->addQuerySpec([
			'sql' => $query,
			'params' => $params,
			'row_count' => 1,
		]);



		$this->assertTrue($object->save());

		$object = get_entity($object->guid);
		$this->assertEquals(ACCESS_PUBLIC, $object->access_id);

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testCanCloneObject() {

		$user = $this->createUser();
		$object = $this->createObject([
			'owner_guid' => $user->guid,
			'container_guid' => $user->guid,
		]);
		
		$object->foo1 = 'bar1';
		$object->foo2 = ['foo2.1', 'foo2.2'];
		$object->save();

		$clone = clone $object;

		$this->assertEmpty($clone->guid);
		
		$this->assertNull($clone->time_created);
		$this->assertNull($clone->time_updated);
		$this->assertNull($clone->last_action);
		
		$this->assertEquals($object->title, $clone->title);
		$this->assertEquals($object->description, $clone->description);
		$this->assertEquals($object->foo1, $clone->foo1);
		$this->assertEquals($object->foo2, $clone->foo2);
	}

	public function testCanExportObject() {

		$object = $this->createObject([
			'title' => 'Foo',
			'description' => 'Bar',
		]);

		$prep = new \Elgg\Export\Entity();
		$prep->title = 'Foo';
		$prep->description = 'Bar';
		$prep->tags = [];
		$prep->guid = $object->guid;
		$prep->type = $object->getType();
		$prep->subtype = $object->getSubtype();
		$prep->owner_guid = $object->getOwnerGUID();
		$prep->container_guid = $object->getContainerGUID();
		$prep->time_created = date('c', $object->getTimeCreated());
		$prep->time_updated = date('c', $object->getTimeUpdated());
		$prep->url = $object->getURL();
		$prep->read_access = (int) $object->access_id;

		$this->assertEquals($prep, $object->toObject());
	}

	public function testCanNotCommentWhileLoggedOut() {

		$object = $this->createObject();
		$this->assertFalse($object->canComment());
	}

	public function testCanCommentWhenLoggedIn() {

		$user = $this->createUser();
		_elgg_services()->session->setLoggedInUser($user);

		$object = $this->createObject();
		$this->assertTrue($object->canComment());

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testCanAddRelationship() {

		$object = $this->createObject();
		$target = $this->createObject();

		$this->assertFalse(check_entity_relationship($object->guid, 'related', $target->guid));

		$object->addRelationship($target->guid, 'related');

		$this->assertInstanceOf(\ElggRelationship::class, check_entity_relationship($object->guid, 'related', $target->guid));

		$object->removeRelationship($target->guid, 'related');

		$this->assertFalse(check_entity_relationship($object->guid, 'related', $target->guid));
	}

	public function testCanSerialize() {
		$object = $this->createObject();

		$data = serialize($object);

		$unserialized = unserialize($data);

		$this->assertEquals($object, $unserialized);
	}

	public function testCanArrayAccessAttributes() {
		$object = $this->createObject();

		$this->assertEquals($object->guid, $object['guid']);

		foreach ($object as $attr => $value) {
			$this->assertEquals($object->$attr, $object[$attr]);
		}

		unset($object['access_id']);
	}

	public function testIsLoggable() {
		$object = $this->createObject();

		$this->assertEquals($object->guid, $object->getSystemLogID());
		$this->assertEquals($object, $object->getObjectFromID($object->guid));
	}
}
