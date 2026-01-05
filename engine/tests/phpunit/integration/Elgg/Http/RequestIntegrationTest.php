<?php

use Elgg\IntegrationTestCase;

class RequestIntegrationTest extends IntegrationTestCase {
	
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
