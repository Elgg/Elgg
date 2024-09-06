<?php

namespace Elgg\Cache;

class QueryCacheTest extends \Elgg\IntegrationTestCase {
	
	public function testCacheRespectsLimit() {
		$app = $this->createApplication([
			'isolate' => true,
			'custom_config_values' => [
				'db_query_cache_limit' => 3,
			],
		]);
		
		$cache = $app->internal_services->queryCache;
		$cache->clear();
		
		$this->assertEquals(3, $this->getInaccessableProperty($cache, 'query_cache_limit'));
		$this->assertEmpty($this->getInaccessableProperty($cache, 'keys'));
		
		$cache->save('a', 'foo1');
		$cache->save('b', 'foo2');
		$cache->save('c', 'foo3');
		
		$this->assertEquals(['a', 'b', 'c'], array_keys($this->getInaccessableProperty($cache, 'keys')));
		
		$cache->save('d', 'foo1');
		
		$this->assertEquals(['b', 'c', 'd'], array_keys($this->getInaccessableProperty($cache, 'keys')));
		$this->assertNull($cache->load('a'));
		
		$cache->save('c', 'foo6');
		
		$this->assertEquals(['b', 'c', 'd'], array_keys($this->getInaccessableProperty($cache, 'keys')));
		
		$this->assertEquals('foo6', $cache->load('c'));
		
		$this->assertEquals(['b', 'd', 'c'], array_keys($this->getInaccessableProperty($cache, 'keys')));
		
		$cache->delete('d');
		
		$this->assertEquals(['b', 'c'], array_keys($this->getInaccessableProperty($cache, 'keys')));
		
		$cache->clear();
		
		$this->assertEquals([], array_keys($this->getInaccessableProperty($cache, 'keys')));
		
	}
}
