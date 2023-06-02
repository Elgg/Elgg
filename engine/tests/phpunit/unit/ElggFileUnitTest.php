<?php

use Elgg\Exceptions\DomainException;
use Elgg\Exceptions\Filesystem\IOException;
use Elgg\Project\Paths;

/**
 * @group UnitTests
 * @group FileService
 */
class ElggFileUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var \ElggFile
	 */
	protected $file;

	public function up() {
		$file = new \ElggFile();
		$file->owner_guid = 1;
		$file->setFilename("foobar.txt");

		$this->file = $file;

		$dataroot = _elgg_services()->config->dataroot;
		
		// we use this for writing new files
		elgg_delete_directory($dataroot . '1/2/');
	}

	public function down() {
		$dataroot = _elgg_services()->config->dataroot;
		
		// we use this for writing new files
		elgg_delete_directory($dataroot . '1/2/');
	}

	public function testCanSetModifiedTime() {
		$time = $this->file->getModifiedTime();
		$this->file->setModifiedTime();
		$this->assertNotEquals($time, $this->file->getModifiedTime());
	}

	public function testCanSetMimeType() {
		unset($this->file->mimetype);

		$mimetype = 'application/plain';
		$this->file->setMimeType($mimetype);
		$this->assertEquals($mimetype, $this->file->getMimeType());
	}

	/**
	 * @dataProvider providerSimpleTypeMap
	 */
	public function testCanParseSimpleType($mime_type, $simple_type) {
		unset($this->file->simpletype);
		$this->file->mimetype = $mime_type;
		$this->assertEquals($simple_type, $this->file->getSimpleType());
	}

	function providerSimpleTypeMap() {
		return [
			[
				'x-world/x-svr',
				'general'
			],
			[
				'application/msword',
				'document'
			],
			[
				'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'document'
			],
			[
				'application/vnd.oasis.opendocument.text',
				'document'
			],
			[
				'application/pdf',
				'document'
			],
			[
				'application/ogg',
				'audio'
			],
			[
				'text/css',
				'document'
			],
			[
				'text/plain',
				'document'
			],
			[
				'audio/midi',
				'audio'
			],
			[
				'audio/mpeg',
				'audio'
			],
			[
				'image/jpeg',
				'image'
			],
			[
				'image/bmp',
				'image'
			],
			[
				'video/mpeg',
				'video'
			],
			[
				'video/quicktime',
				'video'
			],
		];
	}

	public function testFileExists() {
		$this->assertTrue($this->file->exists());

		$this->file->setFilename('foo/bar.txt');
		$this->assertFalse($this->file->exists());
	}

	public function testExceptionThrownForMissingFilenameOnOpen() {
		$file = new \ElggFile();
		
		$this->expectException(IOException::class);
		$file->open('read');
	}

	public function testExceptionThrownForUnknownModeOnOpen() {
		$file = new \ElggFile();
		$file->setFilename('foo.txt');
		
		$this->expectException(DomainException::class);
		$file->open('foo');
	}

	public function testCanReadFile() {
		$this->assertNotEmpty($this->file->open('read'));
		$contents = $this->file->grabFile();
		$this->assertTrue($this->file->close());

		$dataroot = _elgg_services()->config->dataroot;
		$expected = file_get_contents("{$dataroot}1/1/foobar.txt");

		$this->assertEquals($expected, $contents);
	}

	public function testCanCreateEmptyFile() {
		$file = new \ElggFile();
		$file->owner_guid = 2;
		$file->setFilename('write-test.md');

		$this->assertFalse($file->exists());

		$this->assertNotEmpty($file->open('write'));
		$this->assertTrue($file->close());

		$this->assertTrue($file->exists());

		$this->assertEquals('', file_get_contents($file->getFilenameOnFilestore()));

		$this->assertTrue($file->delete());

		$this->assertFalse($file->exists());
	}

	public function testCanWriteToFile() {

		$contents = 'Hello world!';
		$contents2 = 'Sunny day outside!';

		$file = new \ElggFile();
		$file->owner_guid = 2;
		$file->setFilename('write-test.md');

		$this->assertFalse($file->exists());

		$this->assertNotEmpty($file->open('write'));
		$this->assertEquals(strlen($contents), $file->write($contents));
		$this->assertTrue($file->close());

		$this->assertTrue($file->exists());

		$this->assertEquals($contents, file_get_contents($file->getFilenameOnFilestore()));

		$this->assertNotEmpty($file->open('append'));
		$this->assertEquals(strlen($contents2), $file->write($contents2));
		$this->assertTrue($file->close());

		$this->assertEquals($contents . $contents2, file_get_contents($file->getFilenameOnFilestore()));

		$this->assertEquals(strlen($contents . $contents2), $file->getSize());

		$this->assertTrue($file->delete());

		$this->assertFalse($file->exists());
	}

	public function testCanTellPosition() {

		$size = $this->file->getSize();
		$this->assertNotEmpty($size);
		$this->assertNotEmpty($this->file->open('read'));

		$this->assertEquals(0, $this->file->seek(2));
		$this->assertEquals(2, $this->file->tell());
		$this->assertFalse($this->file->eof());

		$this->assertEquals(0, $this->file->seek($size));
		$this->assertFalse($this->file->eof());

		$this->file->read(1);
		$this->assertTrue($this->file->eof());

		$this->assertTrue($this->file->close());
	}

	public function testCanResolveFilenameOnFilestore() {

		$filename = "foo/bar.txt";

		$dataroot = _elgg_services()->config->dataroot;
		$dir = new \Elgg\EntityDirLocator(123);

		$file = new \ElggFile();
		$file->owner_guid = 123;
		$file->setFilename($filename);

		$this->assertEquals($filename, $file->getFilename());

		$filestorename = Paths::sanitize("{$dataroot}{$dir}{$filename}", false);
		$this->assertEquals($filestorename, $file->getFilenameOnFilestore());
	}

	public function testCanCreateAndReadSymlinks() {
		if (stripos(PHP_OS, 'WIN') !== false) {
			$this->markTestSkipped('Unable to test symlinks on Windows');
		}

		$symlink_name = "symlink.txt";

		$dataroot = _elgg_services()->config->dataroot;
		$dir = new \Elgg\EntityDirLocator(2);

		// Remove symlink in case it exists
		if (file_exists("$dataroot$dir$symlink_name")) {
			unlink("$dataroot$dir$symlink_name");
		}

		$target = new \ElggFile();
		$target->owner_guid = 2;
		$target->setFilename('symlink-target.txt');
		$target->open('write');
		$target->write('Testing!');
		$target->close();

		$symlink = new \ElggFile();
		$symlink->owner_guid = 2;
		$symlink->setFilename($symlink_name);

		$to = $target->getFilenameOnFilestore();
		$from = $symlink->getFilenameOnFilestore();

		$this->assertTrue(symlink($to, $from));

		$this->assertEquals("{$dataroot}{$dir}{$symlink_name}", $from);

		$this->assertTrue($symlink->exists());

		$target->open('read');
		$file_contents = $target->grabFile();
		$target->close();

		$symlink->open('read');
		$symlink_contents = $symlink->grabFile();
		$symlink->close();

		$this->assertEquals($file_contents, $symlink_contents);

		$this->assertTrue(unlink("{$dataroot}{$dir}{$symlink_name}"));
		$this->assertFalse($symlink->exists());
	}

	public function testCanDeleteSymlinkAndKeepTarget() {
		if (stripos(PHP_OS, 'WIN') !== false) {
			$this->markTestSkipped('Unable to test symlinks on Windows');
		}

		$to = new \ElggFile();
		$to->owner_guid = 2;
		$to->setFilename('symlink-target.txt');
		$to->open('write');
		$to->close();

		$from = new \ElggFile();
		$from->owner_guid = 2;
		$from->setFilename('symlink.txt');

		$to_filename = $to->getFilenameOnFilestore();
		$from_filename = $from->getFilenameOnFilestore();

		// Delete the symlink but keep the target
		$this->assertTrue(symlink($to_filename, $from_filename));
		$this->assertTrue($from->delete(false));
		$this->assertFalse($from->exists());
		$this->assertFalse(is_link($from_filename));
		$this->assertTrue($to->exists());
	}

	public function testCanDeleteSymlinkAndTarget() {
		if (stripos(PHP_OS, 'WIN') !== false) {
			$this->markTestSkipped('Unable to test symlinks on Windows');
		}

		$to = new \ElggFile();
		$to->owner_guid = 2;
		$to->setFilename('symlink-target.txt');
		$to->open('write');
		$to->close();

		$from = new \ElggFile();
		$from->owner_guid = 2;
		$from->setFilename('symlink.txt');

		$to_filename = $to->getFilenameOnFilestore();
		$from_filename = $from->getFilenameOnFilestore();

		// Delete the symlink and the target
		$this->assertTrue(symlink($to_filename, $from_filename));
		$this->assertTrue($from->delete(true));
		$this->assertFalse($from->exists());
		$this->assertFalse(is_link($from_filename));
		$this->assertFalse($to->exists());
	}

	public function testCanDeleteSymlinkWithMissingTarget() {
		if (stripos(PHP_OS, 'WIN') !== false) {
			$this->markTestSkipped('Unable to test symlinks on Windows');
		}

		$to = new \ElggFile();
		$to->owner_guid = 2;
		$to->setFilename('symlink-target.txt');
		$to->open('write');
		$to->close();

		$from = new \ElggFile();
		$from->owner_guid = 2;
		$from->setFilename('symlink.txt');

		$to_filename = $to->getFilenameOnFilestore();
		$from_filename = $from->getFilenameOnFilestore();

		// Test there are no errors when target doesn't exist anymore
		$this->assertTrue(symlink($to_filename, $from_filename));
		$this->assertTrue($to->delete());
		$this->assertTrue($from->delete(true));
		$this->assertFalse($from->exists());
		$this->assertFalse(is_link($from_filename));
		$this->assertFalse($to->exists());
	}

	public function testCanTransferFile() {

		$dataroot = _elgg_services()->config->dataroot;

		$file = new \ElggFile();
		$file->owner_guid = 3;
		$file->setFilename("file-to-transfer.txt");
		$file->setFilename("file-to-transfer.txt");

		// Fail with non-existent file
		$this->assertFalse($file->transfer(4));

		$file->open('write');
		$file->write('Transfer');
		$file->close();

		$this->assertTrue($file->transfer(4));
		$this->assertEquals(4, $file->owner_guid);
		$this->assertEquals("file-to-transfer.txt", $file->getFilename());
		$this->assertEquals(Paths::sanitize("{$dataroot}1/4/file-to-transfer.txt", false), $file->getFilenameOnFilestore());
		$this->assertTrue($file->exists());
		$this->assertFileDoesNotExist("{$dataroot}1/3/file-to-transfer.txt");

		$this->assertTrue($file->transfer(3, 'tmp/transferred-file.txt'));
		$this->assertEquals(3, $file->owner_guid);
		$this->assertEquals("tmp/transferred-file.txt", $file->getFilename());
		$this->assertEquals(Paths::sanitize("{$dataroot}1/3/tmp/transferred-file.txt", false), $file->getFilenameOnFilestore());
		$this->assertTrue($file->exists());
		$this->assertFileDoesNotExist("{$dataroot}1/4/file-to-transfer.txt");

		// cleanup
		elgg_delete_directory("{$dataroot}1/3/");
		elgg_delete_directory("{$dataroot}1/4/");
	}

	public function testCanGetDownloadUrl() {

		_elgg_services()->events->backup();

		$file = new \ElggFile();
		$file->owner_guid = 2;
		$file->setFilename('download-url.txt');
		$file->open('write');
		$file->close();

		$this->assertEquals(elgg_get_download_url($file), $file->getDownloadURL());
		$this->assertEquals(elgg_get_download_url($file, false), $file->getDownloadURL(false));
		$this->assertEquals(elgg_get_download_url($file, false, strtotime('+2 minutes')), $file->getDownloadURL(false, strtotime('+2 minutes')));

		_elgg_services()->events->registerHandler('download:url', 'file', function (\Elgg\Event $event) {
			$file = $event->getEntityParam();

			return elgg_normalize_url("download/{$file->originalfilename}");
		});

		$this->assertEquals(elgg_normalize_url("download/{$file->originalfilename}"), $file->getDownloadURL());

		_elgg_services()->events->restore();

		$file->delete();
	}

	public function testCanGetInlineUrl() {

		_elgg_services()->events->backup();

		$file = new \ElggFile();
		$file->owner_guid = 2;
		$file->setFilename('inline-url.txt');
		$file->open('write');
		$file->close();

		$this->assertEquals(elgg_get_inline_url($file), $file->getInlineURL());
		$this->assertEquals(elgg_get_inline_url($file, false), $file->getInlineURL(false));
		$this->assertEquals(elgg_get_inline_url($file, false, strtotime('+2 minutes')), $file->getInlineURL(false, strtotime('+2 minutes')));

		_elgg_services()->events->registerHandler('inline:url', 'file', function (\Elgg\Event $event) {
			$file = $event->getEntityParam();

			return elgg_normalize_url("download/{$file->originalfilename}");
		});

		$this->assertEquals(elgg_normalize_url("download/{$file->originalfilename}"), $file->getInlineUrl());

		_elgg_services()->events->restore();

		$file->delete();
	}
	
	/**
	 * @dataProvider pathTraversalProvider
	 */
	public function testPathTraversal($filename, $expected_filename, $expected_path) {
		$file = new \ElggFile();
		$file->owner_guid = elgg_get_site_entity()->guid;
		
		$file->setFilename($filename);
		$this->assertEquals($expected_filename, $file->getFilename());
		$this->assertEquals($expected_filename, $file->filename);
		$this->assertEquals($expected_path, $file->getFilenameOnFilestore());
		
		// test magic setter
		$file->filename = $filename;
		$this->assertEquals($expected_filename, $file->getFilename());
		$this->assertEquals($expected_filename, $file->filename);
		$this->assertEquals($expected_path, $file->getFilenameOnFilestore());
	}
	
	public function pathTraversalProvider() {
		$dataroot = elgg_get_data_path();
		
		return [
			['foobar.txt', 'foobar.txt', Paths::sanitize("{$dataroot}/1/1/foobar.txt", false)],
			['../foobar.txt', 'foobar.txt', Paths::sanitize("{$dataroot}/1/1/foobar.txt", false)],
			['./foobar.txt', 'foobar.txt', Paths::sanitize("{$dataroot}/1/1/foobar.txt", false)],
			['./../foobar.txt', 'foobar.txt', Paths::sanitize("{$dataroot}/1/1/foobar.txt", false)],
			['.././foobar.txt', 'foobar.txt', Paths::sanitize("{$dataroot}/1/1/foobar.txt", false)],
			['././foobar.txt', 'foobar.txt', Paths::sanitize("{$dataroot}/1/1/foobar.txt", false)],
			['../../foobar.txt', 'foobar.txt', Paths::sanitize("{$dataroot}/1/1/foobar.txt", false)],
			// with sub-folder
			['bar/foobar.txt', 'bar/foobar.txt', Paths::sanitize("{$dataroot}/1/1/bar/foobar.txt", false)],
			['../bar/foobar.txt', 'bar/foobar.txt', Paths::sanitize("{$dataroot}/1/1/bar/foobar.txt", false)],
			['bar/../foobar.txt', 'bar/foobar.txt', Paths::sanitize("{$dataroot}/1/1/bar/foobar.txt", false)],
			['bar/./foobar.txt', 'bar/foobar.txt', Paths::sanitize("{$dataroot}/1/1/bar/foobar.txt", false)],
			['./bar/foobar.txt', 'bar/foobar.txt', Paths::sanitize("{$dataroot}/1/1/bar/foobar.txt", false)],
			['bar/./foobar.txt', 'bar/foobar.txt', Paths::sanitize("{$dataroot}/1/1/bar/foobar.txt", false)],
			['./bar/../foobar.txt', 'bar/foobar.txt', Paths::sanitize("{$dataroot}/1/1/bar/foobar.txt", false)],
			['../bar/./foobar.txt', 'bar/foobar.txt', Paths::sanitize("{$dataroot}/1/1/bar/foobar.txt", false)],
			['./bar/./foobar.txt', 'bar/foobar.txt', Paths::sanitize("{$dataroot}/1/1/bar/foobar.txt", false)],
			['../bar/../foobar.txt', 'bar/foobar.txt', Paths::sanitize("{$dataroot}/1/1/bar/foobar.txt", false)],
		];
	}
	
	public function testFileExistsWithoutOwnerGuid() {
		$file = new \ElggFile();
		$file->setFilename('foobar.txt');
		
		$this->assertFalse($file->exists());
	}
}
