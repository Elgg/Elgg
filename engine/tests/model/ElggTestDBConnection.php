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

/**
 * This class encapsulates a connection to the test database.
 * All the queries performed by the tests should be finally 
 * handled by this class. 
 * 
 * @author andres
 */
class ElggTestDBConnection
{
	protected $host;
	protected $user;
	protected $password;
	protected $name;
	protected $mysqlLink;
	
	/**
	 * Initialize the object's internals according
	 * to the configuration.
	 * 
	 * @param object $configuration
	 */
	public function __construct($configuration)
	{
		$this->host = $configuration->dbhost;
		$this->user = $configuration->dbuser;
		$this->password = $configuration->dbpass;
		$this->name = $configuration->dbname;
	}
	
	/**
	 * Try to open the connection and set the test DB as the current one.
	 * 
	 * @throws Exception
	 */
	public function open()
	{
		$this->mysqlLink = mysql_connect($this->host, $this->user, $this->password, true);
		if (!$this->mysqlLink)
		{
			$error = sprintf("Connection failed to %s using user:%s and password: %s", $this->host, $this->user, $this->password);
			throw new Exception($error);
		}
		$selected = mysql_select_db($this->name, $this->mysqlLink);
		if (!$selected)
		{
			throw new Exception("Can't select the database " . $this->name);
		}
		$this->checkDatabase();
	}
	
	/**
	 * Closes the DB connection
	 */
	public function close()
	{
		$this->checkDatabase();
		mysql_close($this->mysqlLink);
	}
	
	/**
	 * Execute a query
	 * 
	 * @param string $query
	 * @throws Exception
	 * @return resource
	 */
	public function execute($query)
	{
		$this->checkDatabase();
		$result = mysql_query($query, $this->mysqlLink);
		if (mysql_errno($this->mysqlLink) != 0)
		{
			$message = sprintf("SQL Error while executing '%s'\nError number: %i\nError message:%s", $query, mysql_errno($this->mysqlLink), mysql_error($this->mysqlLink));
			throw new Exception($message);
		}
		return $result;
	}
	
	/**
	 * Answers the database object associated with this connection
	 */
	public function database()
	{
		$this->checkDatabase();
		return new ElggTestDB($this, $this->name);
	}
	
// -----------------------------------------------------------------------------------
// Protected
// -----------------------------------------------------------------------------------

	/**
	 * In general terms, we need to be extra-carefull and make sure
	 * all the queries are being applied to the test DB and not
	 * to the production one.
	 * 
	 * While I hate defensive programming and this check adds a little overhead,
	 * it can definitely save us from data loss/corruption in the production DB.
	 *  
	 * @throws Exception
	 */
	protected function checkDatabase()
	{
	    $result = mysql_query("SELECT DATABASE()", $this->mysqlLink);
	    if (!$result)
	    {
	    	$message = sprintf("Can't get selected database. \nError number: %i\nError message:%s\n", mysql_errno($this->mysqlLink), mysql_error($this->mysqlLink));
			throw new Exception($message);
		}
		
		$currentDB = mysql_result($result,0);
		
		if ($this->name != $currentDB)
		{
			$error = sprintf("DANGER: During the course of the test the active database has been changed from '%s' to '%s'", $this->name, $currentDB);			
			throw new Exception($error);
		}
	}
}