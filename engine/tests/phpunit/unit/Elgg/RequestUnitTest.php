<?php

namespace Elgg;

class RequestUnitTest extends UnitTestCase {
	
	public function testGetHttpRequest() {
		$http_request = self::prepareHttpRequest('foo');
		
		$request = new Request(_elgg_services()->dic, $http_request);
		
		$this->assertEquals($http_request, $request->getHttpRequest());
	}
}
