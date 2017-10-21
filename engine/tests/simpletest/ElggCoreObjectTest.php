<?php
/**
 * Elgg Test \ElggObject
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreObjectTest extends \ElggCoreUnitTest {

	protected $subtype;

	public function up() {
		$this->subtype = $this->getRandomSubtype();
		$this->entity = new \ElggObjectWithExposableAttributes();
		$this->entity->subtype = $this->subtype;
	}

	public function down() {
		unset($this->entity);
	}

	public function testElggObjectConstructor() {
		$attributes = array();
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

		$this->assertIdentical($entity_attributes, $attributes);
	}

	public function testElggObjectSave() {
		// new object
		$this->assertEqual($this->entity->getGUID(), 0);
		$guid = $this->entity->save();
		$this->assertNotEqual($guid, 0);

		$entity_row = $this->get_entity_row($guid);
		$this->assertIsA($entity_row, '\stdClass');

		// update existing object
		$this->entity->title = 'testing';
		$this->entity->description = '\ElggObject';
		$this->assertEqual($this->entity->save(), $guid);

		// clean up
		$this->entity->delete();
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
		$this->assertIdentical(0, (int)$object->guid);

		// make sure attributes were copied over
		$this->assertIdentical($object->title, 'testing');
		$this->assertIdentical($object->description, '\ElggObject');

		$guid = $object->save();
		$this->assertTrue($guid !== 0);
		$this->assertTrue($guid !== $this->entity->guid);

		// test that metadata was transfered
		$this->assertIdentical($this->entity->var1, $object->var1);
		$this->assertIdentical($this->entity->var2, $object->var2);
		$this->assertIdentical($this->entity->var3, $object->var3);
		$this->assertIdentical($this->entity->tags, $object->tags);

		// clean up
		$object->delete();
		$this->entity->delete();
	}

	public function testElggObjectContainer() {
		$this->assertEqual($this->entity->getContainerGUID(), elgg_get_logged_in_user_guid());

		// create and save to group
		$group = new \ElggGroup();
		$guid = $group->save();
		$this->assertTrue($this->entity->setContainerGUID($guid));

		// check container
		$this->assertEqual($this->entity->getContainerGUID(), $guid);
		$this->assertIdenticalEntities($group, $this->entity->getContainerEntity());

		// clean up
		$group->delete();
	}

	public function testElggObjectToObject() {
		$keys = array(
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
		);

		$object = $this->entity->toObject();
		$object_keys = array_keys(get_object_vars($object));
		sort($keys);
		sort($object_keys);
		$this->assertIdentical($keys, $object_keys);
	}

	// see https://github.com/elgg/elgg/issues/1196
	public function testElggEntityRecursiveDisableWhenLoggedOut() {
		$e1 = new \ElggObject();
		$e1->subtype = $this->getRandomSubtype();
		$e1->access_id = ACCESS_PUBLIC;
		$e1->owner_guid = 0;
		$e1->container_guid = 0;
		$e1->save();
		$guid1 = $e1->getGUID();

		$e2 = new \ElggObject();
		$e2->subtype = $this->getRandomSubtype();
		$e2->container_guid = $guid1;
		$e2->access_id = ACCESS_PUBLIC;
		$e2->owner_guid = 0;
		$e2->save();
		$guid2 = $e2->getGUID();

		// fake being logged out
		$old_user = $this->replaceSession();
		$ia = elgg_set_ignore_access(true);

		$this->assertTrue($e1->disable(null, true));

		// "log in" original user
		$this->replaceSession($old_user);
		elgg_set_ignore_access($ia);

		$this->assertFalse(get_entity($guid1));
		$this->assertFalse(get_entity($guid2));

		$db_prefix = _elgg_config()->dbprefix;
		$q = "SELECT * FROM {$db_prefix}entities WHERE guid = $guid1";
		$r = get_data_row($q);
		$this->assertEqual('no', $r->enabled);

		$q = "SELECT * FROM {$db_prefix}entities WHERE guid = $guid2";
		$r = get_data_row($q);
		$this->assertEqual('no', $r->enabled);

		access_show_hidden_entities(true);
		$e1->delete();
		$e2->delete();
		access_show_hidden_entities(false);
	}

	public function testElggRecursiveDelete() {
		$types = array('group', 'object', 'user');
		$db_prefix = _elgg_config()->dbprefix;

		foreach ($types as $type) {
			switch ($type) {
				case 'group' :
					$parent = $this->createGroup();
					break;

				case 'user' :
					$parent = $this->createUser();
					break;

				case 'object' :
					$parent = $this->createObject();
					break;
			}
			
			$child = $this->createObject([
				'container_guid' => $parent->guid,
			]);

			$grandchild = $this->createObject([
				'container_guid' => $child->guid,
			]);

			$this->assertTrue($parent->delete(true));

			$q = "SELECT * FROM {$db_prefix}entities WHERE guid = $parent->guid";
			$r = get_data($q);
			$this->assertFalse($r);

			$q = "SELECT * FROM {$db_prefix}entities WHERE guid = $child->guid";
			$r = get_data($q);
			$this->assertFalse($r);

			$q = "SELECT * FROM {$db_prefix}entities WHERE guid = $grandchild->guid";
			$r = get_data($q);
			$this->assertFalse($r);
		}

		// object that owns itself
		// can't check container_guid because of infinite loops in can_edit_entity()
		$obj = new \ElggObject();
		$obj->subtype = $this->getRandomSubtype();
		$obj->save();
		$obj->owner_guid = $obj->guid;
		$obj->save();

		$q = "SELECT * FROM {$db_prefix}entities WHERE guid = $obj->guid";
		$r = get_data_row($q);
		$this->assertEqual($obj->guid, $r->owner_guid);

		$this->assertTrue($obj->delete(true));

		$q = "SELECT * FROM {$db_prefix}entities WHERE guid = $obj->guid";
		$r = get_data_row($q);
		$this->assertFalse($r);
	}

	protected function get_entity_row($guid) {
		$CONFIG = _elgg_config();
		return get_data_row("SELECT * FROM {$CONFIG->dbprefix}entities WHERE guid='$guid'");
	}
}

class ElggObjectWithExposableAttributes extends \ElggObject {
	public function expose_attributes() {
		return $this->attributes;
	}
}
