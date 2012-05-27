<?php
/**
 *  Copyright (C) 2011 Quanbit Software S.A.
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  
 *  IMPORTANT: The tests in this file were ported from the original
 *  elgg engine tests. Please see Elgg's README.txt, COPYRIGHT.txt 
 *  and CONTRIBUTORS.txt for copyright and contributor information.  
 */
require_once(dirname(__FILE__) . '/../model/ElggTestCase.php');

/**
 * This test is a port of engine/tests/objects.php to our framework.
 * 
 * Except for the conversions between SimpleTest and PHPUnit assertions
 * and a few nuances the tests are the same. The only thing that we don't
 * have to handle (since ElggTestCase does it) is the cleanup.
 * 
 * @author andres
 */
class ElggCoreObjectTest extends ElggTestCase
{
	protected $entity; 
	
	/**
	 * Called before each test method.
	 */
	public function setUp() 
	{
		parent::setUp();
		$this->entity = new ElggObjectTest();
	}

	public function testElggObjectConstructor() 
	{
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

		$this->assertEquals($entity_attributes, $attributes);
	}

	public function testElggObjectSave() 
	{
		// new object
		$this->assertNull($this->entity->getGUID());
		$guid = $this->entity->save();
		$this->assertNotEquals($guid, 0);

		$entity_row = $this->get_entity_row($guid);
		$this->assertInstanceOf('stdClass', $entity_row);

		// update existing object
		$this->entity->title = 'testing';
		$this->entity->description = 'ElggObject';
		$this->assertEquals($this->entity->save(), $guid);

		$object_row = $this->get_object_row($guid);
		$this->assertInstanceOf('stdClass', $object_row);
		$this->assertEquals($object_row->title, 'testing');
		$this->assertEquals($object_row->description, 'ElggObject');
	}

	public function testElggObjectLoad() 
	{
		// fail on wrong type
		try {
			$error = new ElggObjectTest(elgg_get_logged_in_user_guid());
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertInstanceOf('InvalidClassException', $e);
			$message = sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), elgg_get_logged_in_user_guid(), 'ElggObject');
			$this->assertEquals($e->getMessage(), $message);
		}
	}

	public function testElggObjectConstructorByGUID() 
	{
		$guid = $this->entity->save();

		// load a new object using guid
		$entity = new ElggObjectTest($guid);
		//Used to be: $this->assertEquals($this->entity, $entity);
		//@link http://trac.elgg.org/ticket/4107
		$this->assertEquals($this->entity->getGUID(), $entity->getGUID());
	}

	public function testElggObjectClone() 
	{
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
		$this->assertEquals(0, (int)$object->guid);

		// make sure attributes were copied over
		$this->assertEquals($object->title, 'testing');
		$this->assertEquals($object->description, 'ElggObject');

		$guid = $object->save();
		$this->assertTrue($guid !== 0);
		$this->assertTrue($guid !== $this->entity->guid);

		// test that metadata was transfered
		$this->assertEquals($this->entity->var1, $object->var1);
		$this->assertEquals($this->entity->var2, $object->var2);
		$this->assertEquals($this->entity->var3, $object->var3);
		$this->assertEquals($this->entity->tags, $object->tags);
	}

	public function testElggObjectContainer() 
	{
		$this->assertEquals($this->entity->getContainerGUID(), elgg_get_logged_in_user_guid());

		// create and save to group
		$group = new ElggGroup();
		$guid = $group->save();
		$this->assertTrue($this->entity->setContainerGUID($guid));

		// check container
		$this->assertEquals($this->entity->getContainerGUID(), $guid);
		// Used to be: $this->assertEquals($group, $this->entity->getContainerEntity());
		//@link http://trac.elgg.org/ticket/4107
	}

	public function testElggObjectExportables() 
	{
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

		$this->assertEquals($exportables, $this->entity->getExportableValues());
	}

	public function xtestElggObjectAccessOverrides() 
	{
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

	protected function get_object_row($guid) 
	{
		global $CONFIG;
		return get_data_row("SELECT * FROM {$CONFIG->dbprefix}objects_entity WHERE guid='$guid'");
	}

	protected function get_entity_row($guid) 
	{
		global $CONFIG;
		return get_data_row("SELECT * FROM {$CONFIG->dbprefix}entities WHERE guid='$guid'");
	}
}

class ElggObjectTest extends ElggObject 
{
	public function expose_attributes() 
	{
		return $this->attributes;
	}
}
	