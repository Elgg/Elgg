<?php

namespace Elgg\Integration;

use Elgg\EntityDirLocator;
use Elgg\IntegrationTestCase;
use ElggFile;

/**
 * Elgg Test Skeleton
 *
 * @group IntegrationTests
 */
class ElggCoreFilestoreTest extends IntegrationTestCase {

	/**
	 * @var \ElggUser
	 */
	protected $owner;
	
	public function up() {
		$this->owner = $this->createUser();
		elgg()->session->setLoggedInUser($this->owner);
	}

	public function down() {
		if ($this->owner) {
			$this->owner->delete();
		}
		
		elgg()->session->removeLoggedInUser();
	}

	public function testFilenameOnFilestore() {
		// create a user to own the file
		$user = $this->owner;
		
		$dir = new EntityDirLocator($user->guid);

		// setup a test file
		$file = new ElggFile();
		$file->owner_guid = $user->guid;
		$file->setFilename('testing/filestore.txt');
		$file->open('write');
		$file->write('Testing!');
		$this->assertTrue($file->close());

		// ensure filename and path is expected
		$filename = $file->getFilenameOnFilestore();
		$filepath = _elgg_services()->config->dataroot . $dir . 'testing/filestore.txt';
		$this->assertEquals($filepath, $filename);
		$this->assertFileExists($filepath);

		// ensure file removed on user delete
		// deleting the user should remove all users files
		$this->assertTrue($user->delete());
		$this->assertFileDoesNotExist($filepath);
	}

	function testElggFileDelete() {
		$user = $this->owner;
		$dir = new EntityDirLocator($user->guid);

		$file = new ElggFile();
		$file->owner_guid = $user->guid;
		$file->setFilename('testing/ElggFileDelete');
		$this->assertTrue(is_resource($file->open('write')));
		$this->assertIsInt($file->write('Test'));
		$this->assertTrue($file->close());
		$file->save();

		$filename = $file->getFilenameOnFilestore();
		$filepath = _elgg_services()->config->dataroot . $dir . "testing/ElggFileDelete";
		$this->assertEquals($filepath, $filename);
		$this->assertFileExists($filepath);

		$this->assertTrue($file->delete());
		$this->assertFileDoesNotExist($filepath);
	}
}
