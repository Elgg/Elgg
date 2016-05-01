<?php
namespace Elgg\lib\elgglib;

use Elgg\CallResult;

class CallTest extends \PHPUnit_Framework_TestCase {

	function tearDown() {
		elgg_clear_plugin_hook_handlers('elgg_call:method', Greeter1::class);
		elgg_clear_plugin_hook_handlers('elgg_call:value', Greeter1::class);
	}

	function testCall() {
		$res = elgg_call('Elgg\\lib\\elgglib\\testFunction', ['Name']);

		$this->assertInstanceOf(CallResult::class, $res);
		$this->assertTrue($res->was_called);
		$this->assertEquals('Hello, Name', $res->value);
	}

	function testTrimsLeadingSlash() {
		$res = elgg_call('\\Elgg\\lib\\elgglib\\testFunction', ['Name']);

		$this->assertEquals('Hello, Name', $res->value);
	}

	function testMissingCall() {
		$res = elgg_call('not_a_real_function', ['Name']);

		$this->assertInstanceOf(CallResult::class, $res);
		$this->assertFalse($res->was_called);
	}

	function testClassNameCall() {
		$res = elgg_call(Greeter1::class, ['Name']);

		$this->assertEquals('Hello, Name', $res->value);
	}

	function testOverrideMethodCall() {
		$handler = function ($h, $t, $v, $p) use (&$args) {
			$args = func_get_args();
			return Greeter2::class;
		};
		elgg_register_plugin_hook_handler('elgg_call:method', Greeter1::class, $handler);

		$res = elgg_call(Greeter1::class, ['Name']);

		$this->assertEquals('Hola, Name', $res->value);

		$expected_params = [
			'method' => Greeter1::class,
			'args' => ['Name'],
		];
		$this->assertEquals($expected_params, $args[3]);
	}

	function testOverrideValue() {
		$handler = function ($h, $t, $v, $p) {
			return "$v!!";
		};
		elgg_register_plugin_hook_handler('elgg_call:value', Greeter1::class, $handler);

		$res = elgg_call(Greeter1::class, ['Name']);

		$this->assertEquals('Hello, Name!!', $res->value);
	}

	function testCancel() {
		elgg_register_plugin_hook_handler('elgg_call:method', Greeter1::class, 'Elgg\Values::getFalse');

		$res = elgg_call(Greeter1::class, ['Name']);

		$this->assertFalse($res->was_called);
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	function testNonStringMethod() {
		elgg_call([]);
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	function testEmptyMethod() {
		elgg_call('');
	}

	/**
	 * @expectedException \RuntimeException
	 */
	function testCannotInstantiate() {
		elgg_call(Greeter3::class, ['Name']);
	}
}

class Greeter1 {
	function __invoke($name) {
		return "Hello, $name";
	}
}

class Greeter2 {
	function __invoke($name) {
		return "Hola, $name";
	}
}

class Greeter3 extends Greeter1 {
	function __construct($arg1) {}
}

function testFunction($name) {
	return "Hello, $name";
}
