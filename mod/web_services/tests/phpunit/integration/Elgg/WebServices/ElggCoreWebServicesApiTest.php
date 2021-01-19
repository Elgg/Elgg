<?php

namespace Elgg\WebServices;

use Elgg\Exceptions\InvalidParameterException;
use Elgg\IntegrationTestCase;

/**
 * @group WebServices
 */
class ElggCoreWebServicesApiTest extends IntegrationTestCase {

	private $call_method;

	public function up() {
		$this->call_method = _elgg_services()->request->getMethod();
		// Emulate GET request, which is not set in cli mode
		_elgg_services()->request->server->set('REQUEST_METHOD', 'GET');
	}

	/**
	 * Called after each test method.
	 */
	public function down() {
		// Restore original request method
		_elgg_services()->request->server->set('REQUEST_METHOD', $this->call_method);
	}

	public function testExposeFunctionBadParameters() {
		$this->expectException(\TypeError::class);
		elgg_ws_expose_function('test', 'test', 'BAD');
	}

	public function testExposeFunctionParametersBadArray() {
		$this->expectException(InvalidParameterException::class);
		$this->expectExceptionMessage(elgg_echo('InvalidParameterException:APIParametersArrayStructure', ['test']));
		elgg_ws_expose_function('test', 'test', ['param1' => 'string']);
	}

	public function testExposeFunctionBadHttpMethod() {
		$this->expectException(InvalidParameterException::class);
		$this->expectExceptionMessage(elgg_echo('InvalidParameterException:UnrecognisedHttpMethod', ['BAD', 'test']));
		elgg_ws_expose_function('test', 'test', null, '', 'BAD');
	}

	// api key methods
	public function testApiAuthenticate() {
		$this->markTestSkipped();
	}

	public function testApiAuthKeyNoKey() {
		$this->expectException(\APIException::class);
		$this->expectExceptionMessage(elgg_echo('APIException:MissingAPIKey'));
		elgg_ws_pam_auth_api_key();
	}

	public function testApiAuthKeyBadKey() {
		set_input('api_key', 'BAD');
		
		$this->expectException(\APIException::class);
		$this->expectExceptionMessage(elgg_echo('APIException:BadAPIKey'));
		elgg_ws_pam_auth_api_key();
	}
	
	public function testApiAuthKeyDisabled() {
		/* @var $entity \ElggApiKey */
		$entity = $this->createObject([
			'subtype' => \ElggApiKey::SUBTYPE,
		]);
		
		$this->assertInstanceOf(\ElggApiKey::class, $entity);
		
		set_input('api_key', $entity->getPublicKey());
		
		$this->assertTrue(elgg_ws_pam_auth_api_key());
		
		$this->assertTrue($entity->disableKeys());
		$this->assertFalse($entity->hasActiveKeys());
		
		$this->expectException(\APIException::class);
		$this->expectExceptionMessage(elgg_echo('APIException:BadAPIKey'));
		elgg_ws_pam_auth_api_key();
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
