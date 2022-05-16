<?php

namespace Elgg\Cache;

use Elgg\UnitTestCase;

class QueryCacheUnitTest extends UnitTestCase {
	
	public function testDisableCacheWithClearClearsCache() {
		$cache = new QueryCache();
		$cache->enable();
		
		$cache->set('foo', 'bar');
		$cache->disable();
		$cache->set('bar', 'foo'); // this shouldn't be saved
		
		$cache->enable();
		
		$this->assertEmpty($cache->get('foo'));
		$this->assertEmpty($cache->get('bar'));
	}
	
	public function testDisableCacheWithoutClearRetainsCache() {
		$cache = new QueryCache();
		$cache->enable();
		
		$cache->set('foo', 'bar');
		$cache->disable(false);
		$cache->set('bar', 'foo'); // this shouldn't be saved
		
		$cache->enable();
		
		$this->assertEquals('bar', $cache->get('foo'));
		$this->assertEmpty($cache->get('bar'));
	}
}
