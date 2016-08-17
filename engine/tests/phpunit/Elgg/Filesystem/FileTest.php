<?php

namespace Elgg\Filesystem;

class FileTest extends \Elgg\TestCase {

	public function testCanCheckForItsOwnExistence() {
		$directory = Directory\InMemory::fromArray([
					'/foo/bar/bar.php' => 'bar',
		]);

		$realfile = new File($directory, '/foo/bar/bar.php');
		$nonfile = new File($directory, '/foo/baz.php');

		$this->assertTrue($realfile->exists());
		$this->assertFalse($nonfile->exists());
	}

}
