<?php

namespace Elgg;

use Elgg\Helpers\EventsServiceTestInvokable;
use Elgg\Helpers\TestEventHandler;
use Psr\Log\LogLevel;

class EventsServiceUnitTest extends \Elgg\UnitTestCase {

	protected int $counter = 0;
	protected int $counter2 = 0;
	protected EventsService $events;

	public function up() {
		$this->counter = 0;
		$this->counter2 = 0;
		$this->events = new EventsService(new HandlersService());
		_elgg_services()->logger->disable();
	}
	
	public function down() {
		_elgg_services()->logger->enable();
	}

	public function incrementCounter(): bool {
		$this->counter++;
		return true;
	}
	
	public function incrementCounter2(): bool {
		$this->counter2++;
		return true;
	}

	public function testTriggerCallsRegisteredHandlersAndReturnsTrue() {
		$this->events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));
		$this->events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertTrue($this->events->trigger('foo', 'bar'));
		$this->assertEquals($this->counter, 2);
	}

	public function testFalseStopsPropagationAndReturnsFalse() {
		$this->events->registerHandler('foo', 'bar', 'Elgg\Values::getFalse');
		$this->events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertFalse($this->events->trigger('foo', 'bar'));
		$this->assertEquals($this->counter, 0);
	}

	public function testNullDoesNotStopPropagation() {
		$this->events->registerHandler('foo', 'bar', 'Elgg\Values::getNull');
		$this->events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertTrue($this->events->trigger('foo', 'bar'));
		$this->assertEquals($this->counter, 1);
	}

	public function testUnstoppableEventsCantBeStoppedAndReturnTrue() {
		$this->events->registerHandler('foo', 'bar', 'Elgg\Values::getFalse');
		$this->events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertTrue($this->events->trigger('foo', 'bar', null, array(
			EventsService::OPTION_STOPPABLE => false
		)));
		$this->assertEquals($this->counter, 1);
	}
	
	public function testIndividualTriggersHaveNoSquenceID() {
		$calls = 0;
		$this->events->registerHandler('all', 'non_sequence', function(\Elgg\Event $event) use (&$calls) {
			$calls++;
			$this->assertNull($event->getSequenceID());
		});
		
		$this->events->triggerBefore('foo', 'non_sequence');
		$this->events->trigger('foo', 'non_sequence');
		$this->events->triggerAfter('foo', 'non_sequence');
		$this->events->triggerDeprecated('foo:deprecated', 'non_sequence');
		
		$this->events->triggerResults('results', 'non_sequence');
		$this->events->triggerDeprecatedResults('results:deprecated', 'non_sequence');
		
		$this->assertEquals(6, $calls);
	}
	
	public function testTriggerSequenceHasSquenceID() {
		$calls = 0;
		$sequence_ids = [];
		$this->events->registerHandler('all', 'sequence', function(\Elgg\Event $event) use (&$calls, &$sequence_ids) {
			$calls++;
			$this->assertNotNull($event->getSequenceID());
			$sequence_ids[] = $event->getSequenceID();
		});
		
		$this->events->triggerSequence('foo', 'sequence');
		$this->events->triggerSequence('foo', 'sequence'); // this should give a new unique sequence ID
		$this->events->triggerResultsSequence('results', 'sequence');
		$this->events->triggerResultsSequence('results', 'sequence'); // this should give a new unique sequence ID
		
		$sequence_ids = array_unique($sequence_ids);
		
		$this->assertEquals(12, $calls);
		$this->assertCount(4, $sequence_ids);
	}

	public function testUncallableHandlersAreLogged() {
		$this->events->registerHandler('foo', 'bar', array(new \stdClass(), 'uncallableMethod'));
		
		_elgg_services()->logger->disable();
		$this->events->trigger('foo', 'bar');

		$logged = _elgg_services()->logger->enable();

		$expected = [
			[
				'message' => 'Handler for event [foo, bar] is not callable: (stdClass)->uncallableMethod',
				'level' => 'warning',
			]
		];
		$this->assertSame($expected, $logged);
	}

	public function testEventTypeHintReceivesObject() {
		$handler = new TestEventHandler();

		$this->events->registerHandler('foo', 'bar', $handler);

		$this->assertFalse($this->events->trigger('foo', 'bar', null));
		$this->assertCount(1, TestEventHandler::$invocations);
		$this->assertCount(1, TestEventHandler::$invocations[0]["args"]);
		$this->assertInstanceOf(Event::class, TestEventHandler::$invocations[0]["args"][0]);

		TestEventHandler::$invocations = [];
	}

	public function testInvokableClassNamesGetEventObject() {
		$this->events->registerHandler('foo', 'bar', TestEventHandler::class);
		$this->events->registerHandler('foo', 'bar', TestEventHandler::class);

		$this->assertEquals(false, $this->events->trigger('foo', 'bar', null));
		$this->assertCount(1, TestEventHandler::$invocations);
		$this->assertCount(1, TestEventHandler::$invocations[0]["args"]);
		$this->assertInstanceOf(Event::class, TestEventHandler::$invocations[0]["args"][0]);

		TestEventHandler::$invocations = [];
	}
	
	public function testTriggerDeprecatedWithoutRegisteredHandlers() {
		
		_elgg_services()->logger->disable();
		
		$this->assertTrue($this->events->triggerDeprecated('foo', 'bar', null, 'The event "foo":"bar" has been deprecated', '1.0'));
		
		$logged = _elgg_services()->logger->enable();
		
		$this->assertEquals([], $logged);
	}
	
	public function testTriggerDeprecatedWithRegisteredHandlers() {
		
		$this->events->registerHandler('foo', 'bar', [$this, 'incrementCounter']);
		
		_elgg_services()->logger->disable();
		
		$this->assertTrue($this->events->triggerDeprecated('foo', 'bar', null, 'Do not use it!', '1.0'));
		$this->assertEquals(1, $this->counter);
		
		$logged = _elgg_services()->logger->enable();
		$this->assertCount(1, $logged);
		
		$message_details = $logged[0];
		
		$this->assertArrayHasKey('message', $message_details);
		$this->assertArrayHasKey('level', $message_details);
		$this->assertStringStartsWith("Deprecated in 1.0: The 'foo', 'bar' event is deprecated. Do not use it!", $message_details['message']);
		$this->assertEquals(LogLevel::WARNING, $message_details['level']);
	}
	
	public function testTriggerDeprecatedResultsWithoutRegisteredHandlers() {
		
		_elgg_services()->logger->disable();
		
		$this->assertTrue($this->events->triggerDeprecatedResults('foo', 'bar', [], true, 'The event "foo":"bar" has been deprecated', '1.0'));
		
		$logged = _elgg_services()->logger->enable();
		
		$this->assertEquals([], $logged);
	}
	
	public function testTriggerDeprecatedResultsWithRegisteredHandlers() {
		
		$this->events->registerHandler('foo', 'bar', [$this, 'incrementCounter']);
		
		_elgg_services()->logger->disable();
		
		$this->assertTrue($this->events->triggerDeprecatedResults('foo', 'bar', [], false, 'Do not use it!', '1.0'));
		$this->assertEquals(1, $this->counter);
		
		$logged = _elgg_services()->logger->enable();
		$this->assertCount(1, $logged);
		
		$message_details = $logged[0];
		
		$this->assertArrayHasKey('message', $message_details);
		$this->assertArrayHasKey('level', $message_details);
		$this->assertStringStartsWith("Deprecated in 1.0: The 'foo', 'bar' event is deprecated. Do not use it!", $message_details['message']);
		$this->assertEquals(LogLevel::WARNING, $message_details['level']);
	}
	
	public function testTriggerWithBeforeCallbackNotCalledWithoutRegisteredHandlers() {
		$this->assertTrue($this->events->trigger('foo', 'bar', [], [
			EventsService::OPTION_BEGIN_CALLBACK => [$this, 'incrementCounter'],
		]));
		
		$this->assertEmpty($this->counter);
	}
	
	public function testTriggerWithBeforeCallbackCalledWithRegisteredHandlers() {
		$this->events->registerHandler('foo', 'bar', [$this, 'incrementCounter2']);
		
		$this->assertTrue($this->events->trigger('foo', 'bar', [], [
			EventsService::OPTION_BEGIN_CALLBACK => [$this, 'incrementCounter'],
		]));
		
		$this->assertEquals(1, $this->counter);
		$this->assertEquals(1, $this->counter2);
	}
	
	public function testTriggerResultsWithBeforeCallbackNotCalledWithoutRegisteredHandlers() {
		$this->assertTrue($this->events->triggerResults('foo', 'bar', [], true, [
			EventsService::OPTION_BEGIN_CALLBACK => [$this, 'incrementCounter'],
		]));
		
		$this->assertEmpty($this->counter);
	}
	
	public function testTriggerResultsWithBeforeCallbackCalledWithRegisteredHandlers() {
		$this->events->registerHandler('foo', 'bar', [$this, 'incrementCounter2']);
		
		$this->assertTrue($this->events->triggerResults('foo', 'bar', [], false, [
			EventsService::OPTION_BEGIN_CALLBACK => [$this, 'incrementCounter'],
		]));
		
		$this->assertEquals(1, $this->counter);
		$this->assertEquals(1, $this->counter2);
	}
	
	public function testTriggerWithEndCallbackNotCalledWithoutRegisteredHandlers() {
		$this->assertTrue($this->events->trigger('foo', 'bar', [], [
			EventsService::OPTION_END_CALLBACK => [$this, 'incrementCounter'],
		]));
		
		$this->assertEmpty($this->counter);
	}
	
	public function testTriggerWithEndCallbackCalledWithRegisteredHandlers() {
		$this->events->registerHandler('foo', 'bar', [$this, 'incrementCounter2']);
		
		$this->assertTrue($this->events->trigger('foo', 'bar', [], [
			EventsService::OPTION_END_CALLBACK => [$this, 'incrementCounter'],
		]));
		
		$this->assertEquals(1, $this->counter);
		$this->assertEquals(1, $this->counter2);
	}
	
	public function testTriggerResultsWithEndCallbackNotCalledWithoutRegisteredHandlers() {
		$this->assertTrue($this->events->triggerResults('foo', 'bar', [], true, [
			EventsService::OPTION_END_CALLBACK => [$this, 'incrementCounter'],
		]));
		
		$this->assertEmpty($this->counter);
	}
	
	public function testTriggerResultsWithEndCallbackCalledWithRegisteredHandlers() {
		$this->events->registerHandler('foo', 'bar', [$this, 'incrementCounter2']);
		
		$this->assertTrue($this->events->triggerResults('foo', 'bar', [], false, [
			EventsService::OPTION_END_CALLBACK => [$this, 'incrementCounter'],
		]));
		
		$this->assertEquals(1, $this->counter);
		$this->assertEquals(1, $this->counter2);
	}
	
	public function testTriggerPassesException() {
		$this->events->registerHandler('foo', 'bar', [$this, 'incrementCounter']);
		$this->events->registerHandler('foo', 'bar', function(\Elgg\Event $event) {
			throw new \Elgg\Exceptions\Exception('testing');
		});
		$this->events->registerHandler('foo', 'bar', [$this, 'incrementCounter2']);
		
		$this->expectException(\Elgg\Exceptions\Exception::class);
		$this->events->trigger('foo', 'bar');
	}
	
	public function testTriggerContinuesOnException() {
		$this->events->registerHandler('foo', 'bar', [$this, 'incrementCounter']);
		$this->events->registerHandler('foo', 'bar', function(\Elgg\Event $event) {
			throw new \Elgg\Exceptions\Exception('testing');
		});
		$this->events->registerHandler('foo', 'bar', [$this, 'incrementCounter2']);
		
		$this->events->trigger('foo', 'bar', null, [EventsService::OPTION_CONTINUE_ON_EXCEPTION => true]);
		
		$this->assertEquals(1, $this->counter);
		$this->assertEquals(1, $this->counter2);
	}
	
	public function testTriggerResultsPassesException() {
		$this->events->registerHandler('foo', 'bar', [$this, 'incrementCounter']);
		$this->events->registerHandler('foo', 'bar', function(\Elgg\Event $event) {
			throw new \Elgg\Exceptions\Exception('testing');
		});
		$this->events->registerHandler('foo', 'bar', [$this, 'incrementCounter2']);
		
		$this->expectException(\Elgg\Exceptions\Exception::class);
		$this->events->triggerResults('foo', 'bar');
	}
	
	public function testTriggerResultsContinuesOnException() {
		$this->events->registerHandler('foo', 'bar', function(\Elgg\Event $event) {
			$this->counter++;
			
			return $event->getValue() . 'a';
		});
		$this->events->registerHandler('foo', 'bar', function(\Elgg\Event $event) {
			$this->counter++;
			
			throw new \Elgg\Exceptions\Exception('testing');
		});
		$this->events->registerHandler('foo', 'bar', function(\Elgg\Event $event) {
			$this->counter++;
			
			return $event->getValue() . 'b';
		});
		$this->events->registerHandler('foo', 'bar', function(\Elgg\Event $event) {
			$this->counter++;
			
			throw new \Elgg\Exceptions\Exception('testing');
		});
		$this->events->registerHandler('foo', 'bar', function(\Elgg\Event $event) {
			$this->counter++;
			
			return $event->getValue() . 'c';
		});
		
		$result = $this->events->triggerResults('foo', 'bar', [], '', [EventsService::OPTION_CONTINUE_ON_EXCEPTION => true]);
		
		$this->assertEquals(5, $this->counter);
		$this->assertEquals('abc', $result);
	}
	
	public function testCanRegisterHandlers() {
		$f = function () {
			
		};
		
		$this->assertTrue($this->events->registerHandler('foo', 'bar', 'callback1'));
		$this->assertTrue($this->events->registerHandler('foo', 'bar', $f));
		$this->assertTrue($this->events->registerHandler('foo', 'baz', 'callback3', 100));
		
		$expected = [
			'foo' => [
				'bar' => [
					500 => ['callback1', $f],
				],
				'baz' => [
					100 => ['callback3'],
				],
			],
		];
		
		$this->assertSame($expected, $this->events->getAllHandlers());
		
		// check possibly invalid callbacks
		$this->assertFalse($this->events->registerHandler('foo', 'bar', 1234));
	}
	
	public function testCanUnregisterHandlers() {
		$o = new EventsServiceTestInvokable();
		
		$this->events->registerHandler('foo', 'bar', 'callback1');
		$this->events->registerHandler('foo', 'bar', 'callback2', 100);
		$this->events->registerHandler('foo', 'bar', 'callback2', 150);
		$this->events->registerHandler('foo', 'bar', [$o, '__invoke'], 300);
		$this->events->registerHandler('foo', 'bar', [$o, '__invoke'], 300);
		$this->events->registerHandler('foo', 'bar', [$o, '__invoke'], 300);
		
		$this->events->unregisterHandler('foo', 'bar', 'callback2');
		$this->events->unregisterHandler('foo', 'bar', EventsServiceTestInvokable::KLASS . '::__invoke');
		$this->events->unregisterHandler('foo', 'bar', [EventsServiceTestInvokable::KLASS, '__invoke']);
		$this->events->unregisterHandler('foo', 'bar', [$o, '__invoke']);
		
		$expected = [
			'foo' => [
				'bar' => [
					500 => ['callback1'],
					// only one removed
					150 => ['callback2'],
				]
			]
		];
		$this->assertSame($expected, $this->events->getAllHandlers());
		
		// check unregistering things that aren't registered
		$this->events->unregisterHandler('foo', 'bar', 'not_valid');
	}
	
	public function testCanClearMultipleHandlersAtOnce() {
		$this->events->registerHandler('foo', 'bar', 'callback1');
		$this->events->registerHandler('foo', 'baz', 'callback1', 10);
		$this->events->registerHandler('foo', 'bar', 'callback2', 100);
		$this->events->registerHandler('foo', 'bar', 'callback2', 150);
		
		$expected = [
			'foo' => [
				'baz' => [
					10 => ['callback1'],
				]
			]
		];
		// clearHandlers should remove everything registrered for 'foo', 'bar', but not 'foo', 'baz'
		$this->events->clearHandlers('foo', 'bar');
		
		$this->assertSame($expected, $this->events->getAllHandlers());
	}
	
	public function testOnlyOneHandlerUnregistered() {
		$this->events->registerHandler('foo', 'bar', 'callback');
		$this->events->registerHandler('foo', 'bar', 'callback');
		
		$this->events->unregisterHandler('foo', 'bar', 'callback');
		
		$this->assertSame([500 => ['callback']], $this->events->getAllHandlers()['foo']['bar']);
	}
	
	public function testGetOrderedHandlers() {
		$this->events->registerHandler('foo', 'bar', 'callback1');
		$this->events->registerHandler('foo', 'bar', 'callback2');
		$this->events->registerHandler('all', 'all', 'callback4', 100);
		$this->events->registerHandler('foo', 'baz', 'callback3', 100);
		$this->events->registerHandler('all', 'bar', 'callback5', 110);
		$this->events->registerHandler('foo', 'all', 'callback6', 120);
		
		$expected_foo_bar = [
			'callback4', // first even though it's [all, all]
			'callback5',
			'callback6',
			'callback1',
			'callback2',
		];
		
		$expected_foo_baz = [
			'callback4', // first even though it's [all, all]
			'callback3',
			'callback6',
		];
		
		$this->assertSame($expected_foo_bar, $this->events->getOrderedHandlers('foo', 'bar'));
		$this->assertSame($expected_foo_baz, $this->events->getOrderedHandlers('foo', 'baz'));
	}
	
	public function testCanBackupAndRestoreRegistrations() {
		$this->events->registerHandler('foo', 'bar', 'callback2');
		$this->events->registerHandler('all', 'all', 'callback4', 100);
		$handlers = $this->events->getAllHandlers();
		
		$this->events->backup();
		$this->assertEmpty($this->events->getAllHandlers());
		
		$this->events->restore();
		$this->assertEquals($handlers, $this->events->getAllHandlers());
	}
	
	public function testBackupIsAStack() {
		$this->events->registerHandler('foo', 'bar', 'callback2');
		$handlers1 = $this->events->getAllHandlers();
		$this->events->backup();
		
		$this->events->registerHandler('all', 'all', 'callback4', 100);
		$handlers2 = $this->events->getAllHandlers();
		$this->events->backup();
		
		$this->events->restore();
		$this->assertEquals($handlers2, $this->events->getAllHandlers());
		
		$this->events->restore();
		$this->assertEquals($handlers1, $this->events->getAllHandlers());
	}
	
	public function testStaticCallbacksWithPrecedingSlash() {
		$this->assertTrue($this->events->registerHandler('foo', 'bar', '\MyCustomClass::static_callback'));
		$this->assertTrue($this->events->registerHandler('foo', 'bar', 'MyCustomClass2::static_callback'));
		
		$expected = [
			'foo' => [
				'bar' => [
					500 => ['\MyCustomClass::static_callback', 'MyCustomClass2::static_callback'],
				],
			],
		];
		
		$this->assertSame($expected, $this->events->getAllHandlers());
		
		$this->events->unregisterHandler('foo', 'bar', 'MyCustomClass::static_callback');
		$this->events->unregisterHandler('foo', 'bar', '\MyCustomClass2::static_callback');
		
		$this->assertSame([], $this->events->getAllHandlers());
	}
}
