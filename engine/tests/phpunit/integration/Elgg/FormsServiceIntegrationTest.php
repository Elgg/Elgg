<?php

namespace Elgg;

use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;

class FormsServiceIntegrationTest extends ActionResponseTestCase {
	
	public function up() {
		$this->createApplication([
			'isolate' => true,
		]);
		
		$views_dir = $this->normalizeTestFilePath('views');
		_elgg_services()->views->autoregisterViews('', "{$views_dir}/default", 'default');
	}
	
	public function testStickyFormSupportDisabledWithError() {
		elgg_register_action('foo/bar', __CLASS__ . '::errorActionController', 'public');
		
		$response = $this->executeAction('foo/bar', [
			'foo' => 'bar',
		]);
		
		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertFalse(elgg_is_sticky_form('foo/bar'));
		
		$event = $this->registerTestingEvent('form:prepare:fields', 'foo/bar', function(\Elgg\Event $incoming_event) {});
		
		elgg_view_form('foo/bar', [
			'class' => 'foo-bar',
		], [
			'baz2' => 'bar2',
		]);
		
		$event->assertNumberOfCalls(1);
		$event->assertValueBefore(['baz2' => 'bar2']);
		$event->assertValueAfter(['baz2' => 'bar2']);
	}
	
	public function testStickyFormSupportEnabledWithError() {
		elgg_register_action('foo/bar', __CLASS__ . '::errorActionController', 'public');
		
		$response = $this->executeAction('foo/bar', [
			'foo' => 'bar',
			'baz2' => 'bar',
			'_elgg_sticky_form_name' => 'foo/bar', // this is added by enabling sticky form support
		]);
		
		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertTrue(elgg_is_sticky_form('foo/bar'));
		
		$event = $this->registerTestingEvent('form:prepare:fields', 'foo/bar', function(\Elgg\Event $incoming_event) {});
		
		elgg_view_form('foo/bar', [
			'class' => 'foo-bar',
			'sticky_enabled' => true,
		], [
			'baz2' => 'bar2',
		]);
		
		$event->assertNumberOfCalls(1);
		$event->assertValueBefore(['baz2' => 'bar2']);
		$event->assertValueAfter([
			'baz2' => 'bar', // overruled by action
			'foo' => 'bar',
		]);
		
		// check that sticky form data is cleared after use
		$this->assertFalse(elgg_is_sticky_form('foo/bar'));
	}
	
	public function testStickyFormSupportEnabledWithIgnoredFieldsWithError() {
		elgg_register_action('foo/bar', __CLASS__ . '::errorActionController', 'public');
		
		$response = $this->executeAction('foo/bar', [
			'foo' => 'bar',
			'baz2' => 'bar',
			'ignored' => 'foo',
			'_elgg_sticky_form_name' => 'foo/bar', // this is added by enabling sticky form support
			'_elgg_sticky_ignored_fields' => 'baz2,ignored', // this is added by sticky form support
		]);
		
		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertTrue(elgg_is_sticky_form('foo/bar'));
		
		$event = $this->registerTestingEvent('form:prepare:fields', 'foo/bar', function(\Elgg\Event $incoming_event) {});
		
		elgg_view_form('foo/bar', [
			'class' => 'foo-bar',
			'sticky_enabled' => true,
		], [
			'baz2' => 'bar2',
		]);
		
		$event->assertNumberOfCalls(1);
		$event->assertValueBefore(['baz2' => 'bar2']);
		$event->assertValueAfter([
			'baz2' => 'bar2', // ignored in action
			'foo' => 'bar',
		]);
		
		// check that sticky form data is cleared after use
		$this->assertFalse(elgg_is_sticky_form('foo/bar'));
	}
	
	public function testStickyFormSupportEnabledWithSuccess() {
		elgg_register_action('foo/bar', __CLASS__ . '::okActionController', 'public');
		
		$response = $this->executeAction('foo/bar', [
			'foo' => 'bar',
			'baz2' => 'bar',
			'_elgg_sticky_form_name' => 'foo/bar', // this is added by enabling sticky form support
		]);
		
		$this->assertInstanceOf(OkResponse::class, $response);
		$this->assertTrue(_elgg_services()->events->hasHandler('response', 'all'));
		$this->assertTrue(elgg_is_sticky_form('foo/bar'));
		
		_elgg_services()->events->triggerResults('response', 'action:foo/bar', [], $response); // this is normally done by the ResponseFactory
		
		$this->assertFalse(elgg_is_sticky_form('foo/bar'));
		
		$event = $this->registerTestingEvent('form:prepare:fields', 'foo/bar', function(\Elgg\Event $incoming_event) {});
		
		elgg_view_form('foo/bar', [
			'class' => 'foo-bar',
			'sticky_enabled' => true,
		], [
			'baz2' => 'bar2',
		]);
		
		$event->assertNumberOfCalls(1);
		$event->assertValueBefore(['baz2' => 'bar2']);
		$event->assertValueAfter(['baz2' => 'bar2']);
	}
	
	public static function errorActionController(\Elgg\Request $request) {
		return elgg_error_response();
	}
	
	public static function okActionController(\Elgg\Request $request) {
		return elgg_ok_response();
	}
}
