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
 * This test checks that the whole datatbase structure is loaded once
 * (and only once) before all the tests run.
 * 
 * @author andres
 */

class ElggTestDBReloadTest extends ElggTestCaseMetatest
{
	/**
	 * Here we mirror the helper object; one will be used
	 * by the test suite process and the other serialized
	 * and passed to the forked child process.
	 */
	public static function setUpBeforeClass()
	{
		global $TestCaseHelper;
		global $TestCaseHelperForForkedProcess;
		if (empty($TestCaseHelper))
		{
			static::loadConfigSettings();
			
			global $CONFIG;
			$CONFIG = new ElggTestConfig(static::getConfigSettings());
			
			$TestCaseHelper = new MetatestHelper(static::getTestDBConnection());
			$TestCaseHelper->setTemporalLog();
			
			$TestCaseHelperForForkedProcess = $TestCaseHelper;
		}
		else 
			{
				global $InnerProcess;
				$InnerProcess = TRUE;
			}
		parent::setUpBeforeClass();
	}
	
	/**
	 * -----------------------------
	 * Test 1
	 * -----------------------------
	 * 
	 * This is the first test of the suite (see the "depends" annotations).
	 * Since this is the first test to run and we started logging before
	 * the suite startup we should be able to take a look at the structure-rebuild
	 * process of the DB.
	 * 
	 * Note: Since tables using engine MEMORY don't record changed times they
	 * will be dropped and loaded one more time 
	 */
	public function testDBReload()
	{
		global $TestCaseHelperForForkedProcess;
		$log = $TestCaseHelperForForkedProcess->getTemporalLogContents();
		
		$inMemoryTables = static::inMemoryTableNames();
		
		foreach (static::allTableNames() as $table)
		{
			if (in_array($table, $inMemoryTables))
			{
				$this->assertTableIsDropped($log, $table, 2);
				$this->assertTableIsCreated($log, $table, 2);
			}
			else 
				{
					$this->assertTableIsDropped($log, $table, 1);
					$this->assertTableIsCreated($log, $table, 1);
				}
		}
	}
	
	/**
	 * @depends testDBReload
	 * -----------------------------
	 * Test 2
	 * -----------------------------
	 * 
	 * Check that the DB strcture is recreated only once per suite. 
	 * Since we keep holding the same log, we should only see one DB
	 * structure creation, the same that we have already seen in the 
	 * previous test. The only tables that should have been dropped and
	 * re-created are those modified by the elgg engine
	 */
	public function testNoTableIsCreatedTwice()
	{
		global $TestCaseHelperForForkedProcess;
		$log = $TestCaseHelperForForkedProcess->getTemporalLogContents();
		
		$elggTables = static::elggModifiedTableNames();
		$inMemoryTables = static::inMemoryTableNames();
		
		foreach (static::allTableNames() as $table)
		{
			if (in_array($table, $inMemoryTables))
			{
				$this->assertTableIsDropped($log, $table, 3);
				$this->assertTableIsCreated($log, $table, 3);
			}
			else if (in_array($table, $elggTables))
			{
				$this->assertTableIsDropped($log, $table, 2);
				$this->assertTableIsCreated($log, $table, 2);
			}
			else 
			{
				$this->assertTableIsDropped($log, $table, 1);
				$this->assertTableIsCreated($log, $table, 1);
			}
		}
	}
	
	/**
	 * Check wether we are on the forked process or not and
	 * restore the log appropriately
	 */
	public static function tearDownAfterClass()
	{
		global $InnerProcess;
		if (empty($InnerProcess))
		{
			global $TestCaseHelper;
			$TestCaseHelper->restoreOriginalLog();	
		}
		parent::tearDownAfterClass();
	}
	
// -----------------------------------------------------------------------------------
// Protected
// -----------------------------------------------------------------------------------	
		
	protected function assertTableIsDropped($log, $table, $howManyTimes)
	{
		$text = "/DROP TABLE $table/";
		$this->assertStrictCountLogRegexp($text, $log, $howManyTimes);
	}
	
	protected function assertTableIsCreated($log, $table, $howManyTimes)
	{
		$text = "/CREATE TABLE `$table`/";
		$this->assertStrictCountLogRegexp($text, $log, $howManyTimes);	
	}
}