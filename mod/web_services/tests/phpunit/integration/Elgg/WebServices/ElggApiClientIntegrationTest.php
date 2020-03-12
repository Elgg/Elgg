<?php

namespace Elgg\WebServices;

use Elgg\IntegrationTestCase;

class ElggApiClientIntegrationTest extends IntegrationTestCase {

	/**
	 * {@inheritDoc}
	 */
	public function up() {
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
	}
	
	public function testConstructor() {
		$client = new ElggApiClient('http://localhost');
		
		$this->assertInstanceOf(ElggApiClient::class, $client);
		$this->assertEquals('http://localhost', $client->getUrl());
	}
	
	public function testGetSetUrl() {
		// test set by constructor
		$client = new ElggApiClient('http://localhost');
		$this->assertEquals('http://localhost', $client->getUrl());
		
		// test setter
		$this->assertInstanceOf(ElggApiClient::class, $client->setUrl('http://hostlocal'));
		$this->assertEquals('http://hostlocal', $client->getUrl());
	}
	
	public function testGetSetParams() {
		// test set by constructor
		$client = new ElggApiClient('http://localhost', ['foo' => 'bar']);
		$this->assertEquals(['foo' => 'bar'], $client->getParams());
		
		// test setter
		$this->assertInstanceOf(ElggApiClient::class, $client->setParams(['bar' => 'foo']));
		$this->assertEquals(['bar' => 'foo'], $client->getParams());
	}
	
	/**
	 * @dataProvider validMethodProvider
	 */
	public function testGetSetMethod($method) {
		// test set by constructor
		$client = new ElggApiClient('http://localhost', [], $method);
		$this->assertEquals(strtoupper($method), $client->getMethod());
		
		// test setter
		$this->assertInstanceOf(ElggApiClient::class, $client->setMethod($method));
		$this->assertEquals(strtoupper($method), $client->getMethod());
	}
	
	public function validMethodProvider() {
		return [
			['get'],
			['GET'],
			['post'],
			['POST'],
		];
	}
	
	/**
	 * @dataProvider invalidMethodProvider
	 */
	public function testConstructorWithInvalidMethod($method) {
		$this->expectException(\APIException::class);
		new ElggApiClient('http://localhost', [], $method);
	}
	
	/**
	 * @dataProvider invalidMethodProvider
	 */
	public function testSetMethodWithInvalidMethod($method) {
		$client = new ElggApiClient('http://localhost');
		
		$this->expectException(\APIException::class);
		$client->setMethod($method);
	}
	
	public function invalidMethodProvider() {
		return [
			['PUT'],
			['HEAD'],
			['dummy'],
		];
	}
	
	public function testSetApiKeys() {
		$client = new ElggApiClient('http://localhost');
		
		$reflection_public = new \ReflectionProperty(ElggApiClient::class, 'public_api_key');
		$reflection_public->setAccessible(true);
		
		$reflection_private = new \ReflectionProperty(ElggApiClient::class, 'private_api_key');
		$reflection_private->setAccessible(true);
		
		$client->setApiKeys('foo', 'bar');
		
		$this->assertEquals('foo', $reflection_public->getValue($client));
		$this->assertEquals('bar', $reflection_private->getValue($client));
	}
	
	public function testExecuteRequest() {
		$this->markTestIncomplete();
	}
	
	public function testAddPostHashHeaders() {
		$this->markTestIncomplete();
	}
	
	public function testAddHMACHeaders() {
		$this->markTestIncomplete();
	}
	
	public function testPrepareRequestMovesQueryParamsToParamsOnGetRequests() {
		$client = new ElggApiClient('http://localhost?api=foo&var1=bar');
		$this->assertEquals([], $client->getParams());
		
		$reflection = new \ReflectionMethod(ElggApiClient::class, 'prepareRequest');
		$reflection->setAccessible(true);
		
		$reflection->invoke($client);
		
		$this->assertEquals([
			'api' => 'foo',
			'var1' => 'bar',
		], $client->getParams());
	}
	
	public function testPrepareRequestMovesMethodParamToUrlOnPostRequest() {
		$client = new ElggApiClient('http://localhost?api=foo&var1=bar', ['method' => 'system.api.list'], 'POST');
		$this->assertEquals(['method' => 'system.api.list'], $client->getParams());
		
		$reflection = new \ReflectionMethod(ElggApiClient::class, 'prepareRequest');
		$reflection->setAccessible(true);
		
		$reflection->invoke($client);
		
		$this->assertEquals([
			'api' => 'foo',
			'var1' => 'bar',
		], $client->getParams());
		$this->assertEquals('http://localhost?method=system.api.list', $client->getUrl());
	}
}
