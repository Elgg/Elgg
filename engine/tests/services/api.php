<?php
/**
 * Elgg Test Services - General API and REST
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreServicesApiTest extends ElggCoreUnitTest {

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		global $API_METHODS;
		$this->swallowErrors();
		$API_METHODS = array();
	}
	
// expose_function
	public function testExposeFunctionNoMethod() {
		try {
			@expose_function();
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'InvalidParameterException');
			$this->assertIdentical($e->getMessage(), elgg_echo('InvalidParameterException:APIMethodOrFunctionNotSet'));
		}
	}
	
	public function testExposeFunctionNoFunction() {
		try {
			@expose_function('test');
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'InvalidParameterException');
			$this->assertIdentical($e->getMessage(), elgg_echo('InvalidParameterException:APIMethodOrFunctionNotSet'));
		}
	}
	
	public function testExposeFunctionBadParameters() {
		try {
			@expose_function('test', 'test', 'BAD');
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'InvalidParameterException');
			$this->assertIdentical($e->getMessage(), sprintf(elgg_echo('InvalidParameterException:APIParametersArrayStructure'), 'test'));
		}
	}
	
	public function testExposeFunctionParametersBadArray() {
		try {
			expose_function('test', 'test', array('param1' => 'string'));
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'InvalidParameterException');
			$this->assertIdentical($e->getMessage(), sprintf(elgg_echo('InvalidParameterException:APIParametersArrayStructure'), 'test'));
		}
	}
	
	public function testExposeFunctionBadHttpMethod() {
		try {
			@expose_function('test', 'test', null, '', 'BAD');
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
		
		$this->assertTrue(expose_function('test', 'foo', $parameters));
		
		$parameters = array('param1' => array('type' => 'int', 'required' => true), 
							'param2' => array('type' => 'bool', 'required' => true),
							'param3' => array('type' => 'string', 'required' => false), );
		$method['description'] = '';
		$method['function'] = 'foo';
		$method['parameters'] = $parameters;
		$method['call_method'] = 'GET'; 
		$method['require_api_auth'] = false;
		$method['require_user_auth'] = false;

		$this->assertIdentical($method, $API_METHODS['test']);
	}

// unexpose_function
	public function testUnexposeFunction() {
		global $API_METHODS;
		
		$this->registerFunction();
		
		unexpose_function('test');
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
		expose_function('test', 'foo');
		
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
		expose_function('test', 'elgg_echo', $params);
		
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
		expose_function('test', 'elgg_echo', $params);
		
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
		
		// int and bool
		$this->registerFunction();
		$parameters = array('param1' => 1, 'param2' => 0);
		$s = serialise_parameters('test', $parameters);
		$this->assertIdentical($s, ',1,false');
		
		// string
		$this->registerFunction(false, false, array('param1' => array('type' => 'string')));
		$parameters = array('param1' => 'testing');
		$s = serialise_parameters('test', $parameters);
		$this->assertIdentical($s, ",'testing'");

		// test string with " in it
		$this->registerFunction(false, false, array('param1' => array('type' => 'string')));
		$parameters = array('param1' => 'test"ing');
		$s = serialise_parameters('test', $parameters);
		$this->assertIdentical($s, ',\'test"ing\'');
		
		// test string with ' in it
		$this->registerFunction(false, false, array('param1' => array('type' => 'string')));
		$parameters = array('param1' => 'test\'ing');
		$s = serialise_parameters('test', $parameters);
		$this->assertIdentical($s, ",'test\'ing'");
		
		// test string with \ in it
		$this->registerFunction(false, false, array('param1' => array('type' => 'string')));
		$parameters = array('param1' => 'test\ing');
		$s = serialise_parameters('test', $parameters);
		$this->assertIdentical($s, ",'test\\ing'"); 
		
		// test string with \' in it
		$this->registerFunction(false, false, array('param1' => array('type' => 'string')));
		$parameters = array('param1' => "test\'ing");
		$s = serialise_parameters('test', $parameters);
		$this->assertIdentical($s, ",'test\\\\'ing'"); // test\\'ing
		
		// test string reported by twall in #1364
		$this->registerFunction(false, false, array('param1' => array('type' => 'string')));
		$parameters = array('param1' => '{"html":"<div><img src=\\"http://foo.com\\"/>Blah Blah</div>"}');
		$s = serialise_parameters('test', $parameters);
		$this->assertIdentical($s, ",'{\"html\":\"<div><img src=\\\"http://foo.com\\\"/>Blah Blah</div>\"}'");
		
		// float
		$this->registerFunction(false, false, array('param1' => array('type' => 'float')));
		$parameters = array('param1' => 2.5);
		$s = serialise_parameters('test', $parameters);
		$this->assertIdentical($s, ',2.5');

		// indexed array of strings
		$this->registerFunction(false, false, array('param1' => array('type' => 'array')));
		$parameters = array('param1' => array('one', 'two'));
		$s = serialise_parameters('test', $parameters);
		$this->assertIdentical($s, ",array('0'=>'one','1'=>'two')");

		// associative array of strings
		$this->registerFunction(false, false, array('param1' => array('type' => 'array')));
		$parameters = array('param1' => array('first' => 'one', 'second' => 'two'));
		$s = serialise_parameters('test', $parameters);
		$this->assertIdentical($s, ",array('first'=>'one','second'=>'two')");

		// indexed array of strings
		$this->registerFunction(false, false, array('param1' => array('type' => 'array')));
		$parameters = array('param1' => array(1, 2));
		$s = serialise_parameters('test', $parameters);
		$this->assertIdentical($s, ",array('0'=>'1','1'=>'2')");

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
		global $CONFIG;
		
		$CONFIG->input['api_key'] = 'BAD';
		try {
			api_auth_key();
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'APIException');
			$this->assertIdentical($e->getMessage(), elgg_echo('APIException:BadAPIKey'));
		}
	}
	
	protected function registerFunction($api_auth = false, $user_auth = false, $params = null) {
		$parameters = array('param1' => array('type' => 'int', 'required' => true),
							'param2' => array('type' => 'bool', 'required' => false), );
		
		if ($params == null) {
			$params = $parameters;
		}

		expose_function('test', 'elgg_echo', $params, '', 'POST', $api_auth, $user_auth);
	}
	
}
