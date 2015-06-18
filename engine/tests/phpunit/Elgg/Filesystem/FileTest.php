<?php
namespace Elgg\Filesystem;

class FileTest extends \PHPUnit_Framework_TestCase {

	public function createFile($path) {
		return new File(FlyDirectory::createInMemory(), $path);
	}
	
	public function testCanCheckForItsOwnExistence() {
		$directory = FlyDirectory::createInMemory();
		$directory->putContents('/foo/bar/bar.php', 'bar');
		
		$realfile = $this->createFile('/foo/bar/bar.php');
		$realfile->putContents('bar');

		$this->assertTrue($realfile->exists());

		$nonfile = $this->createFile('/foo/baz.php');

		$this->assertFalse($nonfile->exists());
	}
	
	public function testConsidersOnlyDotPrefixedFilesToBePrivate() {
		$paths = array(
			'.htaccess',
			'.git',
			'/.foo/htaccess',
			'/.git/htaccess',
		);
		
		foreach ($paths as $path) {
			$this->assertTrue($this->createFile($path)->isPrivate());
		}
		
		$paths = array(
			'htaccess',
			'git',
			'/foo/bar.htaccess',
			'/foo/bar.git',
		);
		
		foreach ($paths as $path) {
			$this->assertFalse($this->createFile($path)->isPrivate());
		}
	}
}