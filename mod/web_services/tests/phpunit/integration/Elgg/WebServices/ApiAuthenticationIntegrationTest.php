<?php

namespace Elgg\WebServices;

use Elgg\IntegrationTestCase;
use Elgg\Http\Request;
use Elgg\WebServices\Middleware\ApiContextMiddleware;
use Elgg\WebServices\Middleware\ViewtypeMiddleware;
use Elgg\WebServices\Middleware\RestApiOutputMiddleware;
use Elgg\WebServices\Middleware\RestApiErrorHandlingMiddleware;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthenticationIntegrationTest extends IntegrationTestCase {

	/**
	 * @var \ElggPlugin
	 */
	protected $plugin;
	
	/**
	 * @var array backup of plugin settings
	 */
	protected $plugin_settings;
	
	/**
	 * @var array
	 */
	protected $pam_handlers;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->plugin = elgg_get_plugin_from_id('web_services');
		$this->plugin_settings = $this->plugin->getAllSettings();
		$this->pam_handlers = \ElggPAM::$_handlers;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function down() {
		// restore plugin settings
		foreach ($this->plugin_settings as $name => $value) {
			$this->plugin->setSetting($name, $value);
		}
		
		\ElggPAM::$_handlers = $this->pam_handlers;
	}
	
	/**
	 * Create a testing service with the correct api call
	 *
	 * @param Request $request prepared request
	 *
	 * @return void
	 */
	protected function createService(Request $request): void {
		$app = self::createApplication([
			'isolate' => true,
			'request' => $request,
		]);
		
		// keep this inline with the route declaration in elgg-plugin.php
		$app->_services->routes->register('default:services:rest', [
			'path' => '/services/api/rest/{view}/{segments?}',
			'controller' => RestServiceController::class,
			'defaults' => [
				'view' => 'json',
			],
			'middleware' => [
				ApiContextMiddleware::class,
				ViewtypeMiddleware::class,
				RestApiOutputMiddleware::class,
				RestApiErrorHandlingMiddleware::class,
			],
			'requirements' => [
				'segments' => '.+',
			],
			'walled' => false,
		]);
		
		// in some cases there was a failure with missing view
		$app->_services->views->registerPluginViews($this->plugin->getPath());
		
		// reset all PAM handlers
		\ElggPAM::$_handlers = [];
	}
	
	/**
	 * Execute an api request
	 *
	 * @param Request $request prepared request
	 *
	 * @return mixed
	 * @throws \Throwable
	 */
	protected function executeRequest(Request $request) {
		ob_start();
		
		$t = false;
		$response = false;
		try {
			_elgg_services()->router->route($request);
			$response = _elgg_services()->responseFactory->getSentResponse();
		} catch (\Throwable $t) {
			// just catching
		}
		
		ob_get_clean();
		
		if ($t instanceof \Throwable) {
			throw $t;
		}
		
		return $response;
	}
	
	public function testApiAuthenticationWithValidKey() {
		$key = _elgg_services()->apiUsersTable->createApiUser();
		$this->assertNotFalse($key);
		
		$request = $this->prepareHttpRequest(elgg_generate_url('default:services:rest', [
			'method' => 'api_auth_test',
			'view' => 'json',
			'api_key' => $key->api_key,
		]));
		
		$this->createService($request);
		
		$this->assertTrue($this->plugin->setSetting('auth_allow_key', 1));
		
		$called = 0;
		elgg_ws_expose_function('api_auth_test', function() use (&$called) {
			$called++;
			
			return \SuccessResult::getInstance(['called' => $called]);
		}, [], '', 'GET', true);
		
		/* @var $result Response */
		$result = $this->executeRequest($request);
		$this->assertInstanceOf(Response::class, $result);
		$this->assertEquals(ELGG_HTTP_OK, $result->getStatusCode());
		
		$content = $result->getContent();
		$this->assertIsString($content);
		
		$content = json_decode($content, true);
		$this->assertIsArray($content);
		$this->assertArrayHasKey('status', $content);
		$this->assertEquals(\SuccessResult::$RESULT_SUCCESS, $content['status']);
		
		$this->assertArrayHasKey('result', $content);
		$this->assertArrayHasKey('called', $content['result']);
		$this->assertEquals($called, $content['result']['called']);
	}
	
	public function testApiAuthenticationWithValidKeyButKeyAuthenticationIsDisabled() {
		$key = _elgg_services()->apiUsersTable->createApiUser();
		$this->assertNotFalse($key);
		
		$request = $this->prepareHttpRequest(elgg_generate_url('default:services:rest', [
			'method' => 'api_auth_test',
			'view' => 'json',
			'api_key' => $key->api_key,
		]));
		
		$this->createService($request);
		
		$this->assertTrue($this->plugin->setSetting('auth_allow_key', 0));
		
		$called = 0;
		elgg_ws_expose_function('api_auth_test', function() use (&$called) {
			$called++;
			
			return \SuccessResult::getInstance(['called' => $called]);
		}, [], '', 'GET', true);
		
		$this->expectException(\APIException::class);
		$this->executeRequest($request);
	}
	
	public function testApiAuthenticationWithInvalidKey() {
		$request = $this->prepareHttpRequest(elgg_generate_url('default:services:rest', [
			'method' => 'api_auth_test',
			'view' => 'json',
			'api_key' => 'invalid_key',
		]));
		
		$this->createService($request);
		
		$this->assertTrue($this->plugin->setSetting('auth_allow_key', 1));
		
		$called = 0;
		elgg_ws_expose_function('api_auth_test', function() use (&$called) {
			$called++;
			
			return \SuccessResult::getInstance(['called' => $called]);
		}, [], '', 'GET', true);
		
		$this->expectException(\APIException::class);
		$this->executeRequest($request);
	}
	
	public function testApiAuthenticationWithValidHMACHeaders() {
		$key = _elgg_services()->apiUsersTable->createApiUser();
		$this->assertNotFalse($key);
		
		$request = $this->prepareHttpRequest(elgg_generate_url('default:services:rest', [
			'method' => 'api_auth_test',
			'view' => 'json',
		]));
		
		// add headers
		$api_header = new \stdClass();
		$api_header->algo = 'sha256';
		$api_header->time = time();
		$api_header->nounce = md5(rand());
		
		$hmac = elgg_ws_calculate_hmac(
			$api_header->algo,
			$api_header->time,
			$api_header->nounce,
			$key->api_key,
			$key->secret,
			$request->server->get('QUERY_STRING', '')
		);
		$request->server->set('HTTP_X_ELGG_APIKEY', $key->api_key);
		$request->server->set('HTTP_X_ELGG_HMAC', $hmac);
		$request->server->set('HTTP_X_ELGG_HMAC_ALGO', $api_header->algo);
		$request->server->set('HTTP_X_ELGG_TIME', $api_header->time);
		$request->server->set('HTTP_X_ELGG_NONCE', $api_header->nounce);
		
		$this->createService($request);
		
		$this->assertTrue($this->plugin->setSetting('auth_allow_hmac', 1));
		
		$called = 0;
		elgg_ws_expose_function('api_auth_test', function() use (&$called) {
			$called++;
			
			return \SuccessResult::getInstance(['called' => $called]);
		}, [], '', 'GET', true);
			
		/* @var $result Response */
		$result = $this->executeRequest($request);
		$this->assertInstanceOf(Response::class, $result);
		$this->assertEquals(ELGG_HTTP_OK, $result->getStatusCode());
		
		$content = $result->getContent();
		$this->assertIsString($content);
		
		$content = json_decode($content, true);
		$this->assertIsArray($content);
		$this->assertArrayHasKey('status', $content);
		$this->assertEquals(\SuccessResult::$RESULT_SUCCESS, $content['status']);
		
		$this->assertArrayHasKey('result', $content);
		$this->assertArrayHasKey('called', $content['result']);
		$this->assertEquals($called, $content['result']['called']);
	}
	
	public function testApiAuthenticationWithValidHMACHeadersButHMACAuthenticationIsDisabled() {
		$key = _elgg_services()->apiUsersTable->createApiUser();
		$this->assertNotFalse($key);
		
		$request = $this->prepareHttpRequest(elgg_generate_url('default:services:rest', [
			'method' => 'api_auth_test',
			'view' => 'json',
		]));
		
		// add headers
		$api_header = new \stdClass();
		$api_header->algo = 'sha256';
		$api_header->time = time();
		$api_header->nounce = md5(rand());
		
		$hmac = elgg_ws_calculate_hmac(
			$api_header->algo,
			$api_header->time,
			$api_header->nounce,
			$key->api_key,
			$key->secret,
			$request->server->get('QUERY_STRING', '')
		);
		$request->server->set('HTTP_X_ELGG_APIKEY', $key->api_key);
		$request->server->set('HTTP_X_ELGG_HMAC', $hmac);
		$request->server->set('HTTP_X_ELGG_HMAC_ALGO', $api_header->algo);
		$request->server->set('HTTP_X_ELGG_TIME', $api_header->time);
		$request->server->set('HTTP_X_ELGG_NONCE', $api_header->nounce);
		
		$this->createService($request);
		
		$this->assertTrue($this->plugin->setSetting('auth_allow_hmac', 0));
		
		$called = 0;
		elgg_ws_expose_function('api_auth_test', function() use (&$called) {
			$called++;
			
			return \SuccessResult::getInstance(['called' => $called]);
		}, [], '', 'GET', true);
			
		$this->expectException(\APIException::class);
		$result = $this->executeRequest($request);
	}
	
	public function testApiAuthenticationWithInvalidHMACHeaders() {
		$key = _elgg_services()->apiUsersTable->createApiUser();
		$this->assertNotFalse($key);
		
		$request = $this->prepareHttpRequest(elgg_generate_url('default:services:rest', [
			'method' => 'api_auth_test',
			'view' => 'json',
		]));
		
		// add headers
		$api_header = new \stdClass();
		$api_header->algo = 'sha256';
		$api_header->time = time();
		$api_header->nounce = md5(rand());
		
		$hmac = elgg_ws_calculate_hmac(
			$api_header->algo,
			$api_header->time,
			$api_header->nounce,
			$key->api_key,
			$key->secret,
			$request->server->get('QUERY_STRING', '')
		);
		$request->server->set('HTTP_X_ELGG_APIKEY', $key->api_key);
		$request->server->set('HTTP_X_ELGG_HMAC', $hmac);
		$request->server->set('HTTP_X_ELGG_HMAC_ALGO', $api_header->algo);
		$request->server->set('HTTP_X_ELGG_TIME', $api_header->time + 1); // time header isn't valid
		$request->server->set('HTTP_X_ELGG_NONCE', $api_header->nounce);
		
		$this->createService($request);
		
		$this->assertTrue($this->plugin->setSetting('auth_allow_hmac', 1));
		
		$called = 0;
		elgg_ws_expose_function('api_auth_test', function() use (&$called) {
			$called++;
			
			return \SuccessResult::getInstance(['called' => $called]);
		}, [], '', 'GET', true);
		
		$this->expectException(\APIException::class);
		$this->executeRequest($request);
	}
	
	public function testApiAuthenticationWithValidHMACHeadersPost() {
		// need a way to simulate post data in the request since this is read from 'php://input'
		$this->markTestIncomplete();
	}
	
	public function testApiAuthenticationWithInvalidHMACHeadersPost() {
		// need a way to simulate post data in the request since this is read from 'php://input'
		$this->markTestIncomplete();
	}
}
