<?php

namespace Elgg\Cache;

use Elgg\IntegrationTestCase;

class SystemCacheIntegrationTest extends IntegrationTestCase {
	
	/**
	 * @var SystemCache
	 */
	protected $cache;
	
	/**
	 * @var bool
	 */
	protected $is_enabled;
	
	public function up() {
		$this->createApplication([
			'isolate' => true,
		]);
		
		$this->cache = _elgg_services()->serverCache;
		$this->is_enabled = _elgg_services()->config->system_cache_enabled;
		_elgg_services()->config->system_cache_enabled = false;
	}
	
	public function down() {
		_elgg_services()->config->system_cache_enabled = $this->is_enabled;
	}
	
	public function testInitSavesViewLocations() {
		$this->cache->enable();
		$this->cache->reset();
		
		$this->assertEmpty($this->cache->load('view_locations'));
		$this->assertEmpty($this->cache->load('view_overrides'));
		
		// make sure we register the core views
		_elgg_services()->config->system_cache_loaded = false;
		_elgg_services()->viewCacher->registerCoreViews();
		
		$this->cache->init();
		
		$this->assertNotNull($this->cache->load('view_locations'));
		$this->assertNotNull($this->cache->load('view_overrides'));
		
		$this->cache->reset();
	}
}
