<?php
/**
 * Elgg Test ElggObject
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreObjectTest extends ElggCoreUnitTest {

	/**
	 * Called before each test object.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Called before each test method.
	 */
	public function setUp() {
		$this->entity = new ElggObjectTest();
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		$this->swallowErrors();
		unset($this->entity);
	}

	/**
	 * Called after each test object.
	 */
	public function __destruct() {
		parent::__destruct();
	}

	public function testElggObjectConstructor() {
		$attributes = array();
		$attributes['guid'] = NULL;
		$attributes['type'] = 'object';
		$attributes['subtype'] = NULL;
		$attributes['owner_guid'] = elgg_get_logged_in_user_guid();
		$attributes['container_guid'] = elgg_get_logged_in_user_guid();
		$attributes['site_guid'] = NULL;
		$attributes['access_id'] = ACCESS_PRIVATE;
		$attributes['time_created'] = NULL;
		$attributes['time_updated'] = NULL;
		$attributes['last_action'] = NULL;
		$attributes['enabled'] = 'yes';
		$attributes['tables_split'] = 2;
		$attributes['tables_loaded'] = 0;
		$attributes['title'] = NULL;
		$attributes['description'] = NULL;
		ksort($attributes);

		$entity_attributes = $this->entity->expose_attributes();
		ksort($entity_attributes);

		$this->assertIdentical($entity_attributes, $attributes);
	}

	public function testElggObjectSave() {
		// new object
		$this->AssertEqual($this->entity->getGUID(), 0);
		$guid = $this->entity->save();
		$this->AssertNotEqual($guid, 0);

		$entity_row = $this->get_entity_row($guid);
		$this->assertIsA($entity_row, 'stdClass');

		// update existing object
		$this->entity->title = 'testing';
		$this->entity->description = 'ElggObject';
		$this->assertEqual($this->entity->save(), $guid);

		$object_row = $this->get_object_row($guid);
		$this->assertIsA($object_row, 'stdClass');
		$this->assertIdentical($object_row->title, 'testing');
		$this->assertIdentical($object_row->description, 'ElggObject');

		// clean up
		$this->entity->delete();
	}

	public function testElggObjectLoad() {
		// fail on wrong type
		try {
			$error = new ElggObjectTest(elgg_get_logged_in_user_guid());
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'InvalidClassException');
			$message = sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), elgg_get_logged_in_user_guid(), 'ElggObject');
			$this->assertIdentical($e->getMessage(), $message);
		}
	}

	public function testElggObjectConstructorByGUID() {
		$guid = $this->entity->save();

		// load a new object using guid
		$entity = new ElggObjectTest($guid);
		$this->assertIdentical($this->entity, $entity);

		// clean up
		$this->entity->delete();
	}

	public function testElggObjectClone() {
		$this->entity->title = 'testing';
		$this->entity->description = 'ElggObject';
		$this->entity->var1 = "test";
		$this->entity->var2 = 1;
		$this->entity->var3 = true;
		$this->entity->save();

		// add tag array
		$tag_string = 'tag1, tag2, tag3';
		$tagarray = string_to_tag_array($tag_string);
		$this->entity->tags = $tagarray;

		// a cloned ElggEntity has the guid reset
		$object = clone $this->entity;
		$this->assertIdentical(0, (int)$object->guid);

		// make sure attributes were copied over
		$this->assertIdentical($object->title, 'testing');
		$this->assertIdentical($object->description, 'ElggObject');

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
		$group = new ElggGroup();
		$guid = $group->save();
		$this->assertTrue($this->entity->setContainerGUID($guid));

		// check container
		$this->assertEqual($this->entity->getContainerGUID(), $guid);
		$this->assertIdentical($group, $this->entity->getContainerEntity());

		// clean up
		$group->delete();
	}

	public function testElggObjectExportables() {
		$exportables = array(
			'guid',
			'type',
			'subtype',
			'time_created',
			'time_updated',
			'container_guid',
			'owner_guid',
			'site_guid',
			'title',
			'description'
		);

		$this->assertIdentical($exportables, $this->entity->getExportableValues());
	}

	public function xtestElggObjectAccessOverrides() {
		// set entity to private access with no owner.
		$entity = $this->entity;
		$entity->access_id = ACCESS_PRIVATE;
		$entity->owner_guid = 0;
		$this->assertTrue($entity->save());
		$guid = $entity->getGUID();

		var_dump($guid);
		// try to grab entity
		$entity = false;
		$entity = get_entity($guid);
		var_dump($entity);
		$this->assertFalse($entity);

		$old = elgg_set_ignore_access(true);
	}



	protected function get_object_row($guid) {
		global $CONFIG;
		return get_data_row("SELECT * FROM {$CONFIG->dbprefix}objects_entity WHERE guid='$guid'");
	}

	protected function get_entity_row($guid) {
		global $CONFIG;
		return get_data_row("SELECT * FROM {$CONFIG->dbprefix}entities WHERE guid='$guid'");
	}
}

class ElggObjectTest extends ElggObject {
	public function expose_attributes() {
		return $this->attributes;
	}
}
