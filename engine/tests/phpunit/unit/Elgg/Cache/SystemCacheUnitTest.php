<?php

namespace Elgg\Cache;

use Elgg\UnitTestCase;
use Phpfastcache\Cluster\ClusterPoolInterface;

class SystemCacheUnitTest extends UnitTestCase {

	/**
	 * @var bool previous System Cache enabled state
	 */
	protected $is_enabled;
	
	public function up() {
		$this->is_enabled = _elgg_services()->config->system_cache_enabled;
		_elgg_services()->config->system_cache_enabled = false;
	}

	public function down() {
		_elgg_services()->config->system_cache_enabled = $this->is_enabled;
	}

	public function testCanEnableSystemCache() {
		
		_elgg_services()->systemCache->enable();

		$this->assertTrue(_elgg_services()->systemCache->isEnabled());
		
		_elgg_services()->systemCache->disable();

		$this->assertFalse(_elgg_services()->systemCache->isEnabled());

	}

	public function testCanStoreValuesInSystemCache() {

		$cache = _elgg_services()->fileCache;

		$cache->save('foo', 'bar');

		$this->assertEquals('bar', $cache->load('foo'));

		$cache->delete('foo');

		$this->assertNull($cache->load('foo'));
	}

	public function testCanStoreValuesInSystemCacheUsingApiMethods() {
		
		_elgg_services()->systemCache->enable();

		elgg_save_system_cache('foo', 'bar');

		$this->assertEquals('bar', elgg_load_system_cache('foo'));
		
		_elgg_services()->systemCache->reset();

		$this->assertNull(elgg_load_system_cache('foo'));
	}

	public function testCanNotStoreValuesInSystemCacheWhenDisabled() {
		
		_elgg_services()->systemCache->disable();

		$this->assertFalse(elgg_save_system_cache('foo', 'bar'));

		$this->assertNull(elgg_load_system_cache('foo'));
		
		_elgg_services()->systemCache->enable();

		elgg_save_system_cache('foo', 'bar');

		$this->assertEquals('bar', elgg_load_system_cache('foo'));
		
		_elgg_services()->systemCache->disable();

		$this->assertNull(elgg_load_system_cache('foo'));
		
		_elgg_services()->systemCache->reset();
	}
	
	public function testCanDeleteSingleKeyFromCache() {
		_elgg_services()->systemCache->enable();
		_elgg_services()->systemCache->reset();
		
		elgg_save_system_cache('foo', 'bar');
		elgg_save_system_cache('foo2', 'bar2');
		
		$this->assertTrue(elgg_delete_system_cache('foo'));
		$this->assertNull(elgg_load_system_cache('foo'));
		
		$this->assertEquals('bar2', elgg_load_system_cache('foo2'));
		
		_elgg_services()->systemCache->reset();
	}
	
	public function testCanStoreItemWithTTL() {
		_elgg_services()->systemCache->enable();
		_elgg_services()->systemCache->reset();
		
		elgg_save_system_cache('foo', 'bar', 1);
		
		$pool = $this->getInaccessableProperty(_elgg_services()->fileCache, 'pool');
		
		if ($pool instanceof ClusterPoolInterface) {
			$this->markTestSkipped('Unable to test a cluster as it does not implement detachAllItems for all pool drivers');
		}
		
		$pool->detachAllItems();
		
		// need to wait longer than the previous set TTL
		sleep(2);
		$this->assertNull(elgg_load_system_cache('foo'));
		
		elgg_save_system_cache('foo', 'bar', 5);
		$pool->detachAllItems();
		
		$this->assertEquals('bar', elgg_load_system_cache('foo'));
	}
}
