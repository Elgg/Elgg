<?php

namespace Elgg;

/**
 * @group UnitTests
 */
class DeprecationWrapperUnitTest extends \Elgg\UnitTestCase {

	public $last_stack_line = '';

	public function up() {
		$this->last_stack_line = '';
	}

	public function down() {

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
		$str = new DeprecationWrapper($str, 'BAD!', 1.8, array($this, 'report'));
		$file = __FILE__;
		$new_str = "$str"; $line = __LINE__;
		$this->assertEquals('Hello', $new_str);
		$this->assertEquals("$file:$line", $this->last_stack_line);
	}

	function testWrapObject() {
		$obj = new DeprecationWrapperTestObj1();
		$obj = new DeprecationWrapper($obj, 'BAD!', 1.8, array($this, 'report'));
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

	function testArrayAccessProxiesObjectArrayAccessMethods() {
		$file = __FILE__;
		$obj = new DeprecationWrapperTestObj2();
		$obj->data['foo'] = 'test';
		$wrapper = new DeprecationWrapper($obj, 'FOO', 1.9, array($this, 'report'));

		$foo = $wrapper['foo']; $line = __LINE__;
		$this->assertEquals('test', $foo);
		$this->assertEquals("$file:$line", $this->last_stack_line);

		$wrapper[0] = 'value'; $line = __LINE__;
		$this->assertEquals('value', $obj->data[0]);
		$this->assertEquals("$file:$line", $this->last_stack_line);

		unset($wrapper[0]); $line = __LINE__;
		$this->assertFalse(isset($obj->data[0]));
		$this->assertEquals("$file:$line", $this->last_stack_line);

		$wrapper[] = 'hello'; $line = __LINE__;
		$key_created = array_search('hello', $obj->data);
		$this->assertFalse($key_created === false);
		$this->assertTrue($key_created !== '');
		$this->assertEquals("$file:$line", $this->last_stack_line);
	}

	function testArrayAccessProxiesProperties() {
		$file = __FILE__;
		$obj = new \stdClass();
		$obj->foo = 'test';
		$wrapper = new DeprecationWrapper($obj, 'FOO', 1.9, array($this, 'report'));

		$foo = $wrapper['foo']; $line = __LINE__;
		$this->assertEquals('test', $foo);
		$this->assertEquals("$file:$line", $this->last_stack_line);

		$wrapper[0] = 'value'; $line = __LINE__;
		$this->assertEquals('value', $obj->{'0'});
		$this->assertEquals("$file:$line", $this->last_stack_line);
	}

}

class DeprecationWrapperTestObj1 {

	public $foo = 'foo';

	public function foo() {
		return 'foo';
	}

	public function __toString() {
		return 'foo';
	}

}

class DeprecationWrapperTestObj2 extends \ArrayObject {

	public $data = array();

	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->data[] = $value;
		} else {
			$this->data[$offset] = $value;
		}
	}

	public function offsetExists($offset) {
		return array_key_exists($offset, $this->data);
	}

	public function offsetUnset($offset) {
		unset($this->data[$offset]);
	}

	public function offsetGet($offset) {
		return isset($this->data[$offset]) ? $this->data[$offset] : null;
	}

}
