<?php

namespace Elgg\WebServices;

use Elgg\IntegrationTestCase;

/**
 * @group WebServices
 */
class ElggCoreWebServicesApiTest extends IntegrationTestCase {

	private $call_method;

	public function up() {
		$this->call_method = get_call_method();
		// Emulate GET request, which is not set in cli mode
		_elgg_services()->request->server->set('REQUEST_METHOD', 'GET');
	}

	/**
	 * Called after each test method.
	 */
	public function down() {
		global $API_METHODS;
		$API_METHODS = [];

		// Restore original request method
		_elgg_services()->request->server->set('REQUEST_METHOD', $this->call_method);
	}

	public function testExposeFunctionNoMethod() {
		$this->expectException(\InvalidParameterException::class);
		$this->expectExceptionMessage(elgg_echo('InvalidParameterException:APIMethodOrFunctionNotSet'));
		elgg_ws_expose_function('', 'test');
	}

	public function testExposeFunctionNoFunction() {
		$this->expectException(\InvalidParameterException::class);
		$this->expectExceptionMessage(elgg_echo('InvalidParameterException:APIMethodOrFunctionNotSet'));
		elgg_ws_expose_function('test', '');
	}

	public function testExposeFunctionBadParameters() {
		$this->expectException(\InvalidParameterException::class);
		$this->expectExceptionMessage(elgg_echo('InvalidParameterException:APIParametersArrayStructure', ['test']));
		elgg_ws_expose_function('test', 'test', 'BAD');
	}

	public function testExposeFunctionParametersBadArray() {
		$this->expectException(\InvalidParameterException::class);
		$this->expectExceptionMessage(elgg_echo('InvalidParameterException:APIParametersArrayStructure', ['test']));
		elgg_ws_expose_function('test', 'test', ['param1' => 'string']);
	}

	public function testExposeFunctionBadHttpMethod() {
		$this->expectException(\InvalidParameterException::class);
		$this->expectExceptionMessage(elgg_echo('InvalidParameterException:UnrecognisedHttpMethod', ['BAD', 'test']));
		elgg_ws_expose_function('test', 'test', null, '', 'BAD');
	}

	public function testExposeFunctionSuccess() {
		global $API_METHODS;
		
		// this is a general test but also tests specifically for setting 'required' correctly
		$parameters = [
			'param1' => [
				'type' => 'int',
				'required' => true
			],
			'param2' => ['type' => 'bool'],
			'param3' => [
				'type' => 'string',
				'required' => false
			],
		];

		$this->assertTrue(elgg_ws_expose_function('test', 'foo', $parameters));

		$parameters = [
			'param1' => [
				'type' => 'int',
				'required' => true
			],
			'param2' => [
				'type' => 'bool',
				'required' => true
			],
			'param3' => [
				'type' => 'string',
				'required' => false
			],
		];
		
		$method = [
			'description' => '',
			'function' => 'foo',
			'parameters' => $parameters,
			'call_method' => 'GET',
			'require_api_auth' => false,
			'require_user_auth' => false,
			'assoc' => false,
		];

		$this->assertEquals($API_METHODS['test'], $method);
	}

	public function testUnexposeFunction() {
		global $API_METHODS;
		$API_METHODS = [];

		$this->registerFunction();

		elgg_ws_unexpose_function('test');
		$this->assertEquals([], $API_METHODS);
	}

	public function testAuthenticateMethodNotImplemented() {
		$this->expectException(\APIException::class);
		$this->expectExceptionMessage(elgg_echo('APIException:MethodCallNotImplemented', ['BAD']));
		authenticate_method('BAD');
	}

	public function testAuthenticateMethodApiAuth() {
		$this->registerFunction(true);
		
		$this->expectException(\APIException::class);
		$this->expectExceptionMessage(elgg_echo('APIException:APIAuthenticationFailed', ['test']));
		authenticate_method('test');
	}

	public function testAuthenticateMethodUserAuth() {
		$this->registerFunction(false, true);
		
		$this->expectException(\APIException::class);
		authenticate_method('test');
	}

	public function testAuthenticateMethod() {
		$this->registerFunction(false, false);
		// anonymous with no user authentication
		$this->assertTrue(authenticate_method('test'));
	}

	public function testExecuteMethodNotImplemented() {
		$this->expectException(\APIException::class);
		$this->expectExceptionMessage(elgg_echo('APIException:MethodCallNotImplemented', ['BAD']));
		execute_method('BAD');
	}

	public function testExecuteMethodNonCallable() {
		elgg_ws_expose_function('test', 'foo');
		
		$this->expectException(\APIException::class);
		$this->expectExceptionMessage(elgg_echo('APIException:FunctionDoesNotExist', ['test']));
		execute_method('test');
	}

	public function testExecuteMethodWrongMethod() {
		$this->registerFunction();

		// GET when it should be a POST
		$this->expectException(\CallException::class);
		$this->expectExceptionMessage(elgg_echo('CallException:InvalidCallMethod', ['test', 'POST']));
		execute_method('test');
	}

	public function testVerifyParametersTypeNotSet() {
		$params = ['param1' => ['required' => true]];
		elgg_ws_expose_function('test', 'elgg_echo', $params);
		
		$this->expectException(\APIException::class);
		$this->expectExceptionMessage(elgg_echo('APIException:InvalidParameter', ['param1', 'test']));
		verify_parameters('test', []);
	}

	public function testVerifyParametersMissing() {
		$params = [
			'param1' => [
				'type' => 'int',
				'required' => true
			]
		];
		elgg_ws_expose_function('test', 'elgg_echo', $params);

		$this->expectException(\APIException::class);
		$this->expectExceptionMessage(elgg_echo('APIException:MissingParameterInMethod', ['param1', 'test']));
		verify_parameters('test', []);
	}

	public function testVerifyParameters() {
		$this->registerFunction();

		$parameters = ['param1' => 0];
		$this->assertTrue(verify_parameters('test', $parameters));
	}

	public function testSerialiseParameters() {

		$evaler = function ($params) {
			$s = serialise_parameters('test', $params);

			return eval('return [' . substr($s, 1) . '];');
		};

		// int and bool
		$this->registerFunction();
		$parameters = [
			'param1' => 1,
			'param2' => 0
		];
		$this->assertEquals([
			1,
			false
		], $evaler($parameters));

		// string
		$test_string = 'testing';
		$this->registerFunction(false, false, ['param1' => ['type' => 'string']]);
		$parameters = ['param1' => $test_string];
		$this->assertEquals([$test_string], $evaler($parameters));

		// test string with " in it
		$test_string = 'test"ing';
		$this->registerFunction(false, false, ['param1' => ['type' => 'string']]);
		$parameters = ['param1' => $test_string];
		$this->assertEquals([$test_string], $evaler($parameters));

		// test string with ' in it
		$test_string = 'test\'ing';
		$this->registerFunction(false, false, ['param1' => ['type' => 'string']]);
		$parameters = ['param1' => $test_string];
		$this->assertEquals([$test_string], $evaler($parameters));

		// test string with \ in it
		$test_string = 'test\\ing';
		$this->registerFunction(false, false, ['param1' => ['type' => 'string']]);
		$parameters = ['param1' => $test_string];
		$this->assertEquals([$test_string], $evaler($parameters));

		// test string with \' in it
		$test_string = "test\\'ing";
		$this->registerFunction(false, false, ['param1' => ['type' => 'string']]);
		$parameters = ['param1' => $test_string];
		$this->assertEquals([$test_string], $evaler($parameters));

		// test string with \ at end
		$test_string = "testing\\";
		$this->registerFunction(false, false, ['param1' => ['type' => 'string']]);
		$parameters = ['param1' => $test_string];
		$this->assertEquals([$test_string], $evaler($parameters));

		// test string reported by twall in #1364
		$test_string = '{"html":"<div><img src=\\"http://foo.com\\"/>Blah Blah</div>"}';
		$this->registerFunction(false, false, ['param1' => ['type' => 'string']]);
		$parameters = ['param1' => $test_string];
		$this->assertEquals([$test_string], $evaler($parameters));

		// float
		$this->registerFunction(false, false, ['param1' => ['type' => 'float']]);
		$parameters = ['param1' => 2.5];
		$this->assertEquals([2.5], $evaler($parameters));

		// indexed array of strings
		$this->registerFunction(false, false, ['param1' => ['type' => 'array']]);
		$parameters = [
			'param1' => [
				'one',
				'two'
			]
		];
		$this->assertEquals([
			[
				'one',
				'two'
			]
		], $evaler($parameters));

		// associative array of strings
		$this->registerFunction(false, false, ['param1' => ['type' => 'array']]);
		$parameters = [
			'param1' => [
				'first' => 'one',
				'second' => 'two'
			]
		];
		$this->assertEquals([
			[
				'first' => 'one',
				'second' => 'two'
			]
		], $evaler($parameters));

		// indexed array of strings (all values cast to strings)
		$this->registerFunction(false, false, ['param1' => ['type' => 'array']]);
		$parameters = [
			'param1' => [
				1,
				2
			]
		];
		$this->assertEquals([
			[
				'1',
				'2'
			]
		], $evaler($parameters));

		// test missing optional param
		$this->registerFunction(false, false, [
			'param1' => [
				'type' => 'int',
				'required' => false
			],
			'param2' => ['type' => 'int'],
		]);
		$parameters = ['param2' => '2'];
		$this->assertEquals([
			null,
			2
		], $evaler($parameters));

		// test unknown type
		$this->registerFunction(false, false, ['param1' => ['type' => 'bad']]);
		$parameters = ['param1' => 'test'];
		
		$this->expectException(\APIException::class);
		serialise_parameters('test', $parameters);
	}

	// api key methods
	public function testApiAuthenticate() {
		$this->markTestSkipped();
	}

	public function testApiAuthKeyNoKey() {
		$this->expectException(\APIException::class);
		$this->expectExceptionMessage(elgg_echo('APIException:MissingAPIKey'));
		api_auth_key();
	}

	public function testApiAuthKeyBadKey() {
		set_input('api_key', 'BAD');
		
		$this->expectException(\APIException::class);
		$this->expectExceptionMessage(elgg_echo('APIException:BadAPIKey'));
		api_auth_key();
	}

	/**
	 * @dataProvider serialiseParametersCastingProvider
	 */
	public function testSerialiseParametersCasting($type, $input, $expected) {
		set_input('param', $input);
		$this->registerFunction(false, false, [
			'param' => ['type' => $type],
		]);

		$serialized = serialise_parameters('test', [
			// get_input() necessary because it does recursive trimming
			'param' => get_input('param'),
		]);

		$serialized = trim($serialized, ", ");

		// evaled
		$value = eval("return $serialized;");
		$this->assertEquals($expected, $value);
	}
	
	public function serialiseParametersCastingProvider() {
		return [
			['int', '0', 0],
			['int', '1', 1],
			['int', ' 1', 1],
			['bool', '0', false],
			['bool', 0, false],
			['bool', ' 0', false],
			['bool', ' 1', true],
			['bool', ' false', false],
			['bool', 'true', true],
			['bool', 'foo', true],
			['float', '1.65', 1.65],
			['float', ' 1.56', 1.56],
			['array', ['2 ', ' bar'], ['2 ', ' bar']],
			['array', ["' \""], ["' \\\""]],
			['string', ' foo ', 'foo'],
		];
	}

	public function testExecuteMethod() {
		$params = [
			'param1' => [
				'type' => 'int',
				'required' => false
			],
			'param2' => [
				'type' => 'bool',
				'required' => true
			],
		];
		elgg_ws_expose_function('test', [
			$this,
			'methodCallback'
		], $params);

		set_input('param1', "2");
		set_input('param2', "1");

		$result = execute_method('test');
		$this->assertInstanceOf(\SuccessResult::class, $result);
		$this->assertEquals([
			2,
			true
		], $result->export()->result);

		set_input('param1', null);
		set_input('param2', "1");

		$result = execute_method('test');
		$this->assertInstanceOf(\SuccessResult::class, $result);
		$this->assertEquals([
			null,
			true
		], $result->export()->result);
	}

	public function testExecuteMethodAssoc() {
		$params = [
			'param1' => [
				'type' => 'int',
				'required' => false
			],
			'param2' => [
				'type' => 'bool',
				'required' => true
			],
		];
		elgg_ws_expose_function('test', [
			$this,
			'methodCallbackAssoc'
		], $params, '', 'GET', false, false, true);

		set_input('param1', "2");
		set_input('param2', "1");

		$result = execute_method('test');
		$this->assertInstanceOf(\SuccessResult::class, $result);
		$this->assertEquals([
			'param1' => 2,
			'param2' => true
		], $result->export()->result);

		set_input('param1', null);
		set_input('param2', "1");

		$result = execute_method('test');
		$this->assertInstanceOf(\SuccessResult::class, $result);
		$this->assertEquals([
			'param1' => null,
			'param2' => true
		], $result->export()->result);
	}

	public function methodCallback() {
		return func_get_args();
	}

	public function methodCallbackAssoc($values) {
		return $values;
	}

	protected function registerFunction($api_auth = false, $user_auth = false, $params = null, $assoc = false) {
		$parameters = [
			'param1' => [
				'type' => 'int',
				'required' => true
			],
			'param2' => [
				'type' => 'bool',
				'required' => false
			],
		];

		if ($params == null) {
			$params = $parameters;
		}

		$callback = ($assoc) ? [
			$this,
			'methodCallbackAssoc'
		] : [
			$this,
			'methodCallback'
		];
		elgg_ws_expose_function('test', $callback, $params, '', 'POST', $api_auth, $user_auth);
	}
}
