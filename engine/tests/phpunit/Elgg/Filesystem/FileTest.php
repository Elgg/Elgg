<?php
namespace Elgg\Filesystem;

use Elgg\Filesystem\GaufretteDirectory;
use Gaufrette\Adapter\InMemory;
use PHPUnit_Framework_TestCase as TestCase;

class FileTest extends TestCase {
	
	public function createFile($path) {
		return new File(GaufretteDirectory::createInMemory(), $path);
	}
	
	public function testCanCheckForItsOwnExistence() {
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