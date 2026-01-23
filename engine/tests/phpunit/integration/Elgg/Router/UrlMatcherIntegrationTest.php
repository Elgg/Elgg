<?php

namespace Elgg\Router;

use Elgg\IntegrationTestCase;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class UrlMatcherIntegrationTest extends IntegrationTestCase {
	
	public function up() {
		$this->createApplication([
			'isolate' => true,
		]);
	}
	
	public function testExisingRouteMatchDoesntTriggerEvent() {
		elgg_register_route('foo', [
			'path' => '/foo',
			'handler' => function(\Elgg\Request $request) {
				return elgg_ok_response('hello');
			},
		]);
		
		$event_handler = $this->registerTestingEvent('route:match', 'system', function(\Elgg\Event $event) {});
		
		$route = _elgg_services()->urlMatcher->match('/foo');
		
		$this->assertIsArray($route);
		
		$event_handler->assertNumberOfCalls(0);
	}
	
	public function testNonExisingRouteMatchTriggersEventWithResults() {
		$this->assertNull(elgg_get_route_for_url('/foo'));
		
		$event_handler = $this->registerTestingEvent('route:match', 'system', function(\Elgg\Event $event) {
			return [
				'route' => 'foo:match',
				'path' => '/foo',
				'file' => 'foo.php',
			];
		});
		
		$this->assertNotNull(elgg_get_route_for_url('/foo'));
		$route = _elgg_services()->urlMatcher->match('/foo');
		
		$this->assertIsArray($route);
		$event_handler->assertNumberOfCalls(1);
		$event_handler->assertValueBefore(null);
		$event_handler->assertParamBefore('pathinfo', '/foo');
	}
	
	public function testNonExisingRouteMatchTriggersEventWithoutResults() {
		$event_handler = $this->registerTestingEvent('route:match', 'system', function(\Elgg\Event $event) {});
		
		try {
			$route = _elgg_services()->urlMatcher->match('/foo');
		} catch (ResourceNotFoundException $e) {
			$route = null;
		}
		
		$this->assertEmpty($route);
		
		$event_handler->assertNumberOfCalls(1);
		$event_handler->assertValueBefore(null);
		$event_handler->assertParamBefore('pathinfo', '/foo');
		$event_handler->assertValueAfter(null);
		
		$this->assertInstanceOf(ResourceNotFoundException::class, $e);
	}
}
