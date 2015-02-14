<?php
namespace Elgg\Filesystem;

class GaufretteDirectoryTest extends DirectoryTest {
	
	public function emptyDirectoryProvider() {
		return [
			[GaufretteDirectory::createInMemory()],	
		];
	}
	
}