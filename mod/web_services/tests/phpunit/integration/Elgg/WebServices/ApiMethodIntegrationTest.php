<?php

namespace Elgg\WebServices;

use Elgg\Collections\CollectionItemInterface;
use Elgg\Exceptions\DomainException;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Plugins\IntegrationTestCase;

class ApiMethodIntegrationTest extends IntegrationTestCase {

	/**
	 * Test callback function for api calls
	 */
	public function callbackTest() {
		return 'success';
	}
	
	/**
	 * Create a test api method
	 *
	 * @return \Elgg\WebServices\ApiMethod
	 */
	protected function getApiMethod() {
		return new ApiMethod('foo', [$this, 'callbackTest']);
	}
	
	public function testConstructor() {
		$api = new ApiMethod('foo', [$this, 'callbackTest']);
		
		$this->assertEquals('GET:foo', $api->getID());
		$this->assertEquals('(' . __CLASS__ . ')->callbackTest', $api->describeCallable());
	}
	
	public function testSetterWithProtectedValues() {
		$api = $this->getApiMethod();
		
		$this->assertEquals('GET:foo', $api->getID());
		$this->assertEquals('(' . __CLASS__ . ')->callbackTest', $api->describeCallable());
		
		$api->method = 'bar';
		$this->assertEquals('GET:foo', $api->getID());
		
		$api->callback = 'something';
		$this->assertEquals('(' . __CLASS__ . ')->callbackTest', $api->describeCallable());
	}
	
	public function testCollectionItemInterface() {
		$api = $this->getApiMethod();
		
		$this->assertInstanceOf(CollectionItemInterface::class, $api);
		
		$this->assertEquals(1, $api->getPriority());
		$this->assertEquals('GET:foo', $api->getID());
	}
	
	/**
	 * @dataProvider setterArrayNameProvider
	 */
	public function testArrayParams($name) {
		$api = $this->getApiMethod();
		
		$this->expectException(InvalidArgumentException::class);
		$api->$name = 'string';
	}
	
	public static function setterArrayNameProvider() {
		return [
			['params'],
		];
	}
	
	/**
	 * @dataProvider setterStringNameProvider
	 */
	public function testStringParams($name) {
		$api = $this->getApiMethod();
		
		$this->expectException(InvalidArgumentException::class);
		$api->$name = [];
	}
	
	public static function setterStringNameProvider() {
		return [
			['description'],
			['call_method'],
		];
	}
	
	/**
	 * @dataProvider setterBooleanNameProvider
	 */
	public function testBooleanParams($name) {
		$api = $this->getApiMethod();
		
		$this->expectException(InvalidArgumentException::class);
		$api->$name = 'foo';
	}
	
	public static function setterBooleanNameProvider() {
		return [
			['require_api_auth'],
			['require_user_auth'],
			['supply_associative'],
		];
	}
	
	public function testSetParamsToInvalidArrayStructure() {
		$api = $this->getApiMethod();
		
		$this->expectException(InvalidArgumentException::class);
		$api->params = ['string'];
	}
	
	public function testSetParamsToArrayWithMissingType() {
		$api = $this->getApiMethod();
		
		$this->expectException(InvalidArgumentException::class);
		$api->params = [
			'username' => [],
		];
	}
	
	public function testSetParamsFillsMissingRequired() {
		$api = $this->getApiMethod();
		
		$api->params = [
			'username' => [
				'type' => 'string',
			],
			'password' => [
				'type' => 'string',
				'required' => false,
			],
		];
		
		$expected = [
			'username' => [
				'type' => 'string',
				'required' => true, // this is magicly set
			],
			'password' => [
				'type' => 'string',
				'required' => false,
			],
		];
		
		$this->assertEquals($expected, $api->params);
	}
	
	public function testSetCallMethodToUnsupportedValue() {
		$api = $this->getApiMethod();
		
		$this->expectException(DomainException::class);
		$api->call_method = 'PUT';
	}
	
	/**
	 * @dataProvider supportedCallMethods
	 */
	public function testSetCallMethodToSupportedValue($value) {
		$api = $this->getApiMethod();
		
		$api->call_method = $value;
		$this->assertEquals(strtoupper($value), $api->call_method);
	}
	
	public static function supportedCallMethods() {
		return [
			['get'],
			['GET'],
			['post'],
			['POST'],
		];
	}
	
	/**
	 * @dataProvider typeCastParameterProvider
	 */
	public function testTypeCastParameter($key, $value, $type, $expected) {
		$api = $this->getApiMethod();
		
		$reflector = new \ReflectionClass(ApiMethod::class);
		$method = $reflector->getMethod('typeCastParameter');
		$method->setAccessible(true);
		
		$result = $method->invoke($api, $key, $value, $type);
		
		$this->assertEquals($expected, $result);
	}
	
	public static function typeCastParameterProvider() {
		return [
			['foo', null, 'string', null],
			['foo', '1', 'int', 1],
			['foo', '1', 'integer', 1],
			['foo', 1, 'int', 1],
			['foo', 1, 'integer', 1],
			['foo', 'false', 'bool', false],
			['foo', '0', 'bool', false],
			['foo', 0, 'bool', false],
			['foo', 'false', 'boolean', false],
			['foo', '0', 'boolean', false],
			['foo', 0, 'boolean', false],
			['foo', 1, 'bool', true],
			['foo', 'bar', 'bool', true],
			['foo', 1, 'boolean', true],
			['foo', 'bar', 'boolean', true],
			['foo', 'bar', 'string', 'bar'],
			['foo', 1, 'string', '1'],
			['foo', '1', 'float', (float) 1],
			['foo', '1.0', 'float', 1.0],
			['foo', ['bar', '1', 'false'], 'array', ['bar', '1', 'false']],
		];
	}
	
