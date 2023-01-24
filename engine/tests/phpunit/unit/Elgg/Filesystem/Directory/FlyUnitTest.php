<?php

namespace Elgg\Filesystem\Directory;

use Elgg\Filesystem\DirectoryUnitTestCase;

/**
 * @group UnitTests
 */
class FlyUnitTest extends DirectoryUnitTestCase {

	public function emptyDirectoryProvider() {
		return [
			[Fly::createInMemory()],
		];
	}
}
