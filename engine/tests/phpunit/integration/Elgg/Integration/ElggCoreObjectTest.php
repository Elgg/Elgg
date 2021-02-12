<?php

namespace Elgg\Integration;

use Elgg\Helpers\ElggObjectWithExposableAttributes;

/**
 * Elgg Test \ElggObject
 *
 * @group IntegrationTests
 * @group ElggObject
 * @group Tags
 */
class ElggCoreObjectTest extends \Elgg\IntegrationTestCase {

	/**
	 * @var \ElggEntity
	 */
	protected $entity;

	/**
	 * @var string
	 */
	protected $subtype;
	
	/**
	 *
	 * @var \ElggUser
	 */
	protected $user;

	public function up() {
		$this->user = $this->createUser();
		elgg()->session->setLoggedInUser($this->user);
		
		$this->subtype = $this->getRandomSubtype();
		$this->entity = new ElggObjectWithExposableAttributes();
		$this->entity->setSubtype($this->subtype);
	}

	public function down() {
		
		if ($this->entity) {
			$this->entity->delete();
		}
		
		if ($this->user) {
			$this->user->delete();
		}
		
		elgg()->session->removeLoggedInUser();
	}

	public function testElggObjectConstructor() {
		$attributes = [];
		$attributes['guid'] = null;
		$attributes['type'] = 'object';
		$attributes['subtype'] = $this->subtype;
		$attributes['owner_guid'] = elgg_get_logged_in_user_guid();
		$attributes['container_guid'] = elgg_get_logged_in_user_guid();
		$attributes['access_id'] = ACCESS_PRIVATE;
		$attributes['time_created'] = null;
		$attributes['time_updated'] = null;
		$attributes['last_action'] = null;
		$attributes['enabled'] = 'yes';
		ksort($attributes);

		$entity_attributes = $this->entity->expose_attributes();
		ksort($entity_attributes);

		$this->assertEquals($attributes, $entity_attributes);
	}

	public function testElggObjectSave() {
		// new object
		$this->assertEquals(0, $this->entity->guid);
		$this->assertTrue($this->entity->save());
		$this->assertGreaterThan(0, $this->entity->guid);

		$entity_row = $this->get_entity_row($this->entity->guid);
		$this->assertInstanceOf(\stdClass::class, $entity_row);

		// update existing object
		$this->entity->title = 'testing';
		$this->entity->description = '\ElggObject';
		$this->assertTrue($this->entity->save());
	}

	public function testElggObjectClone() {
		$this->entity->title = 'testing';
		$this->entity->description = '\ElggObject';
		$this->entity->var1 = "test";
		$this->entity->var2 = 1;
		$this->entity->var3 = true;
		$this->entity->save();

		// add tag array
		$tag_string = 'tag1, tag2, tag3';
		$tagarray = string_to_tag_array($tag_string);
		$this->entity->tags = $tagarray;

		// a cloned \ElggEntity has the guid reset
		$object = clone $this->entity;
		$this->assertEquals(0, $object->guid);

		// make sure attributes were copied over
		$this->assertEquals('testing', $object->title);
		$this->assertEquals('\ElggObject', $object->description);

		$this->assertTrue($object->save());
		$this->assertGreaterThan(0, $object->guid);
		$this->assertNotEquals($this->entity->guid, $object->guid);

		// test that metadata was transfered
		$this->assertEquals($this->entity->var1, $object->var1);
		$this->assertEquals($this->entity->var2, $object->var2);
		$this->assertEquals($this->entity->var3, $object->var3);
		$this->assertEquals($this->entity->tags, $object->tags);

		// clean up
		$object->delete();
	}

	public function testElggObjectContainer() {
		$this->assertEquals($this->entity->getContainerGUID(), elgg_get_logged_in_user_guid());

		// create and save to group
		$group = new \ElggGroup();
		$this->assertTrue($group->save());
		$guid = $group->guid;
		$this->assertIsInt($this->entity->setContainerGUID($guid));

		// check container
		$this->assertEquals($guid, $this->entity->getContainerGUID());
		$this->assertEquals($group, $this->entity->getContainerEntity());

		// clean up
		$group->delete();
	}

	public function testElggObjectToObject() {
		$keys = [
			'guid',
			'type',
			'subtype',
			'time_created',
			'time_updated',
			'container_guid',
			'owner_guid',
			'url',
			'read_access',
			'title',
			'description',
			'tags',
		];
		sort($keys);
		
		$object = $this->entity->toObject();
		$object_keys = array_keys($object->getArrayCopy());
		sort($object_keys);
		
		$this->assertEquals($keys, $object_keys);
	}

