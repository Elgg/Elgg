<?php

namespace Elgg\Http;

/**
 * @group HttpService
 * @group UnitTests
 */
class OkResponseUnitTest extends ResponseUnitTestCase {

	public function getReponseClassName(): string {
		return OkResponse::class;
	}

	public function testCanConstructWihtoutArguments() {
		$test_class = $this->getReponseClassName();
		$response = new $test_class();
		
		$this->assertEquals('', $response->getContent());
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertNull($response->getForwardURL());
		$this->assertEquals([], $response->getHeaders());
	}

	public function testCanConstructWithArguments() {
		$content = 'foo';
		$status_code = ELGG_HTTP_PARTIAL_CONTENT;
		$forward_url = REFERRER;

		$test_class = $this->getReponseClassName();
		$response = new $test_class($content, $status_code, $forward_url);

		$this->assertEquals($content, $response->getContent());
		$this->assertEquals($status_code, $response->getStatusCode());
		$this->assertEquals($forward_url, $response->getForwardURL());
		$this->assertEquals([], $response->getHeaders());
	}
}
