<?php

namespace Elgg\lib\elgglib;

/**
 * @group Elgglib
 */
class ElggCacheTest extends \Elgg\TestCase {

	public function testCanSymlinkCache() {

		$root_path = elgg_get_root_path();
		$cache_path = elgg_get_cache_path();

		$simplecache_path = "{$cache_path}views_simplecache";
		$symlink_path = "{$root_path}cache";

		if (is_dir($simplecache_path)) {
			// Removing to make sure it's recreated
			unlink($simplecache_path);
		}

		if (is_dir($symlink_path)) {
			unlink($symlink_path);
		}

		$this->assertTrue(_elgg_symlink_cache());
		$this->assertTrue(_elgg_is_cache_symlinked());
		$this->assertTrue(is_dir($simplecache_path));
		
		// Test that we can flush caches with symlink
		elgg_flush_caches();
	}
}