	public function testTypeCastInvalidArray() {
		$api = $this->getApiMethod();
		
		$reflector = new \ReflectionClass(ApiMethod::class);
		$method = $reflector->getMethod('typeCastParameter');
		$method->setAccessible(true);
		
		$this->expectException(\APIException::class);
		$method->invoke($api, 'foo', '', 'array');
	}
	
	public function testTypeCastInvalidType() {
		$api = $this->getApiMethod();
		
		$reflector = new \ReflectionClass(ApiMethod::class);
		$method = $reflector->getMethod('typeCastParameter');
		$method->setAccessible(true);
		
		$this->expectException(\APIException::class);
		$method->invoke($api, 'foo', '', 'bar');
	}
	
	public function testGetParameters() {
		$http_request = $this->prepareHttpRequest('foo', 'GET', [
			'register' => '1',
			'username' => 'foo',
		]);
		$request = new \Elgg\Request(elgg(), $http_request);
		
		$api = $this->getApiMethod();
		$api->params = [
			'username' => [
				'type' => 'string',
			],
			'password' => [
				'type' => 'string',
				'default' => '1234',
				'rquired' => false,
			],
			'register' => [
				'type' => 'bool',
				'default' => false,
			],
		];
		
		$reflector = new \ReflectionClass(ApiMethod::class);
		$method = $reflector->getMethod('getParameters');
		$method->setAccessible(true);
		
		$result = $method->invoke($api, $request);
		
		$expected = [
			'username' => 'foo', // from input
			'password' => '1234', // from default
			'register' => true, // input casted
		];
		$this->assertEquals($expected, $result);
	}
	
	public function testGetParametersMissingRequiredInput() {
		$http_request = $this->prepareHttpRequest('foo', 'GET', [
			'username' => 'foo',
		]);
		$request = new \Elgg\Request(elgg(), $http_request);
		
		$api = $this->getApiMethod();
		$api->params = [
			'username' => [
				'type' => 'string',
			],
			'password' => [
				'type' => 'string',
			],
		];
		
		$reflector = new \ReflectionClass(ApiMethod::class);
		$method = $reflector->getMethod('getParameters');
		$method->setAccessible(true);
		
		$this->expectException(\APIException::class);
		$method->invoke($api, $request);
	}
	
	public function testExecute() {
		$http_request = $this->prepareHttpRequest('foo', 'GET', [
			'username' => 'foo',
		]);
		$request = new \Elgg\Request(elgg(), $http_request);
		
		$api = $this->getApiMethod();
		$api->supply_associative = true;
		$api->params = [
			'username' => [
				'type' => 'string',
			],
		];
		
		$result = $api->execute($request);
		
		$this->assertInstanceOf(\SuccessResult::class, $result);
	}
	
	public function testExecuteParameterOrder() {
		$http_request = $this->prepareHttpRequest('foo', 'GET', [
			'password' => 'bar',
			'username' => 'foo',
		]);
		$request = new \Elgg\Request(elgg(), $http_request);
		
		$api = new ApiMethod('foo', function(string $username, string $password) {
			$this->assertEquals('foo', $username);
			$this->assertEquals('bar', $password);
			
			return 'success';
		});
		$api->params = [
			'username' => [
				'type' => 'string',
			],
			'password' => [
				'type' => 'string',
			],
		];
		
		$result = $api->execute($request);
		
		$this->assertInstanceOf(\SuccessResult::class, $result);
	}
	
	public function testExecuteParameterOrderAsArray() {
		$http_request = $this->prepareHttpRequest('foo', 'GET', [
			'password' => 'bar',
			'username' => 'foo',
		]);
		$request = new \Elgg\Request(elgg(), $http_request);
		
		$api = new ApiMethod('foo', function(array $params) {
			$expected = [
				'username' => 'foo',
				'password' => 'bar',
			];
			$this->assertEquals($expected, $params);
			
			return 'success';
		});
		$api->params = [
			'username' => [
				'type' => 'string',
			],
			'password' => [
				'type' => 'string',
			],
		];
		$api->supply_associative = true;
		
		$result = $api->execute($request);
		
		$this->assertInstanceOf(\SuccessResult::class, $result);
	}
	
	public function testExecuteNonCallable() {
		$http_request = $this->prepareHttpRequest('foo', 'GET', [
			'password' => 'bar',
			'username' => 'foo',
		]);
		$request = new \Elgg\Request(elgg(), $http_request);
		
		$api = new ApiMethod('foo', 'not_callable');
		
		$this->expectException(\APIException::class);
		$api->execute($request);
	}
	
	public function testExecuteNoResult() {
		$http_request = $this->prepareHttpRequest('foo', 'GET', [
			'password' => 'bar',
			'username' => 'foo',
		]);
		$request = new \Elgg\Request(elgg(), $http_request);
		
		$api = new ApiMethod('foo', function(string $username, string $password) {
			$this->assertEquals('foo', $username);
			$this->assertEquals('bar', $password);
		});
		$api->params = [
			'username' => [
				'type' => 'string',
			],
			'password' => [
				'type' => 'string',
			],
		];
		
		$this->expectException(\APIException::class);
		$api->execute($request);
	}
}
