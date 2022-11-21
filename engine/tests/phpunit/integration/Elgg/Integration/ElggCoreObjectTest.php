<?php

namespace Elgg\Integration;

use Elgg\Database\Select;
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
		elgg()->session_manager->setLoggedInUser($this->user);
		
		$this->subtype = $this->getRandomSubtype();
		$this->entity = new ElggObjectWithExposableAttributes();
		$this->entity->setSubtype($this->subtype);
	}

	public function down() {
		if ($this->entity) {
			$this->entity->delete();
		}
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
		$tagarray = elgg_string_to_array($tag_string);
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
		$group = $this->createGroup();
		$guid = $group->guid;
		$this->entity->setContainerGUID($guid);

		// check container
		$this->assertEquals($guid, $this->entity->getContainerGUID());
		$this->assertEquals($group, $this->entity->getContainerEntity());
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
		$e1 = $this->createObject();
		$guid1 = $e1->guid;

		$e2 = $this->createObject([
			'container_guid' => $guid1,
		]);

		$guid2 = $e2->guid;

		// fake being logged out
		elgg()->session_manager->removeLoggedInUser();
		
		elgg_call(ELGG_IGNORE_ACCESS, function() use ($e1) {
			$this->assertTrue($e1->disable('', true));
		});
		
		// restore logged in user
		elgg()->session_manager->setLoggedInUser($this->user);
		
		$this->assertEmpty(get_entity($guid1));
		$this->assertEmpty(get_entity($guid2));

		$select1 = Select::fromTable('entities')->select('*');
		$select1->where($select1->compare('guid', '=', $guid1, ELGG_VALUE_GUID));
		
		$r = elgg()->db->getDataRow($select1);
		$this->assertEquals('no', $r->enabled);

		$select2 = Select::fromTable('entities')->select('*');
		$select2->where($select2->compare('guid', '=', $guid2, ELGG_VALUE_GUID));
		
		$r = elgg()->db->getDataRow($select2);
		$this->assertEquals('no', $r->enabled);
	}

	public function testElggRecursiveDelete() {
		$types = ['group', 'object', 'user'];
		
		foreach ($types as $type) {
			$parent = $this->createOne($type);
			
			$child = $this->createObject([
				'owner_guid' => $parent->guid,
				'container_guid' => 1,
			]);

			$child2 = $this->createObject([
				'owner_guid' => $parent->guid,
				'container_guid' => $parent->guid,
			]);

			$grandchild = $this->createObject([
				'container_guid' => $child->guid,
				'owner_guid' => 1,
			]);

			elgg_call(ELGG_IGNORE_ACCESS, function() use ($parent) {
				$this->assertTrue($parent->delete(true));
			});

			foreach ([$parent->guid, $child->guid, $child2->guid, $grandchild->guid] as $guid) {
				$entities = Select::fromTable('entities')->select('*');
				$entities->where($entities->compare('guid', '=', $guid, ELGG_VALUE_GUID));
				
				$this->assertEmpty(elgg()->db->getData($entities));
			}
		}

		// object that owns itself
		// can't check container_guid because of infinite loops in can_edit_entity()
		$obj = $this->createObject();
		$obj->owner_guid = $obj->guid;
		$obj->save();

		$entities = Select::fromTable('entities')->select('*');
		$entities->where($entities->compare('guid', '=', $obj->guid, ELGG_VALUE_GUID));
				
		$r = elgg()->db->getDataRow($entities);
		$this->assertEquals($obj->guid, $r->owner_guid);

		$this->assertTrue($obj->delete(true));

		$this->assertEmpty(elgg()->db->getDataRow($entities));
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
	}

	protected function get_entity_row($guid) {
		$select = Select::fromTable('entities')->select('*');
		$select->where($select->compare('guid', '=', $guid, ELGG_VALUE_GUID));

		return elgg()->db->getDataRow($select);
	}
}
