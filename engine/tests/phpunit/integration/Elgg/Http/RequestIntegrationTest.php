<?php

namespace Elgg\Http;

use Elgg\IntegrationTestCase;

class RequestIntegrationTest extends IntegrationTestCase {
	
	public function up() {
		_elgg_services()->events->backup();
		_elgg_services()->events->registerHandler('sanitize', 'input', \Elgg\Input\ValidateInputHandler::class, 1);
		_elgg_services()->events->registerHandler('attributes', 'htmlawed', '\Elgg\Input\ValidateInputHandler::sanitizeStyles');
	}
	
	public function down() {
		_elgg_services()->events->restore();
	}
	
	public function testGetParamFiltersInput() {
		$request = $this->prepareHttpRequest('action/foo', 'GET', ['foo' => 'very<script>alert("welcome");</script><b>bold</b>text'], 0, false);

		$event = $this->registerTestingEvent('sanitize', 'input', function() use ($request) {
			$this->assertEquals('input', $request->getContextStack()->peek());
		});
				
		$this->assertEquals('very<script>alert("welcome");</script><b>bold</b>text', $request->getParam('foo', null, false));
		$this->assertEquals('veryalert("welcome");<b>bold</b>text', $request->getParam('foo', null, true));
		
		$event->assertNumberOfCalls(1);

		$event->unregister();
	}
}
