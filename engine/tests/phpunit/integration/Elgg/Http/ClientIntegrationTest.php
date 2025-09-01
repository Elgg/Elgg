<?php

namespace Elgg\Http;

use Elgg\IntegrationTestCase;

class ClientIntegrationTest extends IntegrationTestCase {
	
	public function testCanCreateHttpClient() {
		$this->assertInstanceOf(Client::class, elgg_get_http_client());
	}
	
	public function testCanPassOptions() {
		$client = elgg_get_http_client(['foo' => 'bar']);
		
		$config = $this->getInaccessableProperty($client, 'config');
		
		$this->assertArrayHasKey('foo', $config);
		$this->assertEquals('bar', $config['foo']);
	}
}
