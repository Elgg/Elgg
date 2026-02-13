<?php

namespace Elgg\WebServices;

use Elgg\Http\OkResponse;
use Elgg\Http\Request as HttpRequest;
use Elgg\Plugins\IntegrationTestCase;
use Elgg\Request;
use Elgg\WebServices\Di\ApiRegistrationService;

class RestServiceControllerIntegrationTest extends IntegrationTestCase {
	
	protected ?Request $request = null;
	
	public function up() {
		$this->createApplication([
			'isolate' => true,
		]);
	}
	
	public function down() {
		$handler = $this->getExceptionHandler();
		
		if (is_array($handler) && $handler[0] instanceof RestServiceController) {
			restore_exception_handler();
		}
		
		if (isset($this->request)) {
			unset($this->request);
		}
	}
	
	protected function getExceptionHandler(): mixed {
		if (!is_callable('get_exception_handler')) {
			// PHP < 8.5
			$temp = function(\Throwable $t) {
			};
			
			$handler = set_exception_handler($temp);
			restore_exception_handler();
		} else {
			$handler = get_exception_handler();
		}
		
		return $handler;
	}
	
	protected function createService(HttpRequest $request): void {
		$this->createApplication([
			'isolate' => true,
			'request' => $request,
		]);
		
		$this->request = new Request(elgg(), $request);
	}
	
	protected function getRequest(HttpRequest $request): Request {
		return $this->request ?? new Request(elgg(), $request);
	}
	
	public function testPrepareExceptionHandler() {
		$http_request = $this->prepareHttpRequest(elgg_generate_url('default:services:rest', [
			'view' => 'json',
			'method' => 'system.api.list',
		]));
		
		$controller = new RestServiceController();
		
		$this->invokeInaccessableMethod($controller, 'prepareForRequest', $this->getRequest($http_request));
		
		$handler = $this->getExceptionHandler();
		
		$this->assertIsArray($handler);
		$this->assertCount(2, $handler);
		$this->assertInstanceOf(RestServiceController::class, $handler[0]);
		$this->assertEquals('exceptionHandler', $handler[1]);
	}
	
	public function testPrepareContext() {
		$http_request = $this->prepareHttpRequest(elgg_generate_url('default:services:rest', [
			'view' => 'json',
			'method' => 'system.api.list',
		]));
		
		$controller = new RestServiceController();
		
		$this->invokeInaccessableMethod($controller, 'prepareForRequest', $this->getRequest($http_request));
		
		$this->assertEquals('api', elgg_get_context());
	}
	
	public function testPrepareBadInvalidViewtype() {
		$this->markTestSkipped();
	}
	
	public function testPrepareUnsupportedViewtype() {
		$this->markTestSkipped();
	}
	
	public function testPrepareSetViewtype() {
		$http_request = $this->prepareHttpRequest(elgg_generate_url('default:services:rest', [
			'view' => 'json',
			'method' => 'system.api.list',
		]));
		
		$controller = new RestServiceController();
		
		$this->invokeInaccessableMethod($controller, 'prepareForRequest', $this->getRequest($http_request));
		
		$this->assertEquals('json', elgg_get_viewtype());
	}
	
	public function testInitApiEvent() {
		$controller = new RestServiceController();
		
		$event = $this->registerTestingEvent('rest', 'init', function(\Elgg\Event $in_event) {
		
		});
		
		$this->invokeInaccessableMethod($controller, 'initApi');
		
		$event->assertNumberOfCalls(1);
		$event->assertValueBefore(false);
		$event->assertValueAfter(false);
		
		$event->unregister();
	}
	
	public function testInitPamHandlers() {
		$controller = new RestServiceController();
		
		$this->invokeInaccessableMethod($controller, 'initApi');
		
		$pam_handlers = $this->getInaccessableProperty(_elgg_services()->authentication, 'handlers');
		
		$this->assertIsArray($pam_handlers);
		$this->assertArrayHasKey('user', $pam_handlers);
		$this->assertArrayHasKey('api', $pam_handlers);
		
		$this->assertArrayHasKey(\Elgg\WebServices\PAM\User\AuthToken::class, $pam_handlers['user']);
		$this->assertArrayHasKey(\Elgg\WebServices\PAM\API\APIKey::class, $pam_handlers['api']);
		$this->assertArrayHasKey(\Elgg\WebServices\PAM\API\Hmac::class, $pam_handlers['api']);
	}
	
	public function testInitNoPamHandlersWithEventResult() {
		$controller = new RestServiceController();
		
		_elgg_services()->events->registerHandler('rest', 'init', '\Elgg\Values::getTrue');
		
		$this->invokeInaccessableMethod($controller, 'initApi');
		
		$pam_handlers = $this->getInaccessableProperty(_elgg_services()->authentication, 'handlers');
		
		$this->assertIsArray($pam_handlers);
		$this->assertArrayHasKey('user', $pam_handlers);
		$this->assertArrayNotHasKey('api', $pam_handlers);
		
		$this->assertArrayNotHasKey(\Elgg\WebServices\PAM\User\AuthToken::class, $pam_handlers['user']);
	}
	
