<?php
namespace Elgg\Filesystem;

class FlyDirectoryTest extends DirectoryTest {
	
	public function emptyDirectoryProvider() {
		return [
			[FlyDirectory::createInMemory()],
		];
	}
}