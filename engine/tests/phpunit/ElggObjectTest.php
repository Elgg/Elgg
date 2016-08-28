<?php

/**
 * @group ElggEntity
 * @group ElggObject
 */
class ElggObjectTest extends \Elgg\TestCase {

	protected function setUp() {
		$this->setupMockServices();

		_elgg_services()->hooks->backup();
	}

	protected function tearDown() {
		_elgg_services()->hooks->restore();
	}

	public function testCanConstructWithoutArguments() {
		$this->assertNotNull(new \ElggObject());
	}

	public function testCanSetDisplayName() {
		$object = $this->mocks()->getObject();

		$name = 'Foo Bar';
		$object->setDisplayName($name);
		$this->assertEquals($name, $object->getDisplayName());
	}

	public function testCanSetVolatileData() {

		$object = $this->mocks()->getObject();
		$object->setVolatileData('foo', 'bar');
		$this->assertEquals('bar', $object->getVolatileData('foo'));
	}

	public function testCanGetOriginalAttributes() {

		$object = $this->mocks()->getObject([
			'access_id' => ACCESS_PUBLIC,
		]);
		$object->access_id = ACCESS_PRIVATE;

		$this->assertEquals(['access_id' => ACCESS_PUBLIC], $object->getOriginalAttributes());
	}

	/**
	 * @group current
	 */
	public function testCanSaveNewObject() {

		// We can't effectively test this without objects table mock
		$this->markTestSkipped();

		$subtype = 'test_subtype';
		$subtype_id = add_subtype('object', $subtype);

		$user = $this->mocks()->getUser();
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

		$guid = _elgg_services()->entityTable->iterate();

		$object->save();

		$object = get_entity($guid);

		$this->assertEquals('object', $object->type);
		$this->assertEquals($subtype_id, $object->subtype);

		// These can't be tested for now, because we are not storing secondary attributes
		//$this->assertEquals('Foo', $object->title);
		//$this->assertEquals('Foo', $object->getDisplayName());
		//$this->assertEquals('Bar', $object->description);

		$this->assertEquals($user->guid, $object->getOwnerGUID());
		$this->assertEquals($user, $object->getOwnerEntity());
		$this->assertEquals($user->guid, $object->getContainerGUID());
		$this->assertEquals($user, $object->getContainerEntity());
		$this->assertEquals(ACCESS_LOGGED_IN, $object->access_id);

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testCanUpdateObject() {

		$user = $this->mocks()->getUser();
		_elgg_services()->session->setLoggedInUser($user);

		$object = $this->mocks()->getObject([
			'owner_guid' => $user->guid,
			'container_guid' => $user->guid,
			'access_id' => ACCESS_LOGGED_IN,
		]);
		$object->access_id = ACCESS_PUBLIC;
		$object->setCurrentTime();

		// Update river
		$dbprefix = elgg_get_config('dbprefix');
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

		$user = $this->mocks()->getUser();
		$object = $this->mocks()->getObject([
			'owner_guid' => $user->guid,
			'container_guid' => $user->guid,
		]);
		
		$object->foo1 = 'bar1';
		$object->foo2 = ['foo2.1', 'foo2.2'];
		$object->save();

		$clone = clone $object;

		$this->assertEmpty($clone->guid);
		$this->assertEquals($object->title, $clone->title);
		$this->assertEquals($object->description, $clone->description);
		$this->assertEquals($object->foo1, $clone->foo1);
		$this->assertEquals($object->foo2, $clone->foo2);
	}

	public function testCanExportObject() {

		$object = $this->mocks()->getObject([
			'title' => 'Foo',
			'description' => 'Bar',
		]);

		$prep = new \stdClass();
		$prep->title = 'Foo';
		$prep->description = 'Bar';
		$prep->tags = [];
		$prep->guid = $object->guid;
		$prep->type = $object->getType();
		$prep->subtype = $object->getSubtype();
		$prep->owner_guid = $object->getOwnerGUID();
		$prep->container_guid = $object->getContainerGUID();
		$prep->site_guid = (int) $object->site_guid;
		$prep->time_created = date('c', $object->getTimeCreated());
		$prep->time_updated = date('c', $object->getTimeUpdated());
		$prep->url = $object->getURL();
		$prep->read_access = (int) $object->access_id;

		$this->assertEquals($prep, $object->toObject());
	}

	public function testCanNotCommentWhileLoggedOut() {

		$object = $this->mocks()->getObject();
		$this->assertFalse($object->canComment());
	}

	public function testCanCommentWhenLoggedIn() {

		$user = $this->mocks()->getUser();
		_elgg_services()->session->setLoggedInUser($user);

		$object = $this->mocks()->getObject();
		$this->assertTrue($object->canComment());

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testCanCommentOnGroupContent() {

		_elgg_groups_init();

		$user = $this->mocks()->getUser();
		$user2 = $this->mocks()->getUser();

		_elgg_services()->session->setLoggedInUser($user);

		$group = $this->mocks()->getGroup([
			'owner_guid' => $user2->guid,
		]);

		$object = $this->mocks()->getObject([
			'owner_guid' => $user2->guid,
			'container_guid' => $group->guid,
		]);

		$this->assertFalse($object->canComment());

		$group->join($user);

		$this->assertTrue($object->canComment());

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testCanAddRelationship() {

		$object = $this->mocks()->getObject();
		$target = $this->mocks()->getObject();

		$this->assertFalse(check_entity_relationship($object->guid, 'related', $target->guid));

		$object->addRelationship($target->guid, 'related');

		$this->assertInstanceOf(\ElggRelationship::class, check_entity_relationship($object->guid, 'related', $target->guid));

		$object->removeRelationship($target->guid, 'related');

		$this->assertFalse(check_entity_relationship($object->guid, 'related', $target->guid));
	}

}
