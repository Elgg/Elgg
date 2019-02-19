<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCaseTest;

class ResponsesIntegrationTest extends IntegrationTestCaseTest {
	
	public function testErrorResponse() {
		$result = elgg_error_response();
		
		$this->assertEquals(ELGG_HTTP_OK, $result->getStatusCode());
	}
}
