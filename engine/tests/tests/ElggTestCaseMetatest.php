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
require_once(dirname(__FILE__) . '/../model/ElggTestCase.php');

/**
 * This is an abstract class whose subclasses will test the behavior
 * of the ElggTestCase class. The main idea here is to temporarily change the MySQL
 * log file, run a set of tests and analyze the generated queries to see if the
 * ElggTestCase is doing the things ok.
 *  
 * For the child test cases to run, the test DB user needs SUPER and RELOAD privileges, 
 * since we need to take a look at the MySQL logs.
 * 
 * As a side note, running the tests with the --process-isolation modifier (which means a 
 * new process is spawned for every test) has some annoying things, one of them being that
 * the static function setUpBeforeClass() is called twice, once inside the test suite process
 * and once in the isolated test process. Thus in some cases we have to do some nasty tricks :(.   
 * 
 * @author andres
 */

abstract class ElggTestCaseMetatest extends ElggTestCase
{
	protected static function appendTablePrefixToAll($tables)
	{
		global $CONFIG;
		$result = array();
		foreach ($tables as $table) 
		{
			$result[] = $CONFIG->dbprefix . $table;
		}
		return $result;
	}

	protected static function fixtures()
	{
		$path = static::fixturesDirectory();
		return glob($path .  '*.sql');
	}
	
	protected static function fixturesMatching($regexp)
	{
		$result = array();
		$fixtures = static::fixtures();
		foreach ($fixtures as $fixtureFilename)
		{
			$contents = file_get_contents($fixtureFilename);
			$ct = preg_match($regexp, $contents);
			if ($ct === 1)
			{
				$result[] = $fixtureFilename;
			}
		}
		return $result;
	}
	
	protected static function tableNamesFromFixtures($array)
	{
		$tables = array();
		foreach ($array as $fixtureFilename)
		{
			$tables[] = basename($fixtureFilename, '.sql');

		}
		return $tables;
	}
	
	/**
	 * The tables in a fresh elgg installation.
	 */
	public static function allTableNames()
	{
		return static::tableNamesFromFixtures(static::fixtures());
	}
	
	/**
	 * The tables that use the MEMORY engine
	 */
	public static function inMemoryTableNames()
	{
		$fixtures = static::fixturesMatching("/ENGINE=MEMORY/");
		return static::tableNamesFromFixtures($fixtures);
	}

	/**
	 * The tables that have associated non-empty fixtures by default.
	 */
	public static function fixturedTableNames()
	{
		$fixtures = static::fixturesMatching("/INSERT INTO `.*` \(.*\) VALUES/");
		return static::tableNamesFromFixtures($fixtures);
	}
	
	/**
	 * The tables that are changed by just including engine/start.php
	 */
	public static function elggModifiedTableNames()
	{
		$tables = array (
						'datalists', 
						'system_log',
						'entities',
						'private_settings',		
						'users_entity',
						'users_sessions',
						);
		return static::appendTablePrefixToAll($tables);		
	}
	
// -----------------------------------------------------------------------------------
// Protected
// -----------------------------------------------------------------------------------
	
	/**
	 * Assert that there is exactly one occurrence of the specified
	 * regexp in the contents of the MySQL log file.
	 * 
	 * @param string $regexp - The regexp to check
	 * @param string $log - The log contents
	 */
	protected function assertStrictOneLogRegexp($regexp, $log)
	{
		$this->assertStrictCountLogRegexp($regexp, $log, 1);
	}
	
	/**
	 * Assert that there is no occurrence of the specified
	 * regexp in the contents of the MySQL log file.
	 * 
	 * @param string $regexp - The regexp to check
	 * @param string $log - The log contents
	 */
	protected function denyLogRegexp($regexp, $log)
	{
		$this->assertStrictCountLogRegexp($regexp, $log, 0);
	}

