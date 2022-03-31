<?php

namespace Elgg;

class HandlerServiceUnitTest extends UnitTestCase {
	
	/**
	 * @var HandlersService
	 */
	protected $service;
	
	public function up() {
		$this->service = _elgg_services()->handlers;
	}
	
	public static function callableHook(\Elgg\Hook $hook) {
		return 'hook';
	}
	
	public function callableEvent(\Elgg\Event $event) {
		return 'event';
	}
	
	public function callableRequest(\Elgg\Request $request) {
		return 'request';
	}
	
	protected function validateCallResult($result, $expected_status, $expected_result, string $expected_object_class) {
		$this->assertIsArray($result);
		$this->assertCount(3, $result);
		
		$this->assertEquals($expected_status, $result[0]);
		$this->assertEquals($expected_result, $result[1]);
		if (is_object($result[2])) {
			$this->assertInstanceOf($expected_object_class, $result[2]);
		} else {
			$this->assertEquals($expected_object_class, $result[2]);
		}
	}
	
	public function testCallUncallable() {
		$result = $this->service->call([$this, 'uncallable'], 'hook', ['unit', 'test']);
		
		$this->validateCallResult($result, false, null, 'hook');
	}
	
	public function testCallHook() {
		$result = $this->service->call([$this, 'callableHook'], 'hook', ['unit', 'test', false, []]);
		
		$this->validateCallResult($result, true, 'hook', \Elgg\Hook::class);
	}
	
	public function testCallEvent() {
		$result = $this->service->call([$this, 'callableEvent'], 'event', ['unit', 'test', []]);
		
		$this->validateCallResult($result, true, 'event', \Elgg\Event::class);
	}
	
	/**
	 * @dataProvider callRequestProvider
	 */
	public function testCallRequest($object_type) {
		$request = $this->prepareHttpRequest();
		$result = $this->service->call([$this, 'callableRequest'], $object_type, [$request]);
		
		$this->validateCallResult($result, true, 'request', \Elgg\Request::class);
	}
	
	public function callRequestProvider() {
		return [
			['middleware'],
			['controller'],
			['action'],
		];
	}
	
	public function testIsCallable() {
		$this->assertTrue($this->service->isCallable([$this, 'callableHook']));
		$this->assertTrue($this->service->isCallable(__CLASS__ .  '::callableHook'));
		$this->assertTrue($this->service->isCallable(\Elgg\Helpers\HooksRegistrationServiceTestInvokable::class));
		
		$this->assertFalse($this->service->isCallable([$this, 'uncallable']));
	}
	
	public function testResolveCallable() {
		$this->assertNotNull($this->service->resolveCallable([$this, 'callableHook']));
		$this->assertNotNull($this->service->resolveCallable(__CLASS__ .  '::callableHook'));
		$this->assertNotNull($this->service->resolveCallable(\Elgg\Helpers\HooksRegistrationServiceTestInvokable::class));
		
		$this->assertNull($this->service->resolveCallable([$this, 'uncallable']));
	}
	
	/**
	 * @dataProvider describeCallableProvider
	 */
	public function testDescribeCallable($callable, $fileroot, $expected) {
		$this->assertStringContainsString($expected, $this->service->describeCallable($callable, $fileroot));
	}
	
	public function describeCallableProvider() {
		return [
			['some_function_name', '', 'some_function_name'],
			[[$this, 'callableHook'], '', '(Elgg\HandlerServiceUnitTest)->callableHook'],
			[[__CLASS__, 'callableHook'], '', 'Elgg\HandlerServiceUnitTest::callableHook'],
			[function() {}, __DIR__, 'HandlerServiceUnitTest.php:105'], // this is very error prone. Please keep an eye on the line number
			[new \Elgg\Helpers\HooksRegistrationServiceTestInvokable(), __FILE__, '(Elgg\Helpers\HooksRegistrationServiceTestInvokable)->__invoke()'],
		];
	}
}
