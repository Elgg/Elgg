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
require_once(dirname(__FILE__) . '/SQLScript.php');

/**
 * This class represents a table that is part of the elgg test database.
 * 
 * When possible, tables are created as IN MEMORY to speedup the queries. However
 * this may not always be possible since IN MEMORY tables don't support BLOB or TEXT
 * column types.
 * 
 * An elgg test table may also have an associated fixture file, which will be loaded before
 * the test is executed. However we try to be smart and not re-load a fixture if the previous
 * test has already loaded it and no changes were applied to that table. 
 *  
 * @author andres
 */
class ElggTestDBTable
{
	private $connection;
	private $name;
	private $engine;
	private $creationTimestamp;
	private $updateTimestamp;
	
	public function __construct($connection, $name, $engine, $creationTimestamp, $updateTimestamp)
	{
		$this->connection = $connection;	
		$this->name = $name;
		$this->engine = $engine;
		$this->creationTimestamp = $creationTimestamp;
		$this->updateTimestamp = $updateTimestamp;
	}

	public function name()
	{
		return $this->name;
	}
	
	/**
	 * Drop the table using its connection
	 * 
	 * @throws Exception
	 */
	public function drop()
	{
		$query = 'DROP TABLE ' . $this->name();
		$result = $this->execute($query);
		if ($result === FALSE)
		{
			$error = sprintf("Unable to drop %s ('%s')", $this->name, $query);
			throw new Exception($error);
		}
	}
	
	/**
	 * Create a sql file that can be later used to re-create
	 * the current state of this table.
	 * 
	 * @param string $path - The path to save the file
	 */
	public function createFixture($path)
	{
		$filename = $path . $this->name() . '.sql';
				
		$structureQuery = $this->buildCreateTableStructureQuery();
		$valuesQuery = $this->buildRecreateTableValuesQuery();
		
		$file = fopen($filename,"w");
		$len = fwrite($file, $structureQuery . "\n\n" . $valuesQuery);
		fclose($file);
	}

	/**
	 * Answer an array with the field names of this table
	 * 
	 * @return Array of String
	 */
	public function fields()
	{
		$fields = array();
		$sql = "SHOW COLUMNS FROM " . $this->name();
		$result = $this->execute($sql);
		while($row = mysql_fetch_assoc($result))
		{
			$fields[] = $row['Field'];
		}
		return $fields;
	}
	
	/**
	 * Answers weather this table uses the MEMORY engine or not
	 * 
	 * @return boolean
	 */
	public function isInMemory()
	{
		return strtoupper($this->engine) == 'MEMORY';
	}

	/**
	 * Restore the table if any change has been made to it after $milestone.
	 * The restoration is achieved by dropping the table and reloading the 
	 * fixture (if available).
	 * 
	 * @param DateTime $milestone
	 */
	public function restoreIfChangedAfter($milestone, $path)
	{
		if ($this->shouldReload($milestone))
		{
			$this->drop();
			$this->loadFixture($path);
		}
	}
	
	/**
	 * Loads the associated table fixture by executing a SQL script
	 * 
	 * @param ElggTestDBConnection $connection
	 */
	public function loadFixture($path)
	{
		$filename = $path . $this->name() . '.sql';
		$script = new SQLScript($filename);
		$script->run($this->connection);
	}
	
	
//	/**
//	 * Truncates this table using an active connection
//	 * 
//	 * @param ElggTestDBConnection $connection
//	 * @throws Exception
//	 */	
//	public function truncate($connection)
//	{
//		$query = 'TRUNCATE TABLE ' . $this->name;
//		$result = $connection->execute($query);
//		if ($result == FALSE)
//		{
//			$error = sprintf("Unable to truncate %s ('%s')", $this->name, $query);
//			throw new Exception($error);
//		}
//	}
			
	
	/**
	 * Answer if the table has been changed after $milestone 
	 * 
	 * @param DateTime $milestone
	 * @return boolean
	 */
	protected function shouldReload($milestone)
	{
		return ($this->isInMemory()) || ($this->updateTimestamp === NULL) || ($this->updateTimestamp >= $milestone);
	}	
// -----------------------------------------------------------------------------------
// Protected
// -----------------------------------------------------------------------------------

	/**
	 * Answers the SQL needed for creating the table's structure
	 * 
	 * @return string
	 */
	protected function buildCreateTableStructureQuery()
	{
		$sql = "SHOW CREATE TABLE " . $this->name();
		$result = $this->execute($sql);
		$row = mysql_fetch_assoc($result);
		return $row['Create Table'] . ";";
	}	

	/**
	 * Answers the SQL needed to populate an empty table with the
	 * current table's values
	 * 
	 * @return string
	 */
	protected function buildRecreateTableValuesQuery()
	{
		$tablename = $this->name();
		$fields = $this->fields();
		$fieldCount = count($fields);
		$header = sprintf("INSERT INTO `%s` (`%s`) VALUES ", $tablename, implode('`,`', $fields));
				
		$sql = "SELECT * FROM $tablename";
		$result = $this->execute($sql);
		$lines = "";
		
		while($row = mysql_fetch_assoc($result))
		{
			$lines .= "\n(";
			$ct = 0;
			foreach ($fields as $field)
			{
				$ct = $ct + 1;
				if($row[$field]===NULL){
					$lines .= "NULL";
				}else if((string)$row[$field] == "0"){
					$lines .= "0";
				}else if(filter_var($row[$field],FILTER_VALIDATE_INT) || filter_var($row[$field],FILTER_VALIDATE_FLOAT)){
					$lines .= $row[$field];
				}else{
					$lines .= "'" . str_replace("\n","\\n",$row[$field]) . "'";
				}
				if ($ct < $fieldCount)
				{
					$lines .= ",";
				} 
				else
					{
						$lines .= "),";
					}
			}
		}
		if ($lines == "")
		{
			return "";
		}
		else
		{
			$lines[strlen($lines)-1] = ';';
			return $header . $lines;
		}
	}

	/**
	 * Execute a query - Just a shorthand for $this->connection->execute()
	 * 
	 * @param string $query
	 * @throws Exception
	 * @return resource
	 */
	protected function execute($query)
	{
		return $this->connection->execute($query);
	}
}