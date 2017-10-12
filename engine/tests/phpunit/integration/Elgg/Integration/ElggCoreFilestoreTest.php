<?php

namespace Elgg\Integration;

use Elgg\EntityDirLocator;
use Elgg\LegacyIntegrationTestCase;
use ElggFile;

/**
 * Elgg Test Skeleton
 *
 * @group IntegrationTests
 */
class ElggCoreFilestoreTest extends LegacyIntegrationTestCase {

	public function up() {
		$this->filestore = new \ElggDiskFilestore();
	}

	public function down() {
		unset($this->filestore);
	}

	public function testFilenameOnFilestore() {
		$CONFIG = _elgg_config();

		// create a user to own the file
		$user = $this->createOne('user');
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
		$filepath = $CONFIG->dataroot . $dir . 'testing/filestore.txt';
		$this->assertIdentical($filename, $filepath);
		$this->assertTrue(file_exists($filepath));

		// ensure file removed on user delete
		// deleting the user should remove all users files
		$user->delete();
		$this->assertFalse(file_exists($filepath));
	}

	function testElggFileDelete() {
		$CONFIG = _elgg_config();

		$user = $this->createOne('user');
		$dir = new EntityDirLocator($user->guid);

		$file = new ElggFile();
		$file->owner_guid = $user->guid;
		$file->setFilename('testing/ElggFileDelete');
		$this->assertTrue(is_resource($file->open('write')));
		$this->assertTrue($file->write('Test'));
		$this->assertTrue($file->close());
		$file->save();

		$filename = $file->getFilenameOnFilestore();
		$filepath = $CONFIG->dataroot . $dir . "testing/ElggFileDelete";
		$this->assertIdentical($filename, $filepath);
		$this->assertTrue(file_exists($filepath));

		$this->assertTrue($file->delete());
		$this->assertFalse(file_exists($filepath));
		$user->delete();
	}
}