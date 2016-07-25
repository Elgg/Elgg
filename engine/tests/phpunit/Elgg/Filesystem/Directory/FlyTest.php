<?php

namespace Elgg\Filesystem\Directory;

use Elgg\Filesystem\DirectoryTest;

class FlyTest extends DirectoryTest {

	public function emptyDirectoryProvider() {
		return [
			[Fly::createInMemory()],
		];
	}

}
