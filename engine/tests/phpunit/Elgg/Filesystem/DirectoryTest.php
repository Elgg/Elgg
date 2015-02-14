<?php
namespace Elgg\Filesystem;

use PHPUnit_Framework_TestCase as TestCase;

abstract class DirectoryTest extends TestCase {

    /**
     * Returns an array of one-element arrays. Those elements should
     * be fresh (empty) directory instances that use the relevant implementation.
     * 
     * @return array
     */
    abstract public function emptyDirectoryProvider();
    
	/**
	 * @dataProvider emptyDirectoryProvider
	 */
	public function testCanRecursivelyListAllFilesInTheDirectory(Directory $directory) {
		$directory->putContents('/foo/bar/bar.php', 'bar');
		$directory->putContents('/foo/baz/baz.php', 'baz');
		$directory->putContents('/foo/foo.php', 'foo');
		$directory->putContents('/qux.php', 'qux');
		
		$this->assertEquals(4, count($directory->getFiles()));
	}
	
	/**
	 * @dataProvider emptyDirectoryProvider
	 */
	public function testCanRecursivelyListAllFilesAtAGivenSubdir(Directory $directory) {
		$directory->putContents('/foo/bar/bar.php', 'bar');
		$directory->putContents('/foo/baz/baz.php', 'baz');
		$directory->putContents('/foo/foo.php', 'foo');
		$directory->putContents('/qux.php', 'qux');
		
		$this->assertEquals(3, count($directory->getFiles('/foo/')));
		$this->assertEquals(3, count($directory->getFiles('/foo')));
		$this->assertEquals(3, count($directory->getFiles('foo/')));
		$this->assertEquals(3, count($directory->getFiles('foo')));

		$this->assertEquals(1, count($directory->getFiles('/foo/bar/')));
		$this->assertEquals(1, count($directory->getFiles('/foo/bar')));
		$this->assertEquals(1, count($directory->getFiles('foo/bar/')));
		$this->assertEquals(1, count($directory->getFiles('foo/bar')));
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
	public function testCannotChrootOutsideItself(Directory $directory) {
		$directory->putContents('/foo/bar.php', 'bar');
		$directory->putContents('/baz.php', 'baz');
		
		$directory->chroot('foo')->chroot('..');
		
		// TODO: Expect to throw exception? Silently cap at existing root?
		$this->markTestIncomplete();
	}
	
	/**
	 * @dataProvider emptyDirectoryProvider
	 */
	public function testCannotGetFileOutsideItself(Directory $directory) {
		$directory->putContents('/foo/bar.php', 'bar');
		$directory->putContents('/baz.php', 'baz');
		
		// TODO: Throw exception?
		$file = $directory->chroot('foo')->getFile('../baz.php');
		
		$this->assertNotEquals('baz', $file->getContents());
	}
}