<?php
/**
 *  Copyright (C) 2012 Quanbit Software S.A.
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
require_once(dirname(__FILE__) . "/ElggTestConfig.php");
require_once(dirname(__FILE__) . "/ElggTestConfigFile.php");
require_once(dirname(__FILE__) . "/ElggTestDBConnection.php");
require_once(dirname(__FILE__) . "/ElggTestDB.php");
require_once(dirname(__FILE__) . "/../../../install/ElggInstaller.php");

/**
 * This class creates the testing environment so that
 * phpunit tests can be executed agains a test DB instead
 * of the production DB. 
 * It basically does two things:
 *  - Create a clean elgg install in the test DB
 *  - Create the table fixtures
 * 
 * @author andres
 */
class ElggTestInstaller
{
	public function install($filename)
	{
		global $CONFIG;
		
		$file = new ElggTestConfigFile($filename);
		$params = $file->getMappings();
		$CONFIG = new ElggTestConfig($params);

		$this->performInstallTweaks();
		$this->dropExistingTables();
		$this->runBatchInstall($params);
		$this->createStandardUser($params);
		$this->createFixtures();
	}
	
// -----------------------------------------------------------------------------------
// Protected
// -----------------------------------------------------------------------------------
	/**
	 * Perform some tweaks to avoid warnings during the batch installation
	 */
	protected function performInstallTweaks()
	{
		global $CONFIG;
		
		//Avoid system log warnings
		$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
		
		//Avoid menu overloading problem (navigation.php, line 94)
		$CONFIG->menus = array();
	}
	
	/**
	 * Ensure that there databe has no tables (e.g. from a previous install) 
	 */
	protected function dropExistingTables()
	{
		global $CONFIG;

		$connection = new ElggTestDBConnection($CONFIG);
		$connection->open();
		$connection->database()->dropAllTables();
		$connection->close();
	}
		
	/**
	 * Run a batch intall using the elgg installer to create a test environment
	 * 
	 * @param array $params - The parameters required to run the installation 
	 */
	protected function runBatchInstall($params)
	{
		$elggInstaller = new ElggInstaller();
		$batchParams = $this->getParamsForBatchInstall($params);
		$elggInstaller->batchInstall($batchParams);
	}
	
	protected function createStandardUser($params)
	{
		$result = register_user(
								$params['user_username'], 
								$params['user_password'],
								$params['user_displayname'],
								$params['user_email']
								);
		if (!$result)
		{
			throw new Exception('Failed to create test user');
		}
	}
	
	/**
	 * Create a fixture file per-table based on the current contents
	 * of the test DB
	 */
	protected function createFixtures()
	{
		global $CONFIG;
		$path = dirname(dirname(__FILE__)) . '/fixtures/';

		$connection = new ElggTestDBConnection($CONFIG);
		$connection->open();
		$connection->database()->createFixtures($path);
		$connection->close();
	}

	/**
	 * Answer the parameters required to perform an elgg batch install
	 * 
	 * @param array $params - The mappings of the config.ini file
	 * @return array - The parameters renamed and filtered according to the batch install needs
	 */
	protected function getParamsForBatchInstall($params)
	{
		$keys = array('dbuser', 'dbpass', 'dbname', 'dbhost', 'site_name', 'site_email', 'site_dataroot', 'site_wwwroot', 'admin_displayname', 'admin_email', 'admin_username', 'admin_password');
		$replacements = array('dbuser', 'dbpassword', 'dbname', 'dbhost', 'sitename', 'siteemail', 'dataroot', 'wwwroot', 'displayname', 'email', 'username', 'password');
		return $this->getMappingsAs($params, $keys, $replacements);
	}
	
	/**
	 * Returns the key=>value pairs of $mappings replacing the keys
	 * in $keys with $replacements
	 * 
	 * @param array $mappings - The original array
	 * @param array $keys - The array of keys to be replaced
	 * @param array $replacements - The keys to use instead of the originals
	 * @return array
	 */
	protected function getMappingsAs($mappings, $keys, $replacements)
	{
		$result = array();
		foreach ($keys as $index => $key) 
		{
			$newKey = $replacements[$index];
			$result[$newKey] = $mappings[$key];
		}
		return $result;
	}
}
