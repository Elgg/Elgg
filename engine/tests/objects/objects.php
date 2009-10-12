<?php
/**
 * Elgg Test ElggObject
 *
 * @package Elgg
 * @subpackage Test
 * @author Curverider Ltd
 * @link http://elgg.org/
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
		unset($this->entity);
	}

	/**
	 * Called after each test object.
	 */
	public function __destruct() {
		parent::__destruct();
	}

	/**
	 * A basic test that will be called and fail.
	 */
	public function testElggObjectConstructor() {
		$attributes = array();
		$attributes['guid'] = '';
		$attributes['type'] = 'object';
		$attributes['subtype'] = '';
		$attributes['owner_guid'] = get_loggedin_userid();
		$attributes['container_guid'] = get_loggedin_userid();
		$attributes['site_guid'] = 0;
		$attributes['access_id'] = ACCESS_PRIVATE;
		$attributes['time_created'] = '';
		$attributes['time_updated'] = '';
		$attributes['enabled'] = 'yes';
		$attributes['tables_split'] = 2;
		$attributes['tables_loaded'] = 0;
		$attributes['title'] = '';
		$attributes['description'] = '';

		$this->assertIdentical($this->entity->expose_attributes(), $attributes);
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
			$error = new ElggObjectTest(get_loggedin_userid());
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'InvalidClassException');
			$message = sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), get_loggedin_userid(), 'ElggObject');
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
	
	public function testElggObjectConstructorByObject() {
		$guid = $this->entity->save();
		
		// stdClass: use guid
		$object_row = $this->get_object_row($guid);
		$entity_row = $this->get_entity_row($guid);
		$this->assertIdentical($this->entity, new ElggObjectTest($object_row));
		$this->assertIdentical($this->entity, new ElggObjectTest($entity_row));
		
		// copy attributes of ElggObject
		$this->assertIdentical($this->entity, new ElggObjectTest($this->entity));
		
		// error on ElggEntity
		$entity = new ElggEntityTest($guid);
		try {
			$error = new ElggObjectTest($entity);
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'InvalidParameterException');
			$this->assertIdentical($e->getMessage(), elgg_echo('InvalidParameterException:NonElggObject'));
		}
		
		// clean up
		$this->entity->delete();
	}
	
	public function testElggObjectContainer() {
		$this->assertEqual($this->entity->getContainer(), get_loggedin_userid());
		
		// fals when container not a group
		$this->assertFalse($this->entity->getContainerEntity());
		
		// create and save to group
		$group = new ElggGroup();
		$guid = $group->save();
		$this->assertTrue($this->entity->setContainer($guid));
		
		// check container
		$this->assertEqual($this->entity->getContainer(), $guid);
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
			'container_guid',
			'owner_guid',
			'title',
			'description'
		);
		
		$this->assertIdentical($exportables, $this->entity->getExportableValues());
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
