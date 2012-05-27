<?php
/**
 *  Copyright (C) 2011-2012 Quanbit Software S.A.
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
 */
require_once(dirname(__FILE__) . '/ElggTestCaseMetatest.php');

/**
 * When a test runs we expect a set of tables to be modified as a side-effect.
 * ElggTestCase is suposed to be smart enough to only restore the affected
 * tables.
 *  
 * This test is used to check two things:
 *  1. That the changed tables are properly regenerated after being changed
 *     by the prevoius test.
 *  2. That only changed tables (or those whose engine is Memory) are reloaded.
 *    
 * @author andres
 */

class ElggTestRestoredTablesTest extends ElggTestCaseMetatest
{
	protected $helper;
	
	/**
	 * Just create and initialize the helper.
	 * 
	 * @see ElggTestCase::setUp()
	 */
	public function setUp()
	{
		$this->helper = new MetatestHelper(static::getTestDBConnection());
		$this->helper->setTemporalLog();
		parent::setUp();
	}
		
	/**
	 * -----------------------------
	 * Test 1
	 * -----------------------------
	 * 
	 * This is the first test of the suite (see the "depends" annotations).
	 * Since this is the first test to run after the DB has been fully
	 * recreated only the MEMORY tables should be dropped and reloaded.
	 * The other tables should remain untouched.
	 */
	public function testOnlyInMemoryTablesAreRestored()
	{
		$log = $this->helper->getTemporalLogContents();
		
		foreach (static::inMemoryTableNames() as $tableName)
		{
			$this->assertTableIsDroppedOnlyOnce($log, $tableName);
		}
		
		$untouchedTableNames = array_diff(static::allTableNames(), static::inMemoryTableNames());
		foreach ($untouchedTableNames as $tableName) 
		{
			$this->assertTableIsNotLoaded($log, $tableName);
		}
	}
	
	/**
	 * @depends testOnlyInMemoryTablesAreRestored
 	 * -----------------------------
	 * Test 2
	 * -----------------------------
	 * 
	 * The previous test didn't change a thing, thus: 
	 *  1. In-memory tables should be dropped and reloaded.
	 *  2. Those tables changed by elgg engine start should be dropped and reloaded.
	 *  3. The other tables should not be dropped nor loaded.
	 */
	public function testNoChangeIsProperlyHandled()
	{
		$tablesToDrop = $this->defaultTablesToDrop();
		$tablesToReload = array_intersect($tablesToDrop, $this->defaultTablesToReload());
		$unchangedTables = array_diff($this->allTableNames(), $tablesToDrop); 

		$this->checkLogTables($tablesToDrop, $tablesToReload, $unchangedTables, $log);
	}
	
	/**
	 * @depends testNoChangeIsProperlyHandled
 	 * -----------------------------
	 * Test 3
	 * -----------------------------
	 * 
	 * In this test we will just create an object to force a change
	 * in the underlying objects_entity table. The test 4, that depends
	 * on this test (testChangingObjectsEntityTable2) will later check 
	 * that the table is restored.
	 */
	public function testChangingObjectsEntityTable1()
	{
		$obj = new ElggObject();
		$obj->title = "Hi test!";
		$obj->save();
	}
	
	/**
	 * @depends testChangingObjectsEntityTable1
 	 * -----------------------------
	 * Test 4
	 * -----------------------------
	 *
	 * Test that tables are truncated and loaded as usual,
	 * plus the objects_entity table, since it was modified
	 * by the rpevious test.
	 */
	public function testChangingObjectsEntityTable2()
	{
		$objectsTableArray = static::appendTablePrefixToAll(array('objects_entity'));
				
		$tablesToDrop = array_merge($this->defaultTablesToDrop(), $objectsTableArray);
		$tablesToReload = array_intersect($tablesToDrop, $this->defaultTablesToReload());
		$unchangedTables = array_diff($this->allTableNames(), $tablesToDrop); 

		$this->checkLogTables($tablesToDrop, $tablesToReload, $unchangedTables, $log);
	}

	/**
	 * Puth the MySQL log back in place
	 *  
	 * @see ElggTestCase::tearDown()
	 */
	public function tearDown()
	{
		$this->helper->restoreOriginalLog();
		parent::tearDown();
	}
	
// -----------------------------------------------------------------------------------
// Protected
// -----------------------------------------------------------------------------------

	protected function defaultTablesToDrop()
	{
		return array_merge(static::inMemoryTableNames(), static::elggModifiedTableNames());
	}
	
	protected function defaultTablesToReload()
	{
		return static::fixturedTableNames();
	}
	
	protected function checkLogTables($dropped, $reloaded, $unchanged)
	{
		$log = $this->helper->getTemporalLogContents();
				
		foreach ($dropped as $tableName)
		{
			$this->assertTableIsDroppedOnlyOnce($log, $tableName);
			$this->assertTableIsCreatedOnlyOnce($log, $tableName);
		}
		
		foreach ($reloaded as $tableName)
		{
			$this->assertTableIsLoadedOnlyOnce($log, $tableName);
		}
		
		foreach ($unchanged as $tableName)
		{
			$this->assertTableIsNotDropped($log, $tableName);
			$this->assertTableIsNotLoaded($log, $tableName);
		}
	}
	
	protected function assertTableIsDroppedOnlyOnce($log, $table) 
	{
		$text = "/DROP TABLE $table/";
		$this->assertStrictOneLogRegexp($text, $log);
	}

	protected function assertTableIsCreatedOnlyOnce($log, $table) 
	{
		$text = "/CREATE TABLE `$table`/";;
		$this->assertStrictOneLogRegexp($text, $log);
	}
	
	protected function assertTableIsNotDropped($log, $table) 
	{
		$text = "/TRUNCATE TABLE $table/";
		$this->denyLogRegexp($text, $log);
	}
	
	protected function assertTableIsLoadedOnlyOnce($log, $table) 
	{
		$insertText = "Query\tINSERT INTO `$table` \(.*\) VALUES";
		//$setAutoincrementText = "Query\tALTER TABLE $table AUTO_INCREMENT =";
		//$regexp = sprintf("/(%s)|(%s)/", $insertText, $setAutoincrementText);
		$regexp = sprintf("/%s/", $insertText);
		$this->assertStrictOneLogRegexp($regexp, $log);
	}	

	protected function assertTableIsNotLoaded($log, $table) 
	{
		$creationText = "CREATE TABLE `$table` \(.*\)";
		$insertText = "INSERT INTO `$table` \(.*\) VALUES";
		$setAutoincrementText = "ALTER TABLE $table AUTO_INCREMENT =";
		$regexp = sprintf("/(%s)|(%s)|(%s)/", $creationText, $insertText, $setAutoincrementText);
		$this->denyLogRegexp($regexp, $log);
	}
}
