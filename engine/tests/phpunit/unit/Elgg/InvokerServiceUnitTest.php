<?php

namespace Elgg;

use Elgg\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;

class InvokerServiceUnitTest extends UnitTestCase {
	
	protected Invoker $invoker;
	protected SessionManagerService $session_manager;
	
	public function up() {
		$this->invoker = _elgg_services()->invoker;
		$this->session_manager = _elgg_services()->session_manager;
	}

	#[DataProvider('callFlagProvider')]
	public function testCallFlags($flag1, $flag2, $getter_function, $setter_function, $default_value) {
		// test default value
		$this->assertEquals($default_value, $this->session_manager->$getter_function());
		
		// test first flag
		$test1 = $this->invoker->call($flag1, function() use ($getter_function, $default_value) {
			$this->assertEquals(!$default_value, $this->session_manager->$getter_function());
			
			return true;
		});
		$this->assertTrue($test1);
		$this->assertEquals($default_value, $this->session_manager->$getter_function());
		
		// test second flag
		$test2 = $this->invoker->call($flag2, function() use ($getter_function, $default_value) {
			$this->assertEquals($default_value, $this->session_manager->$getter_function());
			
			return true;
		});
		$this->assertTrue($test2);
		$this->assertEquals($default_value, $this->session_manager->$getter_function());
		
		// test nesting of calls
		$test3 = $this->invoker->call($flag1, function() use ($flag2, $getter_function, $default_value) {
			$this->assertEquals(!$default_value, $this->session_manager->$getter_function());
			
			$test4 = $this->invoker->call($flag2, function() use ($getter_function, $default_value) {
				$this->assertEquals($default_value, $this->session_manager->$getter_function());
				
				return true;
			});
			$this->assertTrue($test4);
			
			return true;
		});
		$this->assertTrue($test3);
		$this->assertEquals($default_value, $this->session_manager->$getter_function());
	}

	#[DataProvider('callFlagProvider')]
	public function testCallFlagsWithExceptions($flag1, $flag2, $getter_function, $setter_function, $default_value) {
		// test default value
		$this->assertEquals($default_value, $this->session_manager->$getter_function());
		
		// test first flag
		$e = null;
		$called = false;
		try {
			$this->invoker->call($flag1, function () use ($getter_function, $default_value, &$called) {
				$this->assertEquals(!$default_value, $this->session_manager->$getter_function());
				
				$called = true;
				
				throw new InvalidArgumentException();
			});
		} catch (InvalidArgumentException $e) {
		}
		
		$this->assertInstanceOf(InvalidArgumentException::class, $e);
		$this->assertTrue($called);
		$this->assertEquals($default_value, $this->session_manager->$getter_function());
		
		// test second flag
		$e = null;
		$called = false;
		try {
			$this->invoker->call($flag2, function () use ($getter_function, $default_value, &$called) {
				$this->assertEquals($default_value, $this->session_manager->$getter_function());
				
				$called = true;
				
				throw new InvalidArgumentException();
			});
		} catch (InvalidArgumentException $e) {
		}
		
		$this->assertInstanceOf(InvalidArgumentException::class, $e);
		$this->assertTrue($called);
		$this->assertEquals($default_value, $this->session_manager->$getter_function());
		
		// test nesting of calls
		$e = null;
		$called = 0;
		try {
			$this->invoker->call($flag1, function () use ($flag2, $getter_function, $default_value, &$called) {
				$this->assertEquals(!$default_value, $this->session_manager->$getter_function());
				
				$called++;
				
				try {
					$this->invoker->call($flag2, function () use ($getter_function, $default_value, &$called) {
						$this->assertEquals($default_value, $this->session_manager->$getter_function());
						
						$called++;
						
						throw new InvalidArgumentException();
					});
				} catch (InvalidArgumentException $e) {
				}
				
				throw new InvalidArgumentException();
			});
		} catch (InvalidArgumentException $e) {
		}
		
		$this->assertInstanceOf(InvalidArgumentException::class, $e);
		$this->assertEquals(2, $called);
		$this->assertEquals($default_value, $this->session_manager->$getter_function());
		
		// at the end the default should be restored
		$this->assertEquals($default_value, $this->session_manager->$getter_function());
	}
	
	public static function callFlagProvider(): array {
		return [
			[ELGG_IGNORE_ACCESS, ELGG_ENFORCE_ACCESS, 'getIgnoreAccess', 'setIgnoreAccess', false],
			[ELGG_SHOW_DISABLED_ENTITIES, ELGG_HIDE_DISABLED_ENTITIES, 'getDisabledEntityVisibility', 'setDisabledEntityVisibility', false],
			[ELGG_SHOW_DELETED_ENTITIES, ELGG_HIDE_DELETED_ENTITIES, 'getDeletedEntityVisibility', 'setDeletedEntityVisibility', false],
		];
	}
}
