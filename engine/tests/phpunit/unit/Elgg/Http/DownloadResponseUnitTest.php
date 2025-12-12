<?php

namespace Elgg\Http;

use PHPUnit\Framework\Attributes\DataProvider;

class DownloadResponseUnitTest extends ResponseUnitTestCase {
	
	public function getReponseClassName(): string {
		return DownloadResponse::class;
	}
	
	public function testCanConstructWihtoutArguments() {
		$test_class = $this->getReponseClassName();
		$response = new $test_class();
		
		$this->assertEquals('', $response->getContent());
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertNull($response->getForwardURL());
		$this->assertEquals([
			'Content-Type' => 'application/octet-stream; charset=utf-8',
			'Cache-Control' => 'no-store',
			'Content-Disposition' => 'attachment',
		], $response->getHeaders());
	}
	
	public function testCanConstructWithArguments() {
		$content = 'foo';
		$status_code = ELGG_HTTP_PARTIAL_CONTENT;
		
		$test_class = $this->getReponseClassName();
		$response = new $test_class($content, $status_code, REFERRER);
		
		$this->assertEquals($content, $response->getContent());
		$this->assertEquals($status_code, $response->getStatusCode());
		$this->assertNull($response->getForwardURL());
		$this->assertEquals([
			'Content-Type' => 'application/octet-stream; charset=utf-8',
			'Cache-Control' => 'no-store',
			'Content-Disposition' => 'attachment',
			'Content-Length' => strlen($content),
		], $response->getHeaders());
	}
	
	public function testSetFilename() {
		$test_class = $this->getReponseClassName();
		$response = new $test_class();
		
		// test attachment
		$response->setFilename('foo');
		
		$headers = $response->getHeaders();
		$this->assertIsArray($headers);
		$this->assertArrayHasKey('Content-Disposition', $headers);
		$this->assertStringContainsString('filename="foo"', $headers['Content-Disposition']);
		$this->assertStringContainsString('attachment', $headers['Content-Disposition']);
		
		// test inline
		$response->setHeaders([]); // need to clean headers
		$response->setFilename('bar', true);
		
		$headers = $response->getHeaders();
		$this->assertIsArray($headers);
		$this->assertArrayHasKey('Content-Disposition', $headers);
		$this->assertStringContainsString('filename="bar"', $headers['Content-Disposition']);
		$this->assertStringContainsString('inline', $headers['Content-Disposition']);
		
		// test trying to overrule filename fails
		$response->setFilename('notset');
		
		$headers = $response->getHeaders();
		$this->assertIsArray($headers);
		$this->assertArrayHasKey('Content-Disposition', $headers);
		$this->assertStringNotContainsString('filename="notset"', $headers['Content-Disposition']);
		$this->assertStringNotContainsString('attachment', $headers['Content-Disposition']);
	}
	
	/**
	 * Overruled tests from parent because of changes to the DownloadResponse
	 */

	#[DataProvider('validForwardURLsProvider')]
	public function testCanSetForwardURL($value) {
		$test_class = $this->getReponseClassName();
		$response = new $test_class();
		
		$response->setForwardURL($value);
		$this->assertNull($response->getForwardURL());
	}
	
	public function testCanSetHeaders() {
		$test_class = $this->getReponseClassName();
		$response = new $test_class();
		$this->assertEquals([
			'Content-Type' => 'application/octet-stream; charset=utf-8',
			'Cache-Control' => 'no-store',
			'Content-Disposition' => 'attachment',
		], $response->getHeaders());
		
		$response->setHeaders(['Content-Type' => 'application/json']);
		$this->assertEquals([
			'Content-Type' => 'application/json',
			'Cache-Control' => 'no-store',
			'Content-Disposition' => 'attachment',
		], $response->getHeaders());
	}
}
