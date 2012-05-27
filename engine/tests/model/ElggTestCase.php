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

require_once('PHPUnit/Framework/TestSuite.php');
require_once(dirname(__FILE__) . '/ElggTestConfig.php');
require_once(dirname(__FILE__) . '/ElggTestDBConnection.php');
require_once(dirname(__FILE__) . '/ElggTestDBTable.php');
require_once(dirname(__FILE__) . '/ElggTestDB.php');
require_once(dirname(__FILE__) . '/SQLScript.php');
require_once(dirname(__FILE__) . '/ElggTestTimestampStorage.php');

/**
 * Since the elgg engine start is included in the setUp() we might get PHP
 * errors when interpreting a test file (e.g. an abstent parent class in a
 * subclass definition). Thus, we must let PHP load the clases if required
 * before the setUp() is invoked.
 * 
 * @author andres
 */ 
 
function elgg_autoload_classes ($pClassName) 
{
	$filename = __DIR__ . "/../../classes/" . $pClassName . ".php";
	if (file_exists($filename))
	{
		return require_once ($filename);
	}
	else 
		{
			return false;	
		}
}

spl_autoload_register("elgg_autoload_classes");

/**
 * The core of our test cases. This class is in charge of doing all the
 * connections between the test cases and the test DB and restoring the
 * DB back to a known state. 
 * 
 * @author andres
 */
class ElggTestCase extends PHPUnit_Framework_TestCase
{
	/**
	 * Used to bypass PHPUnit buffering during test execution.
	 * See the Debugging methods at the end of the class 
	 */
	protected $buffer = NULL;
	
	/**
	 * Just an in-memory buffer for the settings stored
	 * in config.ini file
	 * 
	 * @var Array of key => value
	 */
	static $configSettings;

	/**
	 * Returns the settings to be used in replacement of the
	 * "real" $CONFIG settings (mostly DB stuff).
	 * Subclasses can redefine/extend this message for 
	 * test-dependent configuration.
	 *
	 * @return array of key => value
	 */
	public static function getConfigSettings()
	{
		return self::$configSettings;
	}
	
	/**
	 * Load the config settings form a .ini file
	 */
	protected static function loadConfigSettings()
	{
		$testConfigFile = __DIR__ . '/../' . 'config.ini';
		$file = new ElggTestConfigFile($testConfigFile);
		static::$configSettings = $file->getMappings();
	}

	/**
	 * Returns the folder with the SQL fixtures to load the tables before the test.  
	 * Subclasses can redefine this message to provide test-specific fixtures.
	 * 
	 * @return string
	 */
	protected static function fixturesDirectory()
	{
		return dirname(__FILE__) . '/../fixtures/';
	}
	
	/**
	 * This method is called before the tests are executed.
	 * We basically create a test DB configuration and
	 * prepare a fresh test DB structure.
	 * Since running the tests in isolation calls this method for
	 * every test case we use a global flag to avoid multiple bootstraps.
	 */
	public static function setUpBeforeClass()
	{
		static::loadConfigSettings();
		
		global $AlreadyBootstrapped;
		if (empty($AlreadyBootstrapped))
		{
			$AlreadyBootstrapped = TRUE;
			global $CONFIG;
			$CONFIG = new ElggTestConfig(static::getConfigSettings());

			static::setUpTestDB();
		} 
	}

	/**
	 * As the name suggest, here we connect to the test DB and
	 * set up a clean version of it.
	 *
	 * @throws Exception
	 */
	protected static function setUpTestDB()
	{
		global $CONFIG;
		$path = static::fixturesDirectory();
		
		$connection = static::getTestDBConnection();
		$database = $connection->database();
		$database->dropAllTables();
		$database->loadFixtures($path);

		static::waitForNexSecond($database);
		$timestamp = $database->currentTimestamp();
		ElggTestTimestampStorage::writeTimestampToFile($timestamp);
		
		$connection->close();
	}
	
	/**
	 * The setup prior to running a test includes:
	 * - Creating and initializing the global $CONFIG.
	 * - Reload the fixture of tables that were changed by a 
	 *   previous test execution.
	 * - Storing the test-start timestamp.
	 * - Loading the elgg engine.
	 * - Loggin-in
	 * @see PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp()
	{
		parent::setUp();
		
		global $CONFIG;
		$path = static::fixturesDirectory();
		
		$CONFIG = new ElggTestConfig(static::getConfigSettings());
		
		$connection = static::getTestDBConnection();
		$database = $connection->database();
				
		$milestone = ElggTestTimestampStorage::readTimestampFromFile();
		
		$this->stopOutputBuffering();
		
		$database->restoreChangedTablesAfter($milestone, $path);
		
		$this->startOutputBuffering();
		
		static::waitForNexSecond($database);
		ElggTestTimestampStorage::writeTimestampToFile($database->currentTimestamp());
		 
		$connection->close();
		
		$_SERVER['REQUEST_URI'] = basename(__FILE__);
		require(dirname(__FILE__) . '/../../../engine/start.php');
		$this->doSetUpLogin();
	}
	
// -----------------------------------------------------------------------------------
// Protected
// -----------------------------------------------------------------------------------
		
	/**
	 * Login just after the setup has been executed.
	 * This mesage has been decoupled from the setUp()
	 * so that subclasses can easily override it.
	 */
	protected function doSetupLogin()
	{
		$this->loginAsUser();
	}

