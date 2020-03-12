<?php

namespace Elgg\Database;

use Elgg\UnitTestCase;

class HMACCacheTableUnitTest extends UnitTestCase {

	protected $service;
	
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
		
	}
	
	public function testCanStoreHmac() {
		$hmac = 'foo';
		
		$this->assertNotFalse($this->service->storeHMAC($hmac));
	}
	
	public function testCanLoadHmac() {
		$hmac = 'foo2';
		
		$this->assertNotFalse($this->service->storeHMAC($hmac));
		
		$this->assertEquals($hmac, $this->service->loadHMAC($hmac));
	}
	
	public function testCanDeleteHmac() {
		$hmac = 'foo3';
		
		$this->assertNotFalse($this->service->storeHMAC($hmac));
		
		$this->assertNotEmpty($this->service->deleteHMAC($hmac));
		$this->assertEmpty($this->service->loadHMAC($hmac));
	}
}
