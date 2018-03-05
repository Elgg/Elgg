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

	/**
	 * @expectedException \InvalidParameterException
	 * @expectedExceptionMessage Method or function not set in call in expose_method()
	 */
	public function testExposeFunctionNoMethod() {
		elgg_ws_expose_function('', 'test');
	}

	/**
	 * @expectedException \InvalidParameterException
	 * @expectedExceptionMessage Method or function not set in call in expose_method()
	 */
	public function testExposeFunctionNoFunction() {
		elgg_ws_expose_function('test', '');
	}

	/**
	 * @expectedException \InvalidParameterException
	 * @expectedExceptionMessage Parameters array structure is incorrect for call to expose method 'test'
	 */
	public function testExposeFunctionBadParameters() {
		elgg_ws_expose_function('test', 'test', 'BAD');
	}

	/**
	 * @expectedException \InvalidParameterException
	 * @expectedExceptionMessage Parameters array structure is incorrect for call to expose method 'test'
	 */
	public function testExposeFunctionParametersBadArray() {
		elgg_ws_expose_function('test', 'test', ['param1' => 'string']);
	}

	/**
	 * @expectedException \InvalidParameterException
	 * @expectedExceptionMessage Unrecognised http method BAD for api method 'test'
	 */
	public function testExposeFunctionBadHttpMethod() {
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
		$method['description'] = '';
		$method['function'] = 'foo';
		$method['parameters'] = $parameters;
		$method['call_method'] = 'GET';
		$method['require_api_auth'] = false;
		$method['require_user_auth'] = false;
		$method['assoc'] = false;

		$this->assertEquals($API_METHODS['test'], $method);
	}

	public function testUnexposeFunction() {
		global $API_METHODS;
		$API_METHODS = [];

		$this->registerFunction();

		elgg_ws_unexpose_function('test');
		$this->assertEquals([], $API_METHODS);
	}

	/**
	 * @expectedException \APIException
	 * @expectedExceptionMessage Method call 'BAD' has not been implemented.
	 */
	public function testAuthenticateMethodNotImplemented() {
		authenticate_method('BAD');
	}

	/**
	 * @expectedException \APIException
	 * @expectedExceptionMessage Method call failed the API Authentication
	 */
	public function testAuthenticateMethodApiAuth() {
		$this->registerFunction(true);
		authenticate_method('test');
	}

	/**
	 * @expectedException \APIException
	 */
	public function testAuthenticateMethodUserAuth() {
		$this->registerFunction(false, true);
		authenticate_method('test');
	}

	public function testAuthenticateMethod() {
		$this->registerFunction(false, false);
		// anonymous with no user authentication
		$this->assertTrue(authenticate_method('test'));
	}

	/**
	 * @expectedException \APIException
	 * @expectedExceptionMessage Method call 'BAD' has not been implemented.
	 */
	public function testExecuteMethodNotImplemented() {
		execute_method('BAD');
	}

	/**
	 * @expectedException \APIException
	 * @expectedExceptionMessage Function for method 'test' is not callable
	 */
	public function testExecuteMethodNonCallable() {
		elgg_ws_expose_function('test', 'foo');
		execute_method('test');
	}

	/**
	 * @expectedException \CallException
	 * @expectedExceptionMessage test must be called using 'POST'
	 */
	public function testExecuteMethodWrongMethod() {
		$this->registerFunction();

		// GET when it should be a POST
		execute_method('test');
	}

	/**
	 * @expectedException \APIException
	 * @expectedExceptionMessage Invalid parameter found for 'param1' in method 'test'.
	 */
	public function testVerifyParametersTypeNotSet() {
		$params = ['param1' => ['required' => true]];
		elgg_ws_expose_function('test', 'elgg_echo', $params);
		verify_parameters('test', []);
	}

	/**
	 * @expectedException \APIException
	 * @expectedExceptionMessage Missing parameter param1 in method test
	 */
	public function testVerifyParametersMissing() {
		$params = [
			'param1' => [
				'type' => 'int',
				'required' => true
			]
		];
		elgg_ws_expose_function('test', 'elgg_echo', $params);

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
		$this->expectException('APIException');
		$s = serialise_parameters('test', $parameters);
	}

	// api key methods
	public function testApiAuthenticate() {
		$this->markTestSkipped();
		//$this->assertFalse(pam_authenticate(null, "api"));
	}

	/**
	 * @expectedException \APIException
	 * @expectedExceptionMessage Missing API key
	 */
	public function testApiAuthKeyNoKey() {
		api_auth_key();
	}

	/**
	 * @expectedException \APIException
	 * @expectedExceptionMessage Bad API key
	 */
	public function testApiAuthKeyBadKey() {
		set_input('api_key', 'BAD');
		api_auth_key();
	}

	public function testSerialiseParametersCasting() {
		$types = [
			'int' => [
				[
					"0",
					0
				],
				[
					"1",
					1
				],
				[
					" 1",
					1
				],
			],
			'bool' => [
				[
					"0",
					false
				],
				[
					" 1",
					true
				],

				// BC with 2.0
				[
					" false",
					false
				],
				[
					"true",
					false
				],
			],
			'float' => [
				[
					"1.65",
					1.65
				],
				[
					" 1.65 ",
					1.65
				],
			],
			'array' => [
				[
					[
						"2 ",
						" bar"
					],
					[
						"2 ",
						" bar"
					]
				],
				[
					["' \""],
					["' \\\""]
				],
			],
			'string' => [
				[
					" foo ",
					"foo"
				],
			],
		];

		foreach ($types as $type => $tests) {
			foreach ($tests as $test) {
				set_input('param', $test[0]);
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
				$this->assertEquals($test[1], $value);
			}
		}
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
