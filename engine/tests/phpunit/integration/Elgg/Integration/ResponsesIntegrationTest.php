<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;

class ResponsesIntegrationTest extends IntegrationTestCase {
	
	public function up() {
		
	}
	
	public function down() {
		
	}
	
	public function testErrorResponse() {
		$result = elgg_error_response();
		
		$this->assertEquals(ELGG_HTTP_BAD_REQUEST, $result->getStatusCode());
	}
}
