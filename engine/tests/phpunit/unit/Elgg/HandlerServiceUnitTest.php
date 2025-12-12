<?php

namespace Elgg;

use PHPUnit\Framework\Attributes\DataProvider;

class HandlerServiceUnitTest extends UnitTestCase {
	
	/**
	 * @var HandlersService
	 */
	protected $service;
	
	public function up() {
		$this->service = _elgg_services()->handlers;
	}
	
	public function callableEvent(\Elgg\Event $event) {
		return 'event';
	}
	
	public static function staticCallableEvent(\Elgg\Event $event) {
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
		_elgg_services()->logger->disable();
		$result = $this->service->call([$this, 'uncallable'], 'hook', ['unit', 'test']);
		_elgg_services()->logger->enable();
		
		$this->validateCallResult($result, false, null, 'hook');
	}
		
	public function testCallEvent() {
		$result = $this->service->call([$this, 'callableEvent'], 'event', ['unit', 'test', [], []]);
		
		$this->validateCallResult($result, true, 'event', \Elgg\Event::class);
	}

	#[DataProvider('callRequestProvider')]
	public function testCallRequest($object_type) {
		$request = $this->prepareHttpRequest();
		$result = $this->service->call([$this, 'callableRequest'], $object_type, [$request]);
		
		$this->validateCallResult($result, true, 'request', \Elgg\Request::class);
	}
	
	public static function callRequestProvider() {
		return [
			['middleware'],
			['controller'],
			['action'],
		];
	}
	
	public function testIsCallable() {
		$this->assertTrue($this->service->isCallable([$this, 'callableEvent']));
		$this->assertTrue($this->service->isCallable(__CLASS__ .  '::staticCallableEvent'));
		$this->assertTrue($this->service->isCallable(\Elgg\Helpers\EventsServiceTestInvokable::class));
		
		$this->assertFalse($this->service->isCallable([$this, 'uncallable']));
	}
	
	public function testResolveCallable() {
		$this->assertNotNull($this->service->resolveCallable([$this, 'callableEvent']));
		$this->assertNotNull($this->service->resolveCallable(__CLASS__ .  '::staticCallableEvent'));
		$this->assertNotNull($this->service->resolveCallable(\Elgg\Helpers\EventsServiceTestInvokable::class));
		
		$this->assertNull($this->service->resolveCallable([$this, 'uncallable']));
	}
	
	public function testDescribeInstancedCallable() {
		$this->assertStringContainsString('(Elgg\HandlerServiceUnitTest)->callableEvent', $this->service->describeCallable([$this, 'callableEvent'], ''));
	}

	#[DataProvider('describeCallableProvider')]
	public function testDescribeCallable($callable, $fileroot, $expected) {
		$this->assertStringContainsString($expected, $this->service->describeCallable($callable, $fileroot));
	}
	
	public static function describeCallableProvider() {
		return [
			['some_function_name', '', 'some_function_name'],
			[[__CLASS__, 'callableEvent'], '', 'Elgg\HandlerServiceUnitTest::callableEvent'],
			[function() {}, __DIR__, 'HandlerServiceUnitTest.php:' . __LINE__],
			[new \Elgg\Helpers\EventsServiceTestInvokable(), __FILE__, '(Elgg\Helpers\EventsServiceTestInvokable)->__invoke()'],
		];
	}
}
