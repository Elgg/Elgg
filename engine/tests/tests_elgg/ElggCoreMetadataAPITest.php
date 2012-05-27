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

class ElggCoreMetadataAPITest extends ElggTestCase
{
	protected $metastrings;
	protected $object;
	
	/**
	 * Called before each test method.
	 */
	public function setUp() 
	{
		parent::setUp();
		$this->metastrings = array();
		$this->object = new ElggObject();
	}

	public function testGetMetastringById() 
	{
		foreach (array('metaUnitTest', 'metaunittest', 'METAUNITTEST') as $string) 
		{
			$this->create_metastring($string);
		}

		// lookup metastring id
		$cs_ids = get_metastring_id('metaUnitTest', TRUE);
		$this->assertEquals($cs_ids, $this->metastrings['metaUnitTest']);

		// lookup all metastrings, ignoring case
		$cs_ids = get_metastring_id('metaUnitTest', FALSE);
		$this->assertEquals(count($cs_ids), 3);
		$this->assertEquals(count($cs_ids), count($this->metastrings));
		foreach ($cs_ids as $string)
		{
			$this->assertTrue(in_array($string, $this->metastrings));
		}
	}

	public function testElggGetEntitiesFromMetadata() 
	{
		global $CONFIG, $METASTRINGS_CACHE, $METASTRINGS_DEADNAME_CACHE;
		$METASTRINGS_CACHE = $METASTRINGS_DEADNAME_CACHE = array();

		$this->object->title = 'Meta Unit Test';
		$this->object->save();
		$this->create_metastring('metaUnitTest');
		$this->create_metastring('tested');

		// create_metadata returns id of metadata on success
		//Used to be: $this->assertTrue(create_metadata($this->object->guid, 'metaUnitTest', 'tested'));
		//@link http://trac.elgg.org/ticket/4120		
		$metadataGUID = create_metadata($this->object->guid, 'metaUnitTest', 'tested');
		$this->assertInternalType('int', $metadataGUID);
		$this->assertGreaterThan(0, $metadataGUID);
		
		// check value with improper case
		$options = array('metadata_names' => 'metaUnitTest', 'metadata_values' => 'Tested', 'limit' => 10, 'metadata_case_sensitive' => TRUE);
		//Used to be: $this->assertFalse(elgg_get_entities_from_metadata($options));
		//@link http://trac.elgg.org/ticket/4120
		$this->assertEquals(elgg_get_entities_from_metadata($options), array());
		
		// compare forced case with ignored case
		$options = array('metadata_names' => 'metaUnitTest', 'metadata_values' => 'tested', 'limit' => 10, 'metadata_case_sensitive' => TRUE);
		$case_true = elgg_get_entities_from_metadata($options);
		$this->assertInternalType('array', $case_true);

		$options = array('metadata_names' => 'metaUnitTest', 'metadata_values' => 'Tested', 'limit' => 10, 'metadata_case_sensitive' => FALSE);
		$case_false = elgg_get_entities_from_metadata($options);
		$this->assertInternalType('array', $case_false);

		$this->assertEquals($case_true, $case_false);
	}

	public function testElggGetMetadataCount() 
	{
		$this->object->title = 'Meta Unit Test';
		$this->object->save();

		$guid = $this->object->getGUID();
		create_metadata($guid, 'tested', 'tested1', 'text', 0, ACCESS_PUBLIC, true);
		create_metadata($guid, 'tested', 'tested2', 'text', 0, ACCESS_PUBLIC, true);

		$count = (int)elgg_get_metadata(array(
												'metadata_names' => array('tested'),
												'guid' => $guid,
												'count' => true,
												));
		$this->assertEquals($count, 2);
	}

	protected function create_metastring($string) 
	{
		global $CONFIG, $METASTRINGS_CACHE, $METASTRINGS_DEADNAME_CACHE;
		$METASTRINGS_CACHE = $METASTRINGS_DEADNAME_CACHE = array();

		mysql_query("INSERT INTO {$CONFIG->dbprefix}metastrings (string) VALUES ('$string')");
		$this->metastrings[$string] = mysql_insert_id();
	}

	protected function delete_metastrings() 
	{
		global $CONFIG, $METASTRINGS_CACHE, $METASTRINGS_DEADNAME_CACHE;
		$METASTRINGS_CACHE = $METASTRINGS_DEADNAME_CACHE = array();

		$strings = implode(', ', $this->metastrings);
		mysql_query("DELETE FROM {$CONFIG->dbprefix}metastrings WHERE id IN ($strings)");
	}	
}