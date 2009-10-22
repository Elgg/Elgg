<?php
/**
 * Elgg Test Services - General API and REST
 *
 * @package Elgg
 * @subpackage Test
 * @author Curverider Ltd
 * @link http://elgg.org/
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
		
		$this->expectException('InvalidParameterException');
		expose_function();
	}
	
	public function testExposeFunctionNoFunction() {
		$this->expectException('InvalidParameterException');
		expose_function('test');
	}
	
	public function testExposeFunctionBadParameters() {
		$this->expectException('InvalidParameterException');
		expose_function('test', 'test', 'BAD');
	}
	
	public function testExposeFunctionParametersNotArray() {
		$this->expectException('InvalidParameterException');
		expose_function('test', 'test', array('param1' => 'string'));
	}
	
	public function testExposeFunctionBadHttpMethod() {
		$this->expectException('InvalidParameterException');
		expose_function('test', 'test', null, '', 'BAD');
	}
	
	public function testExposeFunctionSuccess() {
		global $API_METHODS;
		$parameters = array('param1' => array('type' => 'int', 'required' => true));
		$method['function'] = 'foo';
		$method['parameters'] = $parameters;
		$method['call_method'] = 'GET'; 
		$method['description'] = '';
		$method['require_api_auth'] = false;
		$method['require_user_auth'] = false;

		$this->assertTrue(expose_function('test', 'foo', $parameters));
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
	public function testApiMethodNotImplemented() {
		global $CONFIG;
		
		$results = send_api_get_call($CONFIG->wwwroot . 'pg/api/rest/json/', array('method' => 'bad.method'));
		$obj = json_decode($results);
		$this->assertIdentical(sprintf(elgg_echo('APIException:MethodCallNotImplemented'), 'bad.method'), $obj->api[0]->message);
	}

	public function testAuthenticateForApi() {
		$this->registerFunction(true, false);
		
		$this->expectException('APIException');
		authenticate_method('test');
	}

	public function testAuthenticateForUser() {
		$this->registerFunction(false, true);
		
		$this->expectException('APIException');
		authenticate_method('test');
	}
	
	public function testAuthenticateMethod() {
		$this->registerFunction(false, false);
		// anonymous with no user authentication
		$this->assertTrue(authenticate_method('test'));
	}
	
// api_authenticate
	public function testApiAuthenticate() {
		$this->registerFunction(true, false);
		
		$this->assertFalse(api_authenticate());
	}
	
// execute_method
	public function testExecuteMethodNonCallable() {
		expose_function('test', 'foo');
		
		$this->expectException('ApiException');
		execute_method('test');
	}

	public function testExecuteMethodWrongMethod() {
		$this->registerFunction();
		
		// get when it should be a post
		$this->expectException('CallException');
		execute_method('test');
	}
	
	public function testVerifyParameters() {
		$this->registerFunction();
		
		$parameters = array('param1' => 0);
		$this->assertTrue(verify_parameters('test', $parameters));
		
		$parameters = array('param2' => true);
		$this->expectException('APIException');
		$this->assertTrue(verify_parameters('test', $parameters));
	}
	
	public function testserialise_parameters() {
		
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

		// float
		$this->registerFunction(false, false, array('param1' => array('type' => 'float')));
		$parameters = array('param1' => 2.5);
		$s = serialise_parameters('test', $parameters);
		$this->assertIdentical($s, ',2.5');

		// indexed array of strings
		$this->registerFunction(false, false, array('param1' => array('type' => 'array')));
		$parameters = array('param1' => array('one', 'two'));
		$s = serialise_parameters('test', $parameters);
		$this->assertIdentical($s, "array('0'=>'one','1'=>'two')");

		// associative array of strings
		$this->registerFunction(false, false, array('param1' => array('type' => 'array')));
		$parameters = array('param1' => array('first' => 'one', 'second' => 'two'));
		$s = serialise_parameters('test', $parameters);
		$this->assertIdentical($s, "array('first'=>'one','second'=>'two')");

		// indexed array of strings
		$this->registerFunction(false, false, array('param1' => array('type' => 'array')));
		$parameters = array('param1' => array(1, 2));
		$s = serialise_parameters('test', $parameters);
		$this->assertIdentical($s, "array('0'=>'1','1'=>'2')");

		// test unknown type
		$this->registerFunction(false, false, array('param1' => array('type' => 'bad')));
		$parameters = array('param1' => 'test');
		$this->expectException('APIException');
		$s = serialise_parameters('test', $parameters);
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
