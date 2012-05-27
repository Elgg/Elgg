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

/**
 * Important: since this test writes a file to the elgg file disk storage 
 * (the /data dir) you may want to consider the following: generally the user 
 * running the tests in the command line is not the same user running the php 
 * process (e.g. nobody). As a result you may not have read/write access to the 
 * /data dir when running the test. If you encounter this problem you can:
 *  
 * 1. Change the <%dataroot%> path in the replacements.ini file. It is advisable to use
 *    the system temporary dir (e.g. /tmp) so that if a test crashes badly (e.g. fatal error)
 *    the system can clear those files automatically.
 * 2. Running the tests as the same user that runs the php process. This would give you the
 *    access to the /data dir but will not prevent leaving garbage behind if the test crashes.
 * 3. Granting read/write access to the /data dir to the command line user. Again, this won't 
 *    help if the test crashes.  
 *    
 * @author andres
 */
class ElggCoreFilestoreTest extends ElggTestCase
{
	protected $filestore;

	/**
	 * Called before each test method.
	 */
	public function setUp() 
	{
		parent::setUp();
		$this->filestore = new ElggDiskFilestoreTest();
	}

	public function testFileMatrix() 
	{
		global $CONFIG;
		
		// create a test user
		$user = $this->createTestUser();
		$created = date('Y/m/d', $user->time_created);
		
		// check matrix with guid
		$guid_dir = $this->filestore->makeFileMatrix($user->guid);
		$this->assertEquals($guid_dir, "$created/$user->guid/");
	}
	
	public function testFilenameOnFilestore() 
	{
		global $CONFIG;
		
		// create a user to own the file
		$user = $this->createTestUser();
		$created = date('Y/m/d', $user->time_created);
		
		// setup a test file
		$file = new ElggFile();
		$file->owner_guid = $user->guid;
		$file->setFilename('testing/filestore.txt');
		$file->open('write');
		$file->write('Testing!');
		$this->assertTrue($file->close());
		
		// ensure filename and path is expected
		$filename = $file->getFilenameOnFilestore($file);
		$filepath = "$CONFIG->dataroot$created/$user->guid/testing/filestore.txt";
		$this->assertEquals($filename, $filepath);
		$this->assertTrue(file_exists($filepath));
		
		// ensure file removed on user delete
		$user->delete();
		$this->assertFalse(file_exists($filepath));
	}


	protected function createTestUser($username = 'fileTest') 
	{
		$user = new ElggUser();
		$user->username = $username;
		$guid = $user->save();
		
		// load user to have access to creation time
		return get_entity($guid);
	}
}

class ElggDiskFilestoreTest extends ElggDiskFilestore 
{
	public function makeFileMatrix($guid) 
	{
		return parent::makeFileMatrix($guid);
	}
}