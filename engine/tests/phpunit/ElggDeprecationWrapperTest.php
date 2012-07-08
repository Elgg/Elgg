<?php

class ElggDeprecationWrapperTest extends PHPUnit_Framework_TestCase {

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

	function testWrapString() {
		$str = 'Hello';
		$str = new ElggDeprecationWrapper($str, 'BAD!', 1.8, array($this, 'report'));
		$file = __FILE__;
		$new_str = "$str"; $line = __LINE__;
		$this->assertEquals('Hello', $new_str);
		$this->assertEquals("$file:$line", $this->last_stack_line);
	}

	function testWrapObject() {
		$obj = new ElggDeprecationWrapperTestObj();
		$obj = new ElggDeprecationWrapper($obj, 'BAD!', 1.8, array($this, 'report'));
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
}

class ElggDeprecationWrapperTestObj {
	public $foo = 'foo';
	public function foo() { return 'foo'; }
	public function __toString() { return 'foo'; }
}