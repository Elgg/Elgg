<?php
/**
 * Elgg Test Skeleton
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreFilestoreTest extends ElggCoreUnitTest {

	/**
	 * Called before each test object.
	 */
	public function __construct() {
		parent::__construct();
		
		// all code should come after here
	}

	/**
	 * Called before each test method.
	 */
	public function setUp() {
		$this->filestore = new ElggDiskFilestoreTest();
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		// do not allow SimpleTest to interpret Elgg notices as exceptions
		$this->swallowErrors();
		
		unset($this->filestore);
	}

	/**
	 * Called after each test object.
	 */
	public function __destruct() {
		// all code should go above here
		parent::__destruct();
	}

	public function testFileMatrix() {
		global $CONFIG;
		
		// create a test user
		$user = $this->createTestUser();
		$created = date('Y/m/d', $user->time_created);
		
		// check matrix with guid
		$guid_dir = $this->filestore->makeFileMatrix($user->guid);
		$this->assertIdentical($guid_dir, "$created/$user->guid/");
		
		// clean up user
		$user->delete();
	}
	
	public function testFilenameOnFilestore() {
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
		$this->assertIdentical($filename, $filepath);
		$this->assertTrue(file_exists($filepath));
		
		// ensure file removed on user delete
		$user->delete();
		$this->assertFalse(file_exists($filepath));
	}


	protected function createTestUser($username = 'fileTest') {
		$user = new ElggUser();
		$user->username = $username;
		$guid = $user->save();
		
		// load user to have access to creation time
		return get_entity($guid);
	}
}

class ElggDiskFilestoreTest extends ElggDiskFilestore {
	public function makeFileMatrix($guid) {
		return parent::makeFileMatrix($guid);
	}
}
