<?php

namespace Elgg\Http;

/**
 * @group HttpService
 * @group UnitTests
 */
class RedirectResponseUnitTest extends ResponseUnitTest {

	public function up() {
		$this->class = RedirectResponse::class;
	}

	public function down() {

	}

	public function testCanConstructWihtoutArguments() {
		$test_class = $this->class;
		$response = new $test_class();
		$this->assertEquals('', $response->getContent());
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
		$this->assertEquals(REFERRER, $response->getForwardURL());
		$this->assertEquals([], $response->getHeaders());
	}

	public function testCanConstructWithArguments() {
		$status_code = ELGG_HTTP_PERMANENTLY_REDIRECT;
		$forward_url = REFERRER;

		$test_class = $this->class;
		$response = new $test_class($forward_url, $status_code);

		$this->assertEquals('', $response->getContent());
		$this->assertEquals($status_code, $response->getStatusCode());
		$this->assertEquals($forward_url, $response->getForwardURL());
		$this->assertEquals([], $response->getHeaders());
	}

	// Remaining tests are identical to ResponseUnitTest
}