	/**
	 * @see https://github.com/elgg/elgg/issues/1196
	 */
	public function testElggEntityRecursiveDisableWhenLoggedOut() {
		$e1 = new \ElggObject();
		$e1->setSubtype($this->getRandomSubtype());
		$e1->access_id = ACCESS_PUBLIC;
		$e1->save();
		$guid1 = $e1->guid;

		$e2 = new \ElggObject();
		$e2->setSubtype($this->getRandomSubtype());
		$e2->container_guid = $guid1;
		$e2->access_id = ACCESS_PUBLIC;
		$e2->owner_guid = 0;
		$e2->save();
		$guid2 = $e2->guid;

		// fake being logged out
		elgg()->session->removeLoggedInUser();
		
		elgg_call(ELGG_IGNORE_ACCESS, function() use ($e1) {
			$this->assertTrue($e1->disable(null, true));
		});
		
		// restore logged in user
		elgg()->session->setLoggedInUser($this->user);
		
		$this->assertEmpty(get_entity($guid1));
		$this->assertEmpty(get_entity($guid2));

		$db_prefix = _elgg_services()->config->dbprefix;
		$q = "SELECT * FROM {$db_prefix}entities WHERE guid = $guid1";
		$r = elgg()->db->getDataRow($q);
		$this->assertEquals('no', $r->enabled);

		$q = "SELECT * FROM {$db_prefix}entities WHERE guid = $guid2";
		$r = elgg()->db->getDataRow($q);
		$this->assertEquals('no', $r->enabled);

		elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($e1, $e2) {
			$e1->delete();
			$e2->delete();
		});
	}

	public function testElggRecursiveDelete() {
		$types = ['group', 'object', 'user'];
		$db_prefix = _elgg_services()->config->dbprefix;

		foreach ($types as $type) {
			$parent = $this->createOne($type);
			
			$child = $this->createOne('object', [
				'owner_guid' => $parent->guid,
				'container_guid' => 1,
			]);

			$child2 = $this->createOne('object', [
				'owner_guid' => $parent->guid,
				'container_guid' => $parent->guid,
			]);

			$grandchild = $this->createOne('object', [
				'container_guid' => $child->guid,
				'owner_guid' => 1,
			]);

			elgg_call(ELGG_IGNORE_ACCESS, function() use ($parent) {
				$this->assertTrue($parent->delete(true));
			});

			$q = "SELECT * FROM {$db_prefix}entities WHERE guid = {$parent->guid}";
			$r = elgg()->db->getData($q);
			$this->assertEmpty($r);

			$q = "SELECT * FROM {$db_prefix}entities WHERE guid = {$child->guid}";
			$r = elgg()->db->getData($q);
			$this->assertEmpty($r);

			$q = "SELECT * FROM {$db_prefix}entities WHERE guid = {$child2->guid}";
			$r = elgg()->db->getData($q);
			$this->assertEmpty($r);

			$q = "SELECT * FROM {$db_prefix}entities WHERE guid = {$grandchild->guid}";
			$r = elgg()->db->getData($q);
			$this->assertEmpty($r);
		}

		// object that owns itself
		// can't check container_guid because of infinite loops in can_edit_entity()
		$obj = new \ElggObject();
		$obj->setSubtype($this->getRandomSubtype());
		$obj->save();
		$obj->owner_guid = $obj->guid;
		$obj->save();

		$q = "SELECT * FROM {$db_prefix}entities WHERE guid = {$obj->guid}";
		$r = elgg()->db->getDataRow($q);
		$this->assertEquals($obj->guid, $r->owner_guid);

		$this->assertTrue($obj->delete(true));

		$q = "SELECT * FROM {$db_prefix}entities WHERE guid = {$obj->guid}";
		$r = elgg()->db->getDataRow($q);
		$this->assertEmpty($r);
	}

	public function testCanGetTags() {

		$subtype = $this->getRandomSubtype();
		$objects = $this->createMany('object', 3, [
			'subtype' => $subtype,
		]);

		$objects[0]->foo1 = 'one';
		$objects[0]->foo2 = 'two';
		$objects[0]->foo4 = 'four';

		$objects[1]->foo1 = 'one';
		$objects[1]->foo2 = 'two';
		$objects[1]->foo3 = 'three';
		$objects[1]->foo4 = 'four';

		$objects[2]->foo1 = 'one';
		$objects[2]->foo2 = '';
		$objects[2]->foo3 = '';
		$objects[2]->foo4 = 'four';

		$expected = [
			(object) [
				'tag' => 'one',
				'total' => 3,
			],
			(object) [
				'tag' => 'two',
				'total' => 2,
			],
			(object) [
				'tag' => 'three',
				'total' => 1,
			],
		];

		$actual = elgg_get_tags([
			'types' => 'object',
			'subtypes' => $subtype,
			'tag_names' => ['foo1', 'foo2', 'foo3'],
		]);

		$this->assertEquals($expected, $actual);

		foreach ($objects as $object) {
			$object->delete();
		}
	}

	protected function get_entity_row($guid) {
		$CONFIG = _elgg_services()->config;

		return elgg()->db->getDataRow("SELECT * FROM {$CONFIG->dbprefix}entities WHERE guid='{$guid}'");
	}
}
