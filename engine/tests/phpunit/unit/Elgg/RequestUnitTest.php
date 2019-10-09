<?php

namespace Elgg;

class RequestUnitTest extends UnitTestCase {
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\BaseTestCase::up()
	 */
	public function up() {
		
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\BaseTestCase::down()
	 */
	public function down() {
		
	}
	
	public function testGetHttpRequest() {
		$http_request = self::prepareHttpRequest('foo');
		
		$request = new Request(_elgg_services()->dic, $http_request);
		
		$this->assertEquals($http_request, $request->getHttpRequest());
	}
}
