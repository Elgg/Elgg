<?php

namespace Elgg\Database;

use Elgg\IntegrationTestCase;

class HMACCacheTableIntegrationTest extends IntegrationTestCase {

	/**
	 * @var HMACCacheTable
	 */
	protected $service;
	
	/**
	 * @var string
	 */
	protected $key;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->service = _elgg_services()->hmacCacheTable;
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		// cleanup left over keys
		if (isset($this->key)) {
			$this->service->deleteHMAC($this->key);
		}
	}
	
	public function testCanStoreHmac() {
		$this->key = $hmac = 'foo';
		
		$this->assertNotFalse($this->service->storeHMAC($hmac));
	}
	
	public function testCanLoadHmac() {
		$this->key = $hmac = 'foo2';
		
		$this->assertNotFalse($this->service->storeHMAC($hmac));
		
		$this->assertEquals($hmac, $this->service->loadHMAC($hmac));
	}
	
	public function testCanDeleteHmac() {
		$this->key = $hmac = 'foo3';
		
		$this->assertNotFalse($this->service->storeHMAC($hmac));
		
		$this->assertNotEmpty($this->service->deleteHMAC($hmac));
		$this->assertEmpty($this->service->loadHMAC($hmac));
	}
}
