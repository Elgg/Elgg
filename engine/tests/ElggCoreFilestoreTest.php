<?php
/**
 * Elgg Test Skeleton
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreFilestoreTest extends \ElggCoreUnitTest {

	/**
	 * Called before each test method.
	 */
	public function setUp() {
		$this->filestore = new \ElggDiskFilestore();
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
		global $CONFIG;
		
		$user = $this->createTestUser();
		$filestore = $this->filestore;
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

	function testElggGetFileSimpletype() {

		$tests = array(
			'x-world/x-svr' => 'general',
			'application/msword' => 'document',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'document',
			'application/vnd.oasis.opendocument.text' => 'document',
			'application/pdf' => 'document',
			'application/ogg' => 'audio',
			'text/css' => 'document',
			'text/plain' => 'document',
			'audio/midi' => 'audio',
			'audio/mpeg' => 'audio',
			'image/jpeg' => 'image',
			'image/bmp' => 'image',
			'video/mpeg' => 'video',
			'video/quicktime' => 'video',
		);

		foreach ($tests as $mime_type => $simple_type) {
			$this->assertEqual($simple_type, elgg_get_file_simple_type($mime_type));
		}
	}

	protected function createTestUser($username = 'fileTest') {
		$user = new \ElggUser();
		$user->username = $username;
		$guid = $user->save();
		
		// load user to have access to creation time
		return get_entity($guid);
	}
}