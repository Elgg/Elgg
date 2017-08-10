<?php
/**
 * Elgg Test Skeleton
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreFilestoreTest extends \Elgg\LegacyIntegrationTestCase {

	/**
	 * Called before each test method.
	 */
	public function setUp() {
		parent::setUp();

		$this->filestore = new \ElggDiskFilestore();
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		unset($this->filestore);

		parent::tearDown();
	}
	
	public function testFilenameOnFilestore() {
		$CONFIG = _elgg_config();
		
		// create a user to own the file
		$user = $this->createUser();
		$dir = new \Elgg\EntityDirLocator($user->guid);
		
		// setup a test file
		$file = new \ElggFile();
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
		// deleting the user calls _elgg_clear_entity_files()
		$user->delete();
		$this->assertFalse(file_exists($filepath));
	}

	function testElggFileDelete() {
		$CONFIG = _elgg_config();
		
		$user = $this->createUser();

		$dir = new \Elgg\EntityDirLocator($user->guid);
		
		$file = new \ElggFile();
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
}
