<?php
namespace Elgg\Filesystem;

class FilesystemTest extends \PHPUnit_Framework_TestCase {
	public function testCanRecursivelyListAllFilesInThefilesystem() {
		$filesystem = Filesystem::createInMemory();
		
		$filesystem->put('/foo/bar/bar.php', 'bar');
		$filesystem->put('/foo/baz/baz.php', 'baz');
		$filesystem->put('/foo/foo.php', 'foo');
		$filesystem->put('/qux.php', 'qux');
		
		$this->assertEquals(4, count($filesystem->getFiles()));
	}
	
	public function testCanRecursivelyListAllFilesAtAGivenSubdir() {
		$filesystem = Filesystem::createInMemory();
		
		$filesystem->put('/foo/bar/bar.php', 'bar');
		$filesystem->put('/foo/baz/baz.php', 'baz');
		$filesystem->put('/foo/foo.php', 'foo');
		$filesystem->put('/qux.php', 'qux');
		
		$this->assertEquals(3, count($filesystem->getFiles('/foo/')));
		$this->assertEquals(3, count($filesystem->getFiles('/foo')));
		$this->assertEquals(3, count($filesystem->getFiles('foo/')));
		$this->assertEquals(3, count($filesystem->getFiles('foo')));

		$this->assertEquals(1, count($filesystem->getFiles('/foo/bar/')));
		$this->assertEquals(1, count($filesystem->getFiles('/foo/bar')));
		$this->assertEquals(1, count($filesystem->getFiles('foo/bar/')));
		$this->assertEquals(1, count($filesystem->getFiles('foo/bar')));
	}
	
	public function testChrootReturnsANewFilesystemThatOnlyHasAccessToTheGivenSubdir() {
		$filesystem = Filesystem::createInMemory();
		
		$filesystem->put('/foo/bar/bar.php', 'bar');
		$filesystem->put('/foo/baz/baz.php', 'baz');
		$filesystem->put('/foo/foo.php', 'foo');
		$filesystem->put('/qux.php', 'qux');

		$this->assertEquals(3, count($filesystem->chroot('/foo/')->getFiles()));
		$this->assertEquals(3, count($filesystem->chroot('foo/')->getFiles()));
		$this->assertEquals(3, count($filesystem->chroot('/foo')->getFiles()));
		$this->assertEquals(3, count($filesystem->chroot('foo')->getFiles()));

		$this->assertEquals(1, count($filesystem->chroot('/foo/bar/')->getFiles()));
		$this->assertEquals(1, count($filesystem->chroot('foo/bar/')->getFiles()));
		$this->assertEquals(1, count($filesystem->chroot('/foo/bar')->getFiles()));
		$this->assertEquals(1, count($filesystem->chroot('foo/bar')->getFiles()));
	}
	
	public function testCanGetAnyFileInThisFileSystemEvenIfTheFileDoesNotExistYet() {
		$filesystem = Filesystem::createInMemory();
		
		$this->assertFalse($filesystem->getFile('foo.php')->exists());
	}
	
	public function testCannotChrootOutsideItself() {
		$filesystem = Filesystem::createInMemory();
		
		$filesystem->put('/foo/bar.php', 'bar');
		$filesystem->put('/baz.php', 'baz');
		
		$filesystem->chroot('foo')->chroot('..');
		
		// TODO: Expect to throw exception? Silently cap at existing root?
		$this->markTestIncomplete();
	}
	
	public function testCannotGetFileOutsideItself() {
		$filesystem = Filesystem::createInMemory();
		
		$filesystem->put('/foo/bar.php', 'bar');
		$filesystem->put('/baz.php', 'baz');
		
		// TODO: Throw exception?
		$file = $filesystem->chroot('foo')->getFile('../baz.php');
		
		$this->assertNotEquals('baz', $file->getContents());
	}
}