	/**
	 * Just log out.
	 * 
	 * @see PHPUnit_Framework_TestCase::tearDown()
	 */
	public function tearDown()
	{
		logout();
		parent::tearDown();
	}

	/**
	 * Log-in as the predefined admin user
	 */
	protected function loginAsAdmin()
	{
		$settings = static::getConfigSettings();
		$username = $settings['admin_username'];
		$password = $settings['admin_password'];
		$this->login($username, $password);
	}
	
	/**
	 * Log-in as the predefined standard (non-admin) user
	 */
	protected function loginAsUser()
	{
		$settings = static::getConfigSettings();
		$username = $settings['user_username'];
		$password = $settings['user_password'];
		$this->login($username, $password);
	}
	
	/**
	 * Attempt to authenticate and login a user.
	 * 
	 * @param string $username
	 * @param string $password
	 * @throws Exception
	 */
	protected function login($username, $password)
	{
		$result = elgg_authenticate($username, $password);
		if ($result !== true) 
		{
			throw new Exception("User authentication failed (username: $username - password: $password)");
		}
		$user = get_user_by_username($username);
		login($user);
	}

	/**
	 * Waits untill the date value changes to the next second.
	 * 
	 * Problem 1: Unfrtunatelly MySQL tracks changes in the DB
	 * using a DATETIME type, whose resollution is at the seconds 
	 * level. Thus, we must wait until the next second to flag the
	 * start of the test. This *really* sucks, since we are loosing an
	 * average of 500 milliseconds per test (empyrical data shows that 
	 * the average is around 250ms per test). However this still sucks;
	 * e.g. in a suite with 70 tests that takes 1:14 to run, 16 seconds were
	 * wasted here, thus we could easily achieve a speedup of 20% if we
	 * could eliminate this idle time. 
	 *  
	 * Problem 2: The ideal sollution would be to do something like:
	 *
	 *  list($usec, $sec) = explode(" ", microtime());
	 *	$delta = (int) (1000000 - ($usec * 1000000));
	 *	usleep($delta);
	 * 
	 * However this does't work. In my machine if I do: 
	 *  echo date('Y-m-d H:i:s') . "\n";
	 *  <sleep code>
	 *  echo date('Y-m-d H:i:s') . "\n";
	 *  
	 * I get the same output printed tiwce. I guess that this has
	 * something to do with problems related to microtime(), but I can't 
	 * keep working on this issue, so the quick fix is to make a busy
	 * waiting loop with a resollution of 50 milliseconds. Argh...
	 * 
	 * Problem 3: To avoid issues with PHP and MySQL having different time 
	 * configurations (and as a result different meanings of "now") we can't 
	 * use PHP date(). So, we ask the DB for the current time. 
	 * 
	 */
	protected static function waitForNexSecond($database)
	{
		$start = $database->currentTimestamp();
		while ($start == $database->currentTimestamp()) 
		{
			usleep(50000);
		}
	}

	/**
	 * Create an ElggTestDBConnection for the current configuration, 
	 * open and answer it.
	 * 
	 * @return ElggTestDBConnection
	 */
	protected static function getTestDBConnection()
	{
		global $CONFIG;
		$connection = new ElggTestDBConnection($CONFIG);
		$connection->open();
		return $connection;
	}

//	/**
//	 * Answer the path of the test that is being executed. This allows
//	 * us to provide tests-specific configurations.
//	 * 
//	 * @return string
//	 */
//	protected static function executingTestPath()
//	{
//		global $argv;
//		$path = $argv[count($argv)-1];
//		return realpath($path);
//	} 
	
	
// -----------------------------------------------------------------------------------
// Debugging
// -----------------------------------------------------------------------------------	
	
	/**
	 * Forces the string $str to be shown when running the test.
	 * The string is not taken into account for the phpunit expectOutputString family of assertions.
	 *
	 * @param string $str
	 */
	protected function doEcho($str)
	{
		$this->stopOutputBuffering();
		echo $str;
		$this->startOutputBuffering();
	}

	/**
	 ***Use only if you know what you are doing and be sure to re-enable buffering***
	 *
	 * A general function to force PHPUnit to stop buffering the output.
	 */
	protected function stopOutputBuffering()
	{
		if ($this->isOutputBufferingActive())
		{
			$this->setOutputBufferingActive(FALSE);
			$this->buffer = ob_get_contents();
			ob_end_clean();
		}
	}

	/**
	 ***Use only if you know what you are doing***
	 *
	 * A general function to force PHPUnit to start buffering the output.
	 */
	protected function startOutputBuffering()
	{
		if (!is_null($this->buffer))
		{
			ob_start();
			$this->setOutputBufferingActive(TRUE);
			print $this->buffer;
			$this->buffer = NULL;
		}
	}

	/**
	 * This should be just an accessor, but unfortunately outputBufferingActive
	 * is declared as private, so we must use a workaround
	 */
	protected function isOutputBufferingActive()
	{
		$classReflection = new ReflectionClass('PHPUnit_Framework_TestCase');
		$property = $classReflection->getProperty('outputBufferingActive');
		$property->setAccessible(true);
		return $property->getValue($this);
	}

	/**
	 * This should be just an accessor, but unfortunately outputBufferingActive
	 * is declared as private, so we must use a workaround
	 */
	protected function setOutputBufferingActive($value)
	{
		$classReflection = new ReflectionClass('PHPUnit_Framework_TestCase');
		$property = $classReflection->getProperty('outputBufferingActive');
		$property->setAccessible(true);
		$property->setValue($this, $value);
	}
}