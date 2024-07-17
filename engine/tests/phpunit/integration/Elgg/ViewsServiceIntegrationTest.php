<?php

namespace Elgg;

class ViewsServiceIntegrationTest extends IntegrationTestCase {
	
	public function up() {
		$this->createApplication([
			'isolate' => true,
		]);
		
		_elgg_services()->reset('views');
		
		$this->cache = _elgg_services()->serverCache;
		$this->views = _elgg_services()->views;
		
		$this->cache->enable();
		$this->cache->clear();
	}
	
	public function testConfigureFromCache() {
		$test_data = ['locations' => [
			'default' => [
				'foo' => 'bar.php'
			],
		]];
		
		$this->assertEmpty($this->cache->load('view_locations'));
		$this->assertEmpty($this->views->listViews());
		
		$this->cache->save('view_locations', $test_data);
		
		$this->assertFalse($this->views->isViewLocationsLoadedFromCache());
		
		$this->views->configureFromCache();
		
		$this->assertTrue($this->views->isViewLocationsLoadedFromCache());
		
		$this->assertEquals(array_keys($test_data['locations']['default']), $this->views->listViews());
		
		// assert that once loaded we do not load again
		$this->cache->clear();
		$this->views->configureFromCache();
		$this->assertEquals(array_keys($test_data['locations']['default']), $this->views->listViews());
	}
	
	public function testCacheConfiguration() {
		$this->assertEmpty($this->cache->load('view_locations'));
		$this->assertEmpty($this->cache->load('view_overrides'));
		
		// make sure we register the core views
		_elgg_services()->reset('viewCacher');
		_elgg_services()->viewCacher->registerCoreViews();

		$this->views->cacheConfiguration();
		
		$this->assertNotNull($this->cache->load('view_locations'));
		$this->assertNotNull($this->cache->load('view_overrides'));
	}
}
