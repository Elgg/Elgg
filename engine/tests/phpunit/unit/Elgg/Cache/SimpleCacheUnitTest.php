<?php

namespace Elgg\Cache;

/**
 * @group UnitTests
 * @group Cache
 * @group SimpleCache
 */
class SimpleCacheUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var SimpleCache
	 */
	protected $service;
	
	public function up() {
		$this->service = _elgg_services()->simpleCache;
	}

	public function down() {
		$this->service->clear();
	}

	public function testGetUrlHandlesSingleArgument() {
		$view = 'view.js';
		elgg_register_simplecache_view($view);

		$url = $this->service->getUrl($view);

		$this->assertMatchesRegularExpression("#default/view.js#", $url);
	}

	public function testGetUrlHandlesTwoArguments() {
		elgg_register_simplecache_view('js/view.js');
		$url = $this->service->getUrl('js', 'view.js');

		$this->assertMatchesRegularExpression("#default/view.js$#", $url);
	}

	public function testGetUrlHandlesTwoArgumentsWhereSecondArgHasRedundantPrefix() {
		elgg_register_simplecache_view('js/view.js');
		$url = $this->service->getUrl('js', 'js/view.js');

		$this->assertMatchesRegularExpression("#default/view.js$#", $url);
	}

	public function testRespectsViewAliases() {
		$this->markTestIncomplete();
	}

	public function testCanEnableSimplecache() {

		$is_enabled = _elgg_services()->config->simplecache_enabled;

		_elgg_services()->config->save('simplecache_enabled', false);

		elgg_disable_simplecache();

		$this->assertFalse(elgg_is_simplecache_enabled());

		elgg_enable_simplecache();

		$this->assertTrue(elgg_is_simplecache_enabled());

		_elgg_services()->config->save('simplecache_enabled', $is_enabled);

	}
	
	public function testClearSimplecacheSymlinked() {
		
		if (stripos(PHP_OS, 'WIN') !== false) {
			$this->markTestSkipped('Unable to test symlinks on Windows');
		}
		
		$is_enabled = _elgg_services()->config->simplecache_enabled;
		_elgg_services()->config->save('simplecache_enabled', true);
		
		$this->assertTrue($this->service->isEnabled());
		
		// create symlink
		$this->assertTrue(_elgg_symlink_cache());
		
		// clear cache
		$this->service->clear();
		
		// ensure symlink still works
		$this->assertTrue(_elgg_is_cache_symlinked());
		
		_elgg_services()->config->save('simplecache_enabled', $is_enabled);
		
		// cleanup symlink
		$this->assertTrue(unlink(elgg_get_root_path() . 'cache'));
	}
	
	public function testCacheAsset() {
		$view = 'foobar.js';
		$viewtype = 'default';
		$cache_time = _elgg_services()->config->lastcache;
		
		$this->assertFalse($this->service->cachedAssetExists($cache_time, $viewtype, $view));
		$this->assertNull($this->service->getCachedAssetLocation($cache_time, $viewtype, $view));
		
		$this->assertGreaterThan(0, $this->service->cacheAsset($viewtype, $view, 'just some random text'));
		
		$this->assertTrue($this->service->cachedAssetExists($cache_time, $viewtype, $view));
		$this->assertFileExists($this->service->getCachedAssetLocation($cache_time, $viewtype, $view));
		$this->assertStringEqualsFile($this->service->getCachedAssetLocation($cache_time, $viewtype, $view), 'just some random text');
	}
	
	public function testCanSymlinkCache() {
		if (stripos(PHP_OS, 'WIN') !== false) {
			$this->markTestSkipped('Unable to test symlinks on Windows');
		}
		
		$root_path = elgg_get_root_path();
		$asset_path = elgg_get_asset_path();
		
		$simplecache_path = $asset_path;
		$symlink_path = "{$root_path}cache";
		
		if (is_dir($simplecache_path)) {
			// Removing to make sure it's recreated
			elgg_delete_directory($simplecache_path);
		}
		
		if (is_dir($symlink_path)) {
			unlink($symlink_path);
		}
		
		$this->assertTrue(_elgg_symlink_cache());
		$this->assertTrue(_elgg_is_cache_symlinked());
		$this->assertTrue(is_dir($simplecache_path));
		
		// Test that we can flush caches with symlink
		elgg_clear_caches();
	}
}
