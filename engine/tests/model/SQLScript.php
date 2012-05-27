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
require_once(dirname(__FILE__) . '/ElggTestConfigFile.php');

/**
 * This class represents a SQL Script that resides in a file and
 * that can be executed given a proper database link.
 * 
 * The main functionality of this class (the run() method)
 * has been pretty much copied from the elgg run_sql_script()
 * function (in database.php file), with some modifications
 * to fit our needs. 
 * 
 * @author andres
 */

class SQLScript
{
	private $filename;
	
	/**
	 * Load the required replacements before running the script.
	 * 
	 * @param string $filename
	 */
	public function __construct($filename)
	{
		$this->filename = $filename;
	}
	
	/**
	 * Runs the SQL script using a DB connection.
	 * 
	 * @param ElggTestDBConnection $connection
	 * @throws Exception
	 */
	public function run($connection)
	{
		if ($script = file_get_contents($this->filename)) 
		{
			global $CONFIG;
			
			$errors = array();

			// Remove MySQL -- style comments
			$script = preg_replace('/\-\-.*\n/', '', $script);

			// Statements must end with ; and a newline
			$sql_statements = preg_split('/;[\n\r]+/', $script);

			foreach ($sql_statements as $statement) 
			{
				$statement = trim($statement);
				if (!empty($statement)) 
				{
					$connection->execute($statement);
				}
			}
		}
		else
			{
				throw new Exception("Script $file not found");
			}
	}
}