	public function testAuthenticateMethodWithUnknownMethod() {
		$controller = new RestServiceController();
		
		$this->expectException(\APIException::class);
		$this->expectExceptionMessage(elgg_echo('APIException:MethodCallNotImplemented', ['foo']));
		$this->invokeInaccessableMethod($controller, 'authenticateMethod', 'foo', 'GET');
	}
	
	public function testAuthenticateMethodWithFailedApiAuthentication() {
		$controller = new RestServiceController();
		
		$api = ApiMethod::factory([
			'method' => 'foo',
			'callback' => function() {
				return true;
			},
			'require_api_auth' => true,
		]);
		$registration = ApiRegistrationService::instance();
		$registration->registerApiMethod($api);
		
		$this->expectException(\APIException::class);
		$this->expectExceptionMessage(elgg_echo('APIException:APIAuthenticationFailed'));
		$this->invokeInaccessableMethod($controller, 'authenticateMethod', 'foo', 'GET');
	}
	
	public function testAuthenticateMethodWithFailedUserAuthentication() {
		$controller = new RestServiceController();
		
		$api = ApiMethod::factory([
			'method' => 'foo',
			'callback' => function() {
				return true;
			},
			'require_user_auth' => true,
		]);
		$registration = ApiRegistrationService::instance();
		$registration->registerApiMethod($api);
		
		$this->expectException(\APIException::class);
		$this->expectExceptionMessage(elgg_echo('SecurityException:authenticationfailed'));
		$this->invokeInaccessableMethod($controller, 'authenticateMethod', 'foo', 'GET');
	}
	
	public function testApiRequestSuccess() {
		$http_request = $this->prepareHttpRequest(elgg_generate_url('default:services:rest', [
			'view' => 'json',
			'method' => 'foo',
		]));
		$this->createService($http_request);
		
		$api = ApiMethod::factory([
			'method' => 'foo',
			'callback' => function() {
				return \SuccessResult::getInstance('bar');
			},
		]);
		$registration = ApiRegistrationService::instance();
		$registration->registerApiMethod($api);
		
		$controller = new RestServiceController();
		
		$request = $this->getRequest($http_request);
		
		$response = $controller($request);
		
		$this->assertInstanceOf(OkResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		
		$expected_result = json_encode([
			'status' => \SuccessResult::RESULT_SUCCESS,
			'result' => 'bar',
		]);
		$this->assertEquals($expected_result, $response->getContent());
	}
	
	public function testApiRequestError() {
		$http_request = $this->prepareHttpRequest(elgg_generate_url('default:services:rest', [
			'view' => 'json',
			'method' => 'foo',
		]));
		$this->createService($http_request);
		
		$api = ApiMethod::factory([
			'method' => 'foo',
			'callback' => function() {
				$result = \ErrorResult::getInstance('bar');
				$result->setHttpStatus(ELGG_HTTP_NOT_FOUND);
				
				return $result;
			},
		]);
		$registration = ApiRegistrationService::instance();
		$registration->registerApiMethod($api);
		
		$controller = new RestServiceController();
		
		$request = $this->getRequest($http_request);
		
		$response = $controller($request);
		
		$this->assertInstanceOf(OkResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_NOT_FOUND, $response->getStatusCode());
		
		$expected_result = json_encode([
			'status' => \ErrorResult::RESULT_FAIL,
			'message' => 'bar',
		]);
		$this->assertEquals($expected_result, $response->getContent());
	}
	
	public function testApiRequestAuthenticationError() {
		$http_request = $this->prepareHttpRequest(elgg_generate_url('default:services:rest', [
			'view' => 'json',
			'method' => 'foo',
		]));
		$this->createService($http_request);
		
		$api = ApiMethod::factory([
			'method' => 'foo',
			'callback' => function() {
				$result = \ErrorResult::getInstance('bar');
				$result->setHttpStatus(ELGG_HTTP_NOT_FOUND);
				
				return $result;
			},
			'require_api_auth' => true,
		]);
		$registration = ApiRegistrationService::instance();
		$registration->registerApiMethod($api);
		
		$controller = new RestServiceController();
		
		$request = $this->getRequest($http_request);
		
		$response = $controller($request);
		
		$this->assertInstanceOf(OkResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FORBIDDEN, $response->getStatusCode());
		
		$expected_result = json_encode([
			'status' => \ErrorResult::RESULT_FAIL,
			'message' => 'Missing API key',
		]);
		$this->assertEquals($expected_result, $response->getContent());
	}
}
