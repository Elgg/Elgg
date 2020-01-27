<?php

namespace Elgg\Http;

/**
 * @group HttpService
 * @group UnitTests
 */
class ErrorResponseUnitTest extends ResponseUnitTest {

	public function up() {
		$this->class = ErrorResponse::class;
	}

	public function down() {

	}

	public function testCanConstructWihtoutArguments() {
		$test_class = $this->class;
		$response = new $test_class();
		$this->assertEquals('', $response->getContent());
		$this->assertEquals(ELGG_HTTP_BAD_REQUEST, $response->getStatusCode());
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
	
	public function testConstructWithInvalidStatusCode() {
		$test_class = $this->class;
		$response = new $test_class('foo', 9999);
		$this->assertEquals(ELGG_HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
	}

	// Remaining tests are identical to ResponseUnitTest
}
