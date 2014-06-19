<?php

class Elgg_DeprecationWrapperTest extends PHPUnit_Framework_TestCase {

	public $last_stack_line = '';

	public function setUp() {
		$this->last_stack_line = '';
	}

	// to some extent this also tests the stack trace operation, but that's OK
	public function report($msg, $version, $backtrace_level) {
		$backtrace = debug_backtrace();
		// never show this call.
		array_shift($backtrace);
		$i = count($backtrace);

		foreach ($backtrace as $trace) {
			$this->last_stack_line = "{$trace['file']}:{$trace['line']}";
			$i--;
			if ($backtrace_level > 0) {
				if ($backtrace_level <= 1) {
					break;
				}
				$backtrace_level--;
			}
		}
	}

	public function testWrapString() {
		$str = 'Hello';
		$str = new Elgg_DeprecationWrapper($str, 'BAD!', 1.8, array($this, 'report'));
		$file = __FILE__;
		$new_str = "$str"; $line = __LINE__;
		$this->assertEquals('Hello', $new_str);
		$this->assertEquals("$file:$line", $this->last_stack_line);
	}

	public function testWrapObject() {
		$obj = new Elgg_DeprecationWrapperTestObj();
		$obj = new Elgg_DeprecationWrapper($obj, 'BAD!', 1.8, array($this, 'report'));
		$file = __FILE__;
		$foo = $obj->foo; $line = __LINE__;
		$this->assertEquals($foo, 'foo');
		$this->assertEquals("$file:$line", $this->last_stack_line);

		$foo = $obj->foo(); $line = __LINE__;
		$this->assertEquals($foo, 'foo');
		$this->assertEquals("$file:$line", $this->last_stack_line);

		$foo = "$obj"; $line = __LINE__;
		$this->assertEquals($foo, 'foo');
		$this->assertEquals("$file:$line", $this->last_stack_line);
	}

	public function testArrayAccessToObject() {
		$obj = new stdClass();
		$obj->foo = 'test';
		$wrapper = new Elgg_DeprecationWrapper($obj, 'FOO', 1.9);
		$this->assertEquals('test', $wrapper['foo']);

		$wrapper[0] = 'value';
		$this->assertEquals('value', $wrapper[0]);
	}
}

class Elgg_DeprecationWrapperTestObj {
	public $foo = 'foo';
	public function foo() { return 'foo'; }
	public function __toString() { return 'foo'; }
}
