<?php
namespace Elgg\Filesystem;

use Gaufrette\Adapter\InMemory;
use Gaufrette\Filesystem as GaufretteFilesystem;
use PHPUnit_Framework_TestCase as TestCase;

class FileTest extends TestCase {
	
	public function testCanCheckForItsOwnExistence() {
		$directory = GaufretteDirectory::createInMemory();
		$directory->putContents('/foo/bar/bar.php', 'bar');
		
		$realfile = new File($directory, '/foo/bar/bar.php');
		$nonfile = new File($directory, '/foo/baz.php');

		$this->assertTrue($realfile->exists());
		$this->assertFalse($nonfile->exists());
	}
}