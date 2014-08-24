<?php
namespace Elgg\Filesystem;

use Gaufrette\Adapter\InMemory;
use Gaufrette\Filesystem as GaufretteFilesystem;

class FileTest extends \PHPUnit_Framework_TestCase {
	
	public function testCanCheckForItsOwnExistence() {
		$filesystem = Filesystem::createInMemory();
		
		$filesystem->put('/foo/bar/bar.php', 'bar');
		
		$realfile = new File($filesystem, '/foo/bar/bar.php');
		$nonfile = new File($filesystem, '/foo/baz.php');

		$this->assertTrue($realfile->exists());
		$this->assertFalse($nonfile->exists());
	}
}