<?php

namespace Elgg;

class RequestUnitTest extends UnitTestCase {
	
	public function testGetHttpRequest() {
		$http_request = self::prepareHttpRequest('foo');
		
		$request = new Request(elgg(), $http_request);
		
		$this->assertEquals($http_request, $request->getHttpRequest());
	}
}
