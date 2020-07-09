<?php

namespace Elgg\WebServices;

use Elgg\IntegrationTestCase;

class ElggHMACCacheIntegrationTest extends IntegrationTestCase {

	/**
	 * @var \ElggHMACCache
	 */
	protected $cache;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->cache = new \ElggHMACCache();
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		$this->cache->clear();
		
		unset($this->cache);
	}
	
	public function testSaveLoadDelete() {
		$this->assertTrue($this->cache->save('key1', 'value1'));
		$this->assertEquals('key1', $this->cache->load('key1'));
		$this->assertTrue($this->cache->delete('key1'));
		$this->assertNull($this->cache->load('key1'));
	}
	
	public function testLoadUnknownKey() {
		$this->assertNull($this->cache->load('unknown_key'));
	}
	
	public function testDeleteUnknownKey() {
		$this->assertFalse($this->cache->delete('unknown_key'));
	}
	
	public function testInvalidate() {
		$this->markTestIncomplete();
	}
	
	public function testClear() {
		$this->cache->save('key2', 'value2');
		$this->assertEquals('key2', $this->cache->load('key2'));
		
		$this->assertTrue($this->cache->clear());
		$this->assertNull($this->cache->load('key2'));
	}
	
	public function testPurge() {
		$this->cache->save('key3', 'value3');
		$this->assertEquals('key3', $this->cache->load('key3'));
		
		// need to get over max_age (default: 0)
		sleep(2);
		
		$this->assertTrue($this->cache->purge());
		$this->assertNull($this->cache->load('key2'));
	}
}