	/**
	 * Assert that there are exactly N occurrences of the specified
	 * regexp in the contents of the MySQL log file.
	 * 
	 * @param string $regexp - The regexp to check
	 * @param string $log - The log contents
	 * @param int $count - The number of accurrences
	 */
	protected function assertStrictCountLogRegexp($regexp, $log, $count)
	{
		$ct = preg_match_all($regexp, $log, $out);
		$this->assertTrue($ct === $count, "$regexp was matched $ct times in $log\nExpecting $count.");
	}
	
}
/**
 * Due to the characteristics of the things we have to test, some things must
 * be done both at the class and instance side. Thus, we must decouple this common
 * things in a helper.
 * 
 * Important: the serialization is clearly flawled, since it looses the
 * connection to the DB. We take this liberty since the serialization is
 * only required for the ElggTestDBReloadTest, where the forked child process
 * does not need a connection.
 *  
 * @author andres
 */
class MetatestHelper implements Serializable
{
	protected $originalLogEnabled;
	protected $originalLog;
	protected $currentLog;
	protected $connection;
	
	public function __construct($connection)
	{
		$this->connection = $connection;
	}
	
	/**
	 * Change temporarily the current MySQL log filename.
	 */
	public function setTemporalLog()
	{
		$this->openConnection();
		$this->originalLogEnabled = $this->getMySQLLoggingStatus();
		$this->originalLog = $this->getMySQLLogFile();
		$this->createTemporalLogFilename();
 		$this->setMySQLLog($this->currentLog);
	}
	
	/**
	 * Restore the original MySQL log filename.
	 */
	public function restoreOriginalLog()
	{
		$this->setMySQLLog($this->originalLog);
		$this->connection->close();
		
		unlink($this->currentLog);
	}
	
	/**
	 * Just open our underlying connection
	 */
	public function openConnection()
	{
		$this->connection->open();
	}
	
	/**
	 * Run the query.
	 * 
	 * @param string $query
	 */
	public function executeQuery($query)
	{
		return $this->connection->execute($query);
	}

	/**
	 * Answer the filename that MySQL is using to log
	 */
	public function getMySQLLogFile()
	{
		$result = $this->connection->execute("SHOW GLOBAL VARIABLES LIKE 'general_log_file'");
		$array = mysql_fetch_assoc($result);
		return $array["Value"];
	}

	/**
	 * Answer if MySQL genral log is on or off
	 */
	public function getMySQLLoggingStatus()
	{
		$result = $this->connection->execute("SHOW GLOBAL VARIABLES LIKE 'general_log'");
		$array = mysql_fetch_assoc($result);
		return $array["Value"];
	}
	
	/**
	 * Set a new filename to be the current MySQL log
	 * 
	 * @param string $filename
	 */
	public function setMySQLLog($filename)
	{
		$this->connection->execute("FLUSH LOGS");
		$this->connection->execute("SET GLOBAL general_log = 'OFF'");
		$this->connection->execute("SET GLOBAL general_log_file = '$filename'");
		
		if (($filename == $this->originalLog) && ($this->originalLogEnabled == 'ON') 
			||
			($filename != $this->originalLog))
				{
					$this->connection->execute("SET GLOBAL general_log = 'ON'");
				}
		$this->connection->execute("FLUSH LOGS");
	}
	
	/**
	 * Create a temporal filename to be used by MySQL to log all the queries
	 */
	public function createTemporalLogFilename()
	{
		$this->currentLog = sys_get_temp_dir() . '/elgg_test_temporal.log';
		$fp = fopen($this->currentLog, 'w');
		fclose($fp);
		chmod($this->currentLog, 0777);
	}
	
	/**
	 * Answer the contents in the current MySQL log file
	 * @return string
	 */
	public function getTemporalLogContents()
	{
		return file_get_contents($this->currentLog, 'r');
	}
	
    /**
     * A little help for serializing this object. Note that the 
     * DB connection information is not serialized. 
     * 
     * @see Serializable::serialize()
     */
    public function serialize() 
    {
        return serialize(array($this->currentLog, $this->originalLog));
    }
    
    /**
     * A little help for unserializing this object. Note that the 
     * DB connection information is not serialized and thus lost
     * in the serialize-unserialize process.
     * 
     * @see Serializable::unserialize()
     */
    public function unserialize($data) 
    {
        list($this->currentLog, $this->originalLog) = unserialize($data);
    }
}