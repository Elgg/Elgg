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

class ElggCoreEntityTest extends ElggTestCase
{
	protected $entity;

	/**
	 * Called before each test method.
	 */
	public function setUp() 
	{
		parent::setUp();
		$this->entity = new ElggEntityTest();
	}

	/**
	 * Tests the protected attributes
	 */
	public function testElggEntityAttributes() 
	{
		$test_attributes = array();
		$test_attributes['guid'] = NULL;
		$test_attributes['type'] = NULL;
		$test_attributes['subtype'] = NULL;
		$test_attributes['owner_guid'] = elgg_get_logged_in_user_guid();
		$test_attributes['container_guid'] = elgg_get_logged_in_user_guid();
		$test_attributes['site_guid'] = NULL;
		$test_attributes['access_id'] = ACCESS_PRIVATE;
		$test_attributes['time_created'] = NULL;
		$test_attributes['time_updated'] = NULL;
		$test_attributes['last_action'] = NULL;
		$test_attributes['enabled'] = 'yes';
		$test_attributes['tables_split'] = 1;
		$test_attributes['tables_loaded'] = 0;
		ksort($test_attributes);

		$entity_attributes = $this->entity->expose_attributes();
		ksort($entity_attributes);

		$this->assertEquals($entity_attributes, $test_attributes);
	}

	public function testElggEntityGetAndSetBaseAttributes() 
	{
		// explicitly set and get access_id
		$this->assertEquals($this->entity->get('access_id'), ACCESS_PRIVATE);
		$this->assertTrue($this->entity->set('access_id', ACCESS_PUBLIC));
		$this->assertEquals($this->entity->get('access_id'), ACCESS_PUBLIC);

		// check internal attributes array
		$attributes = $this->entity->expose_attributes();
		$this->assertEquals($attributes['access_id'], ACCESS_PUBLIC);

		// implicitly set and get access_id
		$this->entity->access_id = ACCESS_PRIVATE;
		$this->assertEquals($this->entity->access_id, ACCESS_PRIVATE);

		// unset access_id
		unset($this->entity->access_id);
		$this->assertEquals($this->entity->access_id, '');

		// unable to directly set guid
		$this->assertFalse($this->entity->set('guid', 'error'));
		$this->entity->guid = 'error';
		$this->assertNotEquals($this->entity->guid, 'error');

		// fail on non-attribute
		$this->assertNull($this->entity->get('non_existent'));

		// consider helper methods
		$this->assertEquals($this->entity->getGUID(), $this->entity->guid );
		$this->assertEquals($this->entity->getType(), $this->entity->type );
		$this->assertEquals($this->entity->getSubtype(), $this->entity->subtype );
		$this->assertEquals($this->entity->getOwnerGUID(), $this->entity->owner_guid );
		$this->assertEquals($this->entity->getAccessID(), $this->entity->access_id );
		$this->assertEquals($this->entity->getTimeCreated(), $this->entity->time_created );
		$this->assertEquals($this->entity->getTimeUpdated(), $this->entity->time_updated );
	}

	public function testElggEntityGetAndSetMetaData() 
	{
		// ensure metadata not set
		$this->assertNull($this->entity->get('non_existent'));
		$this->assertFalse(isset($this->entity->non_existent));

		// create metadata
		//Used to be: $this->assertTrue($this->entity->non_existent = 'testing');
		//@link http://trac.elgg.org/ticket/4094
		$this->assertEquals($this->entity->non_existent = 'testing', 'testing');

		// check metadata set
		$this->assertTrue(isset($this->entity->non_existent));
		$this->assertEquals($this->entity->non_existent, 'testing');
		$this->assertEquals($this->entity->getMetaData('non_existent'), 'testing');

		// check internal metadata array
		$metadata = $this->entity->expose_metadata();
		$this->assertEquals($metadata['non_existent'], 'testing');
	}

	public function testElggEnityGetAndSetAnnotations() 
	{
		$this->assertFalse(array_key_exists('non_existent', $this->entity->expose_annotations()));
		//Used to be: $this->assertFalse($this->entity->getAnnotations('non_existent'));
		//@link http://trac.elgg.org/ticket/4095
		$this->assertEmpty($this->entity->getAnnotations('non_existent'));

		// set and check temp annotation
		$this->assertTrue($this->entity->annotate('non_existent', 'testing'));
		$this->assertEquals($this->entity->getAnnotations('non_existent'), array('testing'));
		$this->assertTrue(array_key_exists('non_existent', $this->entity->expose_annotations()));

		// save entity and check for annotation
		$this->entity->subtype = 'testing';
		$this->save_entity();
		$this->assertFalse(array_key_exists('non_existent', $this->entity->expose_annotations()));
		$annotations = $this->entity->getAnnotations('non_existent');
		$this->assertInstanceOf('ElggAnnotation', $annotations[0]);
		$this->assertEquals($annotations[0]->name, 'non_existent');
		$this->assertEquals($this->entity->countAnnotations('non_existent'), 1);

		$this->assertEquals($annotations, elgg_get_annotations(array('guid' => $this->entity->getGUID())));
		$this->assertEquals($annotations, elgg_get_annotations(array('guid' => $this->entity->getGUID(), 'type' => 'site')));
		$this->assertEquals($annotations, elgg_get_annotations(array('guid' => $this->entity->getGUID(), 'type' => 'site', 'subtype' => 'testing')));
		$this->assertEquals(FALSE, elgg_get_annotations(array('guid' => $this->entity->getGUID(), 'type' => 'site', 'subtype' => 'fail')));

		//  clear annotation
		$this->assertTrue($this->entity->deleteAnnotations());
		$this->assertEquals($this->entity->countAnnotations('non_existent'), 0);

		$this->assertEquals(array(), elgg_get_annotations(array('guid' => $this->entity->getGUID())));
		$this->assertEquals(array(), elgg_get_annotations(array('guid' => $this->entity->getGUID(), 'type' => 'site')));
		$this->assertEquals(array(), elgg_get_annotations(array('guid' => $this->entity->getGUID(), 'type' => 'site', 'subtype' => 'testing')));
	}

	public function testElggEntityCache() 
	{
		global $ENTITY_CACHE;
		$this->assertInternalType('array', $ENTITY_CACHE);
	}

	public function testElggEntitySaveAndDelete() 
	{
		global $ENTITY_CACHE;

		// unable to delete with no guid
		$this->assertFalse($this->entity->delete());

		// error on save
		try {
			$this->entity->save();
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertInstanceOf('InvalidParameterException', $e);
			$this->assertEquals($e->getMessage(), elgg_echo('InvalidParameterException:EntityTypeNotSet'));
		}

		// set elements
		$this->entity->type = 'site';
		$this->entity->non_existent = 'testing';

		// save
		$this->assertNull($this->entity->getGUID());
		$guid = $this->entity->save();
		$this->assertNotEquals($guid, 0);

		// check guid
		$this->assertEquals($this->entity->getGUID(), $guid);
		$attributes = $this->entity->expose_attributes();
		$this->assertEquals($attributes['guid'], $guid);
		$this->assertEquals($ENTITY_CACHE[$guid], $this->entity);

		// check metadata
		$metadata = $this->entity->expose_metadata();
		$this->AssertFalse(in_array('non_existent', $metadata));
		$this->assertEquals($this->entity->get('non_existent'), 'testing');
	}

	public function testElggEntityDisableAndEnable() 
	{
		global $CONFIG;

		// ensure enabled
		$this->assertTrue($this->entity->isEnabled());

		// false on disable because it's not saved yet.
		$this->assertFalse($this->entity->disable());

		// save and disable
		$this->save_entity();

		// add annotations and metadata to check if they're disabled.
		$annotation_id = create_annotation($this->entity->guid, 'test_annotation_' . rand(), 'test_value_' . rand());
		$metadata_id = create_metadata($this->entity->guid, 'test_metadata_' . rand(), 'test_value_' . rand());

		$this->assertTrue($this->entity->disable());

		// ensure disabled by comparing directly with database
		$entity = get_data_row("SELECT * FROM {$CONFIG->dbprefix}entities WHERE guid = '{$this->entity->guid}'");
		$this->assertEquals($entity->enabled, 'no');

		$annotation = get_data_row("SELECT * FROM {$CONFIG->dbprefix}annotations WHERE id = '$annotation_id'");
		$this->assertEquals($annotation->enabled, 'no');

		$metadata = get_data_row("SELECT * FROM {$CONFIG->dbprefix}metadata WHERE id = '$metadata_id'");
		$this->assertEquals($metadata->enabled, 'no');

		// re-enable for deletion to work
		$this->assertTrue($this->entity->enable());

		// check enabled
		// check annotations and metadata enabled.
		$entity = get_data_row("SELECT * FROM {$CONFIG->dbprefix}entities WHERE guid = '{$this->entity->guid}'");
		$this->assertEquals($entity->enabled, 'yes');

		$annotation = get_data_row("SELECT * FROM {$CONFIG->dbprefix}annotations WHERE id = '$annotation_id'");
		$this->assertEquals($annotation->enabled, 'yes');

		$metadata = get_data_row("SELECT * FROM {$CONFIG->dbprefix}metadata WHERE id = '$metadata_id'");
		$this->assertEquals($metadata->enabled, 'yes');
	}

	public function testElggEntityMetadata() 
	{
		// let's delete a non-existent metadata
		$this->assertFalse($this->entity->deleteMetadata('important'));

		// let's add the meatadata
		//Used to be:
		//$this->assertTrue($this->entity->important = 'indeed!');
		//$this->assertTrue($this->entity->less_important = 'true, too!');
		// @link http://trac.elgg.org/ticket/4104
		
		$this->assertEquals($this->entity->important = 'indeed!', 'indeed!');
		$this->assertEquals($this->entity->less_important = 'true, too!', 'true, too!');
		$this->save_entity();

		// test deleting incorrectly
		// @link http://trac.elgg.org/ticket/2273
		//Used to be: $this->assertFalse($this->entity->deleteMetadata('impotent'));
		// @link http://trac.elgg.org/ticket/4105
		$this->assertNull($this->entity->deleteMetadata('impotent'));
		$this->assertEquals($this->entity->important, 'indeed!');

		// get rid of one metadata
		$this->assertEquals($this->entity->important, 'indeed!');
		$this->assertTrue($this->entity->deleteMetadata('important'));
		$this->assertEquals($this->entity->important, '');

		// get rid of all metadata
		$this->assertTrue($this->entity->deleteMetadata());
		$this->assertEquals($this->entity->less_important, '');
	}

	public function testElggEntityExportables() 
	{
		$exportables = array(
			'guid',
			'type',
			'subtype',
			'time_created',
			'time_updated',
			'container_guid',
			'owner_guid',
			'site_guid'
		);

		$this->assertEquals($exportables, $this->entity->getExportableValues());
	}

	public function testElggEntityMultipleMetadata() 
	{
		foreach (array(false, true) as $save) 
		{
			if ($save) 
			{
				$this->save_entity();
			}
			$md = array('brett', 'bryan', 'brad');
			$name = 'test_md_' . rand();

			$this->entity->$name = $md;

			$this->assertEquals($md, $this->entity->$name);
		}
	}

	public function testElggEntitySingleElementArrayMetadata() 
	{
		foreach (array(false, true) as $save) 
		{
			if ($save) 
			{
				$this->save_entity();
			}
			$md = array('test');
			$name = 'test_md_' . rand();

			$this->entity->$name = $md;

			$this->assertEquals($md[0], $this->entity->$name);

		}
	}

	public function testElggEntityAppendMetadata() 
	{
		foreach (array(false, true) as $save) 
		{
			if ($save) 
			{
				$this->save_entity();
			}
			$md = 'test';
			$name = 'test_md_' . rand();

			$this->entity->$name = $md;
			$this->entity->setMetaData($name, 'test2', '', true);

			$this->assertEquals(array('test', 'test2'), $this->entity->$name);

		}
	}

	public function testElggEntitySingleElementArrayAppendMetadata() 
	{
		foreach (array(false, true) as $save) 
		{
			if ($save) 
			{
				$this->save_entity();
			}
			$md = 'test';
			$name = 'test_md_' . rand();

			$this->entity->$name = $md;
			$this->entity->setMetaData($name, array('test2'), '', true);

			$this->assertEquals(array('test', 'test2'), $this->entity->$name);

		}
	}

	public function testElggEntityArrayAppendMetadata() 
	{
		foreach (array(false, true) as $save) 
		{
			if ($save) 
			{
				$this->save_entity();
			}
			$md = array('brett', 'bryan', 'brad');
			$md2 = array('test1', 'test2', 'test3');
			$name = 'test_md_' . rand();

			$this->entity->$name = $md;
			$this->entity->setMetaData($name, $md2, '', true);

			$this->assertEquals(array_merge($md, $md2), $this->entity->$name);

		}
	}

	protected function save_entity($type='site') 
	{
		$this->entity->type = $type;
		$this->assertNotEquals($this->entity->save(), 0);
	}
}

// ElggEntity is an abstract class with no abstact methods.
class ElggEntityTest extends ElggEntity 
{
	public function __construct() 
	{
		$this->initializeAttributes();
	}

	public function expose_attributes() 
	{
		return $this->attributes;
	}

	public function expose_metadata() 
	{
		return $this->temp_metadata;
	}

	public function expose_annotations() 
	{
		return $this->temp_annotations;
	}
}
	