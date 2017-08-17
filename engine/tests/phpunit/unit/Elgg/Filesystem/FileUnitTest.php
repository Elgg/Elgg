<?php

namespace Elgg\Filesystem;

/**
 * @group UnitTests
 */
class FileUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

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
