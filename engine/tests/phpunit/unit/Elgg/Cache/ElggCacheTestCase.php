<?php

namespace Elgg\Cache;

use Elgg\UnitTestCase;
use Elgg\Exceptions\ConfigurationException;

abstract class ElggCacheTestCase extends UnitTestCase {

	/**
	 * @var CompositeCache
	 */
	protected $cache;

	public function up() {
		try {
			$this->cache = $this->createCache();
		} catch (ConfigurationException $ex) {
			if ($this->allowSkip()) {
				$this->markTestSkipped();
			} else {
				$this->fail('Unable to create cache for current configuration');
			}
		}

		$this->cache->clear();
	}

	/**
	 * @param string $namespace
	 *
	 * @return CompositeCache
	 * @throws ConfigurationException
	 */
	abstract function createCache(string $namespace);

	public function cacheableValuesProvider() {
		return [
			['lorem ipsum'],
			[[1, 2, 'abc', true]],
			[new \stdClass()],
		];
	}

	public function makeKey() {
		return "key_" . rand();
	}
	
	/**
	 * Is this test allowed to be skipped?
	 *
	 * @return bool
	 */
	public function allowSkip(): bool {
		return true;
	}

	/**
	 * @dataProvider cacheableValuesProvider
	 */
	public function testCanSaveAndLoad($value) {
		$key = $this->makeKey();

		$this->assertNull($this->cache->load($key));

		$this->assertTrue($this->cache->save($key, $value));

		$this->assertEquals($value, $this->cache->load($key));

		$this->cache->delete($key);

		$this->assertNull($this->cache->load($key));
	}

	public function testCanSaveAndLoadWithTTL() {
		$value = 'foobar';
		$key = $this->makeKey();

		$reflector = new \ReflectionClass($this->cache);
		$property = $reflector->getProperty('pool');
		$property->setAccessible(true);
		
		$pool = $property->getValue($this->cache);
		
		$this->assertNull($this->cache->load($key));

		$this->assertTrue($this->cache->save($key, $value, \Elgg\Values::normalizeTime('-1 second')));
		
		// need to detach to make sure the item is loaded from the backend and does not use static cache
		$pool->detachAllItems();
		
		$this->assertNull($this->cache->load($key));
		
		$this->assertTrue($this->cache->save($key, $value, 5));
		
		// need to detach to make sure the item is loaded from the backend and does not use static cache
		$pool->detachAllItems();
		
		$this->assertEquals($value, $this->cache->load($key));

		$this->cache->delete($key);

		$this->assertNull($this->cache->load($key));
	}

	/**
	 * @dataProvider cacheableValuesProvider
	 */
	public function testCanSaveAndLoadWithArrayAccess($value) {
		$key = $this->makeKey();

		$this->assertFalse(isset($this->cache->$key));
		$this->assertNull($this->cache->$key);

		$this->cache->$key = $value;

		$this->assertEquals($value, $this->cache->$key);

		unset($this->cache->$key);

		$this->assertNull($this->cache->load($key));
	}

	/**
	 * @dataProvider cacheableValuesProvider
	 */
	public function testCanClearCache($value) {
		$key = $this->makeKey();

		$this->assertNull($this->cache->load($key));

		$this->assertTrue($this->cache->add($key, $value));

		$this->assertEquals($value, $this->cache->load($key));

		$this->assertFalse($this->cache->add($key, $value));

		$this->cache->delete($key);

		$this->assertNull($this->cache->load($key));
	}

	public function testCanSetVariable() {

		$this->assertNull($this->cache->getVariable('foo'));

		$this->cache->setVariable('foo', 'bar');

		$this->assertEquals('bar', $this->cache->getVariable('foo'));

		$this->cache->setVariable('foo', null);
	}
	
	public function testCanSeparateCachesWithNamespaces() {
		$cache1 = $this->createCache('cache_namespace1');
		$cache2 = $this->createCache('cache_namespace2');
		
		// repeating tests need to start with empty cache
		$cache1->clear();
		$cache2->clear();
		
		$cache1->save('foo1', 'bar');
		$cache2->save('foo2', 'bar');
		
		$this->assertEquals('bar', $cache1->load('foo1'));
		$this->assertEquals('bar', $cache2->load('foo2'));
		
		// check if values are written to the correct namespaced cache
		$this->assertNull($cache1->load('foo2'));
		$this->assertNull($cache2->load('foo1'));

		// redis/memcache do not support flushing namespaces
		if (static::class !== 'Elgg\Cache\PersistentCacheTest') {
			// check if clearing namespace 1 does not clear namespace 2
			$cache1->clear();
			$this->assertNull($cache1->load('foo1'));
			
			$reflector = new \ReflectionClass($cache2);
			$property = $reflector->getProperty('pool');
			$property->setAccessible(true);
			
			$pool = $property->getValue($cache2);
			
			// need to detach to make sure the item is loaded from the backend and does not use static cache
			$pool->detachAllItems();
			$this->assertEquals('bar', $cache2->load('foo2'));
		}
	}
}
