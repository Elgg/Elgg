<?php

namespace Elgg\Http;

/**
 * @group HttpService
 */
class ErrorResponseTest extends OkResponseTest {

	public function setUp() {
		$this->class = ErrorResponse::class;
	}

	public function testCanConstructWihtoutArguments() {
		$test_class = $this->class;
		$response = new $test_class();
		$this->assertEquals('', $response->getContent());
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertEquals(REFERRER, $response->getForwardURL());
		$this->assertEquals([], $response->getHeaders());
	}

	public function testCanConstructWithArguments() {
		$error = 'foo';
		$status_code = ELGG_HTTP_NOT_FOUND;
		$forward_url = REFERRER;

		$test_class = $this->class;
		$response = new $test_class($error, $status_code, $forward_url);

		$this->assertEquals($error, $response->getContent());
		$this->assertEquals($status_code, $response->getStatusCode());
		$this->assertEquals($forward_url, $response->getForwardURL());
		$this->assertEquals([], $response->getHeaders());
	}

	// Remaining tests are identical to OkResponseTest
}
