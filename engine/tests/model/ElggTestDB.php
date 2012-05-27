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

require_once(dirname(__FILE__) . "/ElggTestDBTable.php");

/**
 * This class represents the test database and encapsulates
 * a set of repetitive tasks that must be performed during 
 * installation and test running.
 * 
 * @author andres
 */
class ElggTestDB
{
	protected $connection;
	protected $name;
	
	public function __construct($connection, $name)
	{
		$this->connection = $connection;
		$this->name = $name;
	}
	
	public function name()
	{
		return $this->name;
	}
	
	public function execute($query)
	{
		return $this->connection->execute($query);
	}
		
	/**
	 * Answer the current timestamp according to the DB settings
	 * 
	 * @return DateTime
	 */
	public function currentTimestamp()
	{
		$result = $this->execute('SELECT NOW()');
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		return new DateTime($row['NOW()']);
	}
	
	/**
	 * Drop all tables in this database
	 */
	public function dropAllTables()
	{
		$tables = $this->tables();
		foreach ($tables as $table)
		{
			$table->drop();
		}
	}
	
	/**
	 * Answers all the tables in this database as objects
	 * 
	 * @return Array of ElggTestDBTable
	 */
	public function tables()
	{
		$tables = array();

		$query = "SELECT TABLE_NAME, ENGINE, CREATE_TIME, UPDATE_TIME FROM information_schema.tables WHERE TABLE_SCHEMA = '" . $this->name() . "'";
		$result = $this->execute($query);

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			$name = $row['TABLE_NAME'];
			$engine = $row['ENGINE'];
			$creationTime = !is_null($row['CREATE_TIME']) ? new DateTime($row['CREATE_TIME']) : NULL;
			$updateTime = !is_null($row['UPDATE_TIME']) ? new DateTime($row['UPDATE_TIME'])  : NULL;
			$tables[] = new ElggTestDBTable($this->connection, $name, $engine, $creationTime, $updateTime);
		}
		return $tables;
	}
	/**
	 * Creates a snapshot of the current state of the DB.
	 * The snapshot is created as one file per table,
	 * so that each table can be loaded as an independent fixture
	 * 
	 * @param string $path - The directory to place the files
	 */
	public function createFixtures($path)
	{
		$tables = $this->tables();
		foreach ($tables as $table)
		{
			$table->createFixture($path);
		}
	}
	
	public function loadFixtures($path)
	{
		$fixtures = glob($path .  '*.sql');
		foreach ($fixtures as $tableSQL)
		{
			$script = new SQLScript($tableSQL);
			$script->run($this->connection);
		}
	}
	
	/**
	 * Check if any table has changed since the last test run.
	 * If so, reload the fixture associated with that table. 
	 */
	public function restoreChangedTablesAfter($milestone, $path)
	{
		$tables = $this->tables();
		foreach ($tables as $table)
		{
			$table->restoreIfChangedAfter($milestone, $path);
		}
	}
	
}