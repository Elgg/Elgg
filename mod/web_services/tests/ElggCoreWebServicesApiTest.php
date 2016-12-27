<?php

/**
 * Elgg Test Web Services - General Web Service API
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreWebServicesApiTest extends ElggCoreUnitTest {

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		global $API_METHODS;
		$API_METHODS = array();
	}

// elgg_ws_expose_function
	public function testExposeFunctionNoMethod() {
		try {
			@elgg_ws_expose_function();
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'InvalidParameterException');
			$this->assertIdentical($e->getMessage(), elgg_echo('InvalidParameterException:APIMethodOrFunctionNotSet'));
		}
	}

	public function testExposeFunctionNoFunction() {
		try {
			@elgg_ws_expose_function('test');
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'InvalidParameterException');
			$this->assertIdentical($e->getMessage(), elgg_echo('InvalidParameterException:APIMethodOrFunctionNotSet'));
		}
	}

	public function testExposeFunctionBadParameters() {
		try {
			@elgg_ws_expose_function('test', 'test', 'BAD');
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'InvalidParameterException');
			$this->assertIdentical($e->getMessage(), sprintf(elgg_echo('InvalidParameterException:APIParametersArrayStructure'), 'test'));
		}
	}

	public function testExposeFunctionParametersBadArray() {
		try {
			elgg_ws_expose_function('test', 'test', array('param1' => 'string'));
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'InvalidParameterException');
			$this->assertIdentical($e->getMessage(), sprintf(elgg_echo('InvalidParameterException:APIParametersArrayStructure'), 'test'));
		}
	}

	public function testExposeFunctionBadHttpMethod() {
		try {
			@elgg_ws_expose_function('test', 'test', null, '', 'BAD');
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'InvalidParameterException');
			$this->assertIdentical($e->getMessage(), sprintf(elgg_echo('InvalidParameterException:UnrecognisedHttpMethod'), 'BAD', 'test'));
		}
	}

	public function testExposeFunctionSuccess() {
		global $API_METHODS;
		// this is a general test but also tests specifically for setting 'required' correctly
		$parameters = array('param1' => array('type' => 'int', 'required' => true), 
							'param2' => array('type' => 'bool'),
							'param3' => array('type' => 'string', 'required' => false), );
		
		$this->assertTrue(elgg_ws_expose_function('test', 'foo', $parameters));
		
		$parameters = array('param1' => array('type' => 'int', 'required' => true), 
							'param2' => array('type' => 'bool', 'required' => true),
							'param3' => array('type' => 'string', 'required' => false), );
		$method['description'] = '';
		$method['function'] = 'foo';
		$method['parameters'] = $parameters;
		$method['call_method'] = 'GET'; 
		$method['require_api_auth'] = false;
		$method['require_user_auth'] = false;
		$method['assoc'] = false;

		$this->assertIdentical($method, $API_METHODS['test']);
	}

// elgg_ws_unexpose_function
	public function testUnexposeFunction() {
		global $API_METHODS;

		$this->registerFunction();

		elgg_ws_unexpose_function('test');
		$this->assertIdentical(array(), $API_METHODS);
	}

// authenticate_method
	public function testAuthenticateMethodNotImplemented() {
		try {
			authenticate_method('BAD');
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'APIException');
			$this->assertIdentical($e->getMessage(), sprintf(elgg_echo('APIException:MethodCallNotImplemented'), 'BAD'));
		}
	}

	public function testAuthenticateMethodApiAuth() {
		$this->registerFunction(true);
		try {
			authenticate_method('test');
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'APIException');
			$this->assertIdentical($e->getMessage(), elgg_echo('APIException:APIAuthenticationFailed'));
		}
	}

	public function testAuthenticateMethodUserAuth() {
		$this->registerFunction(false, true);
		try {
			authenticate_method('test');
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'APIException');
		}
	}

	public function testAuthenticateMethod() {
		$this->registerFunction(false, false);
		// anonymous with no user authentication
		$this->assertTrue(authenticate_method('test'));
	}

// execute_method
	public function testExecuteMethodNotImplemented() {
		try {
			execute_method('BAD');
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'APIException');
			$this->assertIdentical($e->getMessage(), sprintf(elgg_echo('APIException:MethodCallNotImplemented'), 'BAD'));
		}
	}

	public function testExecuteMethodNonCallable() {
		elgg_ws_expose_function('test', 'foo');
		
		try {
			execute_method('test');
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'APIException');
			$this->assertIdentical($e->getMessage(), sprintf(elgg_echo('APIException:FunctionDoesNotExist'), 'test'));
		}
	}

	public function testExecuteMethodWrongMethod() {
		$this->registerFunction();

		try {
			// GET when it should be a POST
			execute_method('test');
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'CallException');
			$this->assertIdentical($e->getMessage(), sprintf(elgg_echo('CallException:InvalidCallMethod'), 'test', 'POST'));
		}
	}

// verify parameters
	public function testVerifyParametersTypeNotSet() {
		$params = array('param1' => array('required' => true));
		elgg_ws_expose_function('test', 'elgg_echo', $params);
		
		try {
			verify_parameters('test', array());
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'APIException');
			$this->assertIdentical($e->getMessage(), sprintf(elgg_echo('APIException:InvalidParameter'), 'param1', 'test'));
		}						
	}
	
	public function testVerifyParametersMissing() {
		$params = array('param1' => array('type' => 'int', 'required' => true));
		elgg_ws_expose_function('test', 'elgg_echo', $params);

		try {
			verify_parameters('test', array());
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'APIException');
			$this->assertIdentical($e->getMessage(), sprintf(elgg_echo('APIException:MissingParameterInMethod'), 'param1', 'test'));
		}
	}

	public function testVerifyParameters() {
		$this->registerFunction();

		$parameters = array('param1' => 0);
		$this->assertTrue(verify_parameters('test', $parameters));
	}

	public function testSerialiseParameters() {

		$evaler = function ($params) {
			$s = serialise_parameters('test', $params);
			return eval('return [' . substr($s, 1) . '];');
		};

		// int and bool
		$this->registerFunction();
		$parameters = array('param1' => 1, 'param2' => 0);
		$this->assertIdentical($evaler($parameters), [1, false]);

		// string
		$test_string = 'testing';
		$this->registerFunction(false, false, array('param1' => array('type' => 'string')));
		$parameters = array('param1' => $test_string);
		$this->assertIdentical($evaler($parameters), [$test_string]);

		// test string with " in it
		$test_string = 'test"ing';
		$this->registerFunction(false, false, array('param1' => array('type' => 'string')));
		$parameters = array('param1' => $test_string);
		$this->assertIdentical($evaler($parameters), [$test_string]);

		// test string with ' in it
		$test_string = 'test\'ing';
		$this->registerFunction(false, false, array('param1' => array('type' => 'string')));
		$parameters = array('param1' => $test_string);
		$this->assertIdentical($evaler($parameters), [$test_string]);

		// test string with \ in it
		$test_string = 'test\\ing';
		$this->registerFunction(false, false, array('param1' => array('type' => 'string')));
		$parameters = array('param1' => $test_string);
		$this->assertIdentical($evaler($parameters), [$test_string]);

		// test string with \' in it
		$test_string = "test\\'ing";
		$this->registerFunction(false, false, array('param1' => array('type' => 'string')));
		$parameters = array('param1' => $test_string);
		$this->assertIdentical($evaler($parameters), [$test_string]);

		// test string with \ at end
		$test_string = "testing\\";
		$this->registerFunction(false, false, array('param1' => array('type' => 'string')));
		$parameters = array('param1' => $test_string);
		$this->assertIdentical($evaler($parameters), [$test_string]);

		// test string reported by twall in #1364
		$test_string = '{"html":"<div><img src=\\"http://foo.com\\"/>Blah Blah</div>"}';
		$this->registerFunction(false, false, array('param1' => array('type' => 'string')));
		$parameters = array('param1' => $test_string);
		$this->assertIdentical($evaler($parameters), [$test_string]);

		// float
		$this->registerFunction(false, false, array('param1' => array('type' => 'float')));
		$parameters = array('param1' => 2.5);
		$this->assertIdentical($evaler($parameters), [2.5]);

		// indexed array of strings
		$this->registerFunction(false, false, array('param1' => array('type' => 'array')));
		$parameters = array('param1' => ['one', 'two']);
		$this->assertIdentical($evaler($parameters), [['one', 'two']]);

		// associative array of strings
		$this->registerFunction(false, false, array('param1' => array('type' => 'array')));
		$parameters = array('param1' => ['first' => 'one', 'second' => 'two']);
		$this->assertIdentical($evaler($parameters), [['first' => 'one', 'second' => 'two']]);

		// indexed array of strings (all values cast to strings)
		$this->registerFunction(false, false, array('param1' => array('type' => 'array')));
		$parameters = array('param1' => [1, 2]);
		$this->assertIdentical($evaler($parameters), [['1', '2']]);

		// test missing optional param
		$this->registerFunction(false, false, [
			'param1' => ['type' => 'int', 'required' => false],
			'param2' => ['type' => 'int'],
		]);
		$parameters = ['param2' => '2'];
		$this->assertIdentical($evaler($parameters), [null, 2]);

		// test unknown type
		$this->registerFunction(false, false, array('param1' => array('type' => 'bad')));
		$parameters = array('param1' => 'test');
		$this->expectException('APIException');
		$s = serialise_parameters('test', $parameters);
	}

// api key methods
	//public function testApiAuthenticate() {
	//	$this->assertFalse(pam_authenticate(null, "api"));
	//}

	public function testApiAuthKeyNoKey() {
		try {
			api_auth_key();
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'APIException');
			$this->assertIdentical($e->getMessage(), elgg_echo('APIException:MissingAPIKey'));
		}
	}

	public function testApiAuthKeyBadKey() {
		set_input('api_key', 'BAD');
		try {
			api_auth_key();
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'APIException');
			$this->assertIdentical($e->getMessage(), elgg_echo('APIException:BadAPIKey'));
		}
	}

	public function testSerialiseParametersCasting() {
		$types = [
			'int' => [
				["0", 0],
				["1", 1],
				[" 1", 1],
			],
			'bool' => [
				["0", false],
				[" 1", true],

				// BC with 2.0
				[" false", false],
				["true", false],
			],
			'float' => [
				["1.65", 1.65],
				[" 1.65 ", 1.65],
			],
			'array' => [
				[["2 ", " bar"], [2, "bar"]],
				[["' \""], ["' \\\""]],
			],
			'string' => [
				[" foo ", "foo"],
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
				$this->assertEqual($value, $test[1]);
			}
		}
	}

	public function testExecuteMethod() {
		$params = array(
			'param1' => array('type' => 'int', 'required' => false),
			'param2' => array('type' => 'bool', 'required' => true),
		);
		elgg_ws_expose_function('test', array($this, 'methodCallback'), $params);

		set_input('param1', "2");
		set_input('param2', "1");

		$result = execute_method('test');
		$this->assertIsA($result, 'SuccessResult');
		$this->assertIdentical($result->export()->result, array(2, true));

		set_input('param1', null);
		set_input('param2', "1");

		$result = execute_method('test');
		$this->assertIsA($result, 'SuccessResult');
		$this->assertIdentical($result->export()->result, array(null, true));
	}

	public function testExecuteMethodAssoc() {
		$params = array(
			'param1' => array('type' => 'int', 'required' => false),
			'param2' => array('type' => 'bool', 'required' => true),
		);
		elgg_ws_expose_function('test', array($this, 'methodCallbackAssoc'), $params, '', 'GET', false, false, true);

		set_input('param1', "2");
		set_input('param2', "1");

		$result = execute_method('test');
		$this->assertIsA($result, 'SuccessResult');
		$this->assertIdentical($result->export()->result, array('param1' => 2, 'param2' => true));

		set_input('param1', null);
		set_input('param2', "1");

		$result = execute_method('test');
		$this->assertIsA($result, 'SuccessResult');
		$this->assertIdentical($result->export()->result, array('param1' => null, 'param2' => true));
	}

	public function methodCallback() {
		return func_get_args();
	}

	public function methodCallbackAssoc($values) {
		return $values;
	}
	
	protected function registerFunction($api_auth = false, $user_auth = false, $params = null, $assoc = false) {
		$parameters = array('param1' => array('type' => 'int', 'required' => true),
							'param2' => array('type' => 'bool', 'required' => false), );
		
		if ($params == null) {
			$params = $parameters;
		}

		$callback = ($assoc) ? [$this, 'methodCallbackAssoc'] : [$this, 'methodCallback'];
		elgg_ws_expose_function('test', $callback, $params, '', 'POST', $api_auth, $user_auth);
	}
}
