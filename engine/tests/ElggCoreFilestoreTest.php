<?php
/**
 * Elgg Test Skeleton
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreFilestoreTest extends ElggCoreUnitTest {

	/**
	 * Called before each test method.
	 */
	public function setUp() {
		$this->filestore = new ElggDiskFilestore();
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		unset($this->filestore);
	}

	public function testFilenameOnFilestore() {
		global $CONFIG;

		// create a user to own the file
		$user = $this->createTestUser();
		$dir = new Elgg_EntityDirLocator($user->guid);

		// setup a test file
		$file = new ElggFile();
		$file->owner_guid = $user->guid;
		$file->setFilename('testing/filestore.txt');
		$file->open('write');
		$file->write('Testing!');
		$this->assertTrue($file->close());

		// ensure filename and path is expected
		$filename = $file->getFilenameOnFilestore($file);
		$filepath = $CONFIG->dataroot . $dir . 'testing/filestore.txt';
		$this->assertIdentical($filename, $filepath);
		$this->assertTrue(file_exists($filepath));

		// ensure file removed on user delete
		// deleting the user calls clear_user_files()
		$user->delete();
		$this->assertFalse(file_exists($filepath));
	}

	function testElggFileDelete() {
		global $CONFIG;

		$user = $this->createTestUser();
		$filestore = $this->filestore;
		$dir = new Elgg_EntityDirLocator($user->guid);

		$file = new ElggFile();
		$file->owner_guid = $user->guid;
		$file->setFilename('testing/ElggFileDelete');
		$this->assertTrue($file->open('write'));
		$this->assertTrue($file->write('Test'));
		$this->assertTrue($file->close());
		$file->save();

		$filename = $file->getFilenameOnFilestore($file);
		$filepath = $CONFIG->dataroot . $dir . "testing/ElggFileDelete";
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