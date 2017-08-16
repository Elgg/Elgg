<?php

namespace Elgg\Filesystem\Directory;

use Elgg\Filesystem\DirectoryUnitTest;

/**
 * @group UnitTests
 */
class FlyTest extends DirectoryUnitTest {

	public function up() {

	}

	public function down() {

	}

	public function emptyDirectoryProvider() {
		return [
			[Fly::createInMemory()],
		];
	}

}
