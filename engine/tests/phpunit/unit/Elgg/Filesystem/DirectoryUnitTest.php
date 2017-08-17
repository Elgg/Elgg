<?php

namespace Elgg\Filesystem;

/**
 * @group UnitTests
 */
abstract class DirectoryUnitTest extends \Elgg\UnitTestCase {

	/**
	 * Returns an array of one-element arrays. Those elements should
	 * be fresh (empty) directory instances that use the relevant implementation.
	 * 
	 * @return array
	 */
	abstract public function emptyDirectoryProvider();

	public function up() {

	}

	public function down() {

	}

	/**
	 * @dataProvider emptyDirectoryProvider
	 */
	public function testListFilesAndDirsInTheDirectory(Directory $directory) {
		$directory->putContents('/foo/bar/bar.php', 'bar');
		$directory->putContents('/foo/baz/baz.php', 'baz');
		$directory->putContents('/foo/foo.php', 'foo');
		$directory->putContents('/qux.php', 'qux');

		$this->assertEquals(4, count($directory->getFiles()));
		$this->assertEquals(1, count($directory->getFiles('', false)));

		$this->assertEquals(3, count($directory->getDirectories()));
		$this->assertEquals(1, count($directory->getDirectories('', false)));
	}

	/**
	 * @dataProvider emptyDirectoryProvider
	 */
	public function testListFilesAndDirsInASubdirectory(Directory $directory) {
		$directory->putContents('/foo/bar/bar.php', 'bar');
		$directory->putContents('/foo/baz/baz.php', 'baz');
		$directory->putContents('/foo/baz/bing/foo.php', 'foo');
		$directory->putContents('/foo/foo.php', 'foo');
		$directory->putContents('/qux.php', 'qux');

		foreach (['foo', '/foo', 'foo/', '/foo/'] as $path) {
			$this->assertEquals(4, count($directory->getFiles($path)));
			$this->assertEquals(1, count($directory->getFiles($path, false)));

			$this->assertEquals(3, count($directory->getDirectories($path)));
			$this->assertEquals(2, count($directory->getDirectories($path, false)));
		}
	}

	/**
	 * @dataProvider emptyDirectoryProvider
	 */
	public function testChrootReturnsANewDirectoryThatOnlyHasAccessToTheGivenSubdir(Directory $directory) {
		$directory->putContents('/foo/bar/bar.php', 'bar');
		$directory->putContents('/foo/baz/baz.php', 'baz');
		$directory->putContents('/foo/foo.php', 'foo');
		$directory->putContents('/qux.php', 'qux');

		$this->assertEquals(3, count($directory->chroot('/foo/')->getFiles()));
		$this->assertEquals(3, count($directory->chroot('foo/')->getFiles()));
		$this->assertEquals(3, count($directory->chroot('/foo')->getFiles()));
		$this->assertEquals(3, count($directory->chroot('foo')->getFiles()));

		$this->assertEquals(1, count($directory->chroot('/foo/bar/')->getFiles()));
		$this->assertEquals(1, count($directory->chroot('foo/bar/')->getFiles()));
		$this->assertEquals(1, count($directory->chroot('/foo/bar')->getFiles()));
		$this->assertEquals(1, count($directory->chroot('foo/bar')->getFiles()));
	}

	/**
	 * @dataProvider emptyDirectoryProvider
	 */
	public function testCanGetAnyFileInThisDirectoryEvenIfTheFileDoesNotExistYet(Directory $directory) {
		$this->assertFalse($directory->getFile('foo.php')->exists());
	}

	/**
	 * @dataProvider emptyDirectoryProvider
	 */
	public function testPathsCannotContainDots(Directory $directory) {
		$funcs = [
			function () use ($directory) {
				$directory->chroot('.');
			},
			function () use ($directory) {
				$directory->chroot('..');
			},
			function () use ($directory) {
				$directory->getFile('.');
			},
			function () use ($directory) {
				$directory->getFile('..');
			},
		];

		foreach ($funcs as $i => $f) {
			try {
				$f();
				$this->fail("A path was allowed to contain . or .. in function #$i");
			} catch (\InvalidArgumentException $e) {

			}
		}
	}

	/**
	 * @dataProvider emptyDirectoryProvider
	 */
	public function testCanGetFileInsideItself(Directory $directory) {
		$directory->putContents('/foo/bar.php', 'bar');

		$file = $directory->chroot('foo')->getFile('bar.php');

		$this->assertInstanceOf(File::class, $file);

		$this->assertEquals('bar', $file->getContents());
	}

	/**
	 * @dataProvider emptyDirectoryProvider
	 */
	public function testCanGetDirectoryInsideItself(Directory $directory) {
		$directory->putContents('/foo/bar/bang.php', 'bang');
		$directory->putContents('/foo/boom/bang.php', 'bang');

		$dirs = $directory->getDirectories('foo');
		$this->assertCount(2, $dirs);
		foreach ($dirs as $dir) {
			$this->assertInstanceOf(Directory::class, $dir);
			$this->assertEquals('bang', $dir->getFile('bang.php')->getContents());
		}
	}

}
