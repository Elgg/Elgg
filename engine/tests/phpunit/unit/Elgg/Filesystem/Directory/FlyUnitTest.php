<?php

namespace Elgg\Filesystem\Directory;

use Elgg\Filesystem\DirectoryUnitTestCase;

class FlyUnitTest extends DirectoryUnitTestCase {

	public static function emptyDirectoryProvider() {
		return [
			[Fly::createInMemory()],
		];
	}
}
