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

	public function testFileMatrixBounds() {
		$guids = array(
			1,
			4999,
			5000,
			5001,
			7500,
			10000,
			13532,
			17234
		);

		foreach ($guids as $guid) {
			$filestore = $this->filestore;
			$bound = $filestore->getLowerBucketBound($guid, $filestore::BUCKET_SIZE);

			if ($guid < 5000) {
				$correct_bound = 1;
			} elseif ($guid < 10000) {
				$correct_bound = 5000;
			} elseif ($guid < 15000) {
				$correct_bound = 10000;
			} elseif ($guid < 20000) {
				$correct_bound = 15000;
			}

			// check bounds
			$this->assertIdentical($correct_bound, $bound);
		}
	}


	public function testFileMatrix() {
		// create a test user
		$user = $this->createTestUser();
		$filestore = $this->filestore;
		$bound = $filestore->getLowerBucketBound($user->guid, $filestore::BUCKET_SIZE);

		// check matrix with guid
		$guid_dir = $this->filestore->makeFileMatrix($user->guid);
		$this->assertIdentical($guid_dir, "$bound/$user->guid/");

		// clean up user
		$user->delete();
	}

	public function testFilenameOnFilestore() {
		global $CONFIG;

		// create a user to own the file
		$user = $this->createTestUser();
		$filestore = $this->filestore;
		$bound = $filestore->getLowerBucketBound($user->guid, $filestore::BUCKET_SIZE);

		// setup a test file
		$file = new ElggFile();
		$file->owner_guid = $user->guid;
		$file->setFilename('testing/filestore.txt');
		$file->open('write');
		$file->write('Testing!');
		$this->assertTrue($file->close());

		// ensure filename and path is expected
		$filename = $file->getFilenameOnFilestore($file);
		$filepath = "$CONFIG->dataroot$bound/$user->guid/testing/filestore.txt";
		$this->assertIdentical($filename, $filepath);
		$this->assertTrue(file_exists($filepath));

		// ensure file removed on user delete
		// Note: this tests clear_user_files() and not ElggFile()->delete()
		$user->delete();
		clear_user_files();
		$this->assertFalse(file_exists($filepath));
	}

	function testElggFileDelete() {
		global $CONFIG;
		
		$user = $this->createTestUser();
		$filestore = $this->filestore;
		$bound = $filestore->getLowerBucketBound($user->guid, $filestore::BUCKET_SIZE);

		$file = new ElggFile();
		$file->owner_guid = $user->guid;
		$file->setFilename('testing/ElggFileDelete');
		$this->assertTrue($file->open('write'));
		$this->assertTrue($file->write('Test'));
		$this->assertTrue($file->close());
		$file->save();

		$filename = $file->getFilenameOnFilestore($file);
		$filepath = "$CONFIG->dataroot$bound/$user->guid/testing/ElggFileDelete";
		$this->assertIdentical($filename, $filepath);
		$this->assertTrue(file_exists($filepath));

		$this->assertTrue($file->delete());
		$this->assertFalse(file_exists($filepath));
		$user->delete();
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
