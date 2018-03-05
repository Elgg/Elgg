<?php

namespace Elgg\Cache;

use Elgg\UnitTestCase;

abstract class ElggCacheTestCase extends UnitTestCase {

	/**
	 * @var CompositeCache
	 */
	protected $cache;

	public function up() {
		try {
			$this->cache = $this->createCache();
		} catch (\ConfigurationException $ex) {
			$this->markTestSkipped("Can not test " . __FUNCTION__ . " with the current configuration");
		}

		$this->cache->clear();
	}

	public function down() {

	}

	/**
	 * @return CompositeCache
	 * @throws \ConfigurationException
	 */
	abstract function createCache();

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


}