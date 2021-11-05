<?php

namespace Elgg\Filesystem\Directory;

use Elgg\Filesystem\DirectoryUnitTest;

/**
 * @group UnitTests
 */
class FlyTest extends DirectoryUnitTest {

	public function emptyDirectoryProvider() {
		return [
			[Fly::createInMemory()],
		];
	}
}
