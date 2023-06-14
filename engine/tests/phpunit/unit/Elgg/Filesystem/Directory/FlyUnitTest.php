<?php

namespace Elgg\Filesystem\Directory;

use Elgg\Filesystem\DirectoryUnitTestCase;

class FlyUnitTest extends DirectoryUnitTestCase {

	public function emptyDirectoryProvider() {
		return [
			[Fly::createInMemory()],
		];
	}
}
