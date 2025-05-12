<?php

namespace Elgg\Http;

use Elgg\Ajax\Service;
use Elgg\Config;
use Elgg\EventsService;
use Elgg\HandlersService;
use Elgg\SystemMessagesService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ResponseFactoryUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var \ElggSession
	 */
	private $session;

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var SystemMessagesService
	 */
	private $system_messages;

	/**
	 * @var Service
	 */
	private $ajax;

	/**
	 * @var ResponseFactory
	 */
	private $response_factory;
	
	/**
	 * @var EventsService
	 */
	private $events;

	public function up() {
		$this->session = _elgg_services()->session;
		$this->config = _elgg_services()->config;
		$this->events = new EventsService(new HandlersService());
		$this->request = $this->createRequest('', 'GET');

		$this->system_messages = new SystemMessagesService($this->session);
		$this->ajax = new Service($this->events, $this->system_messages, $this->request, _elgg_services()->esm, _elgg_services()->externalFiles);

		_elgg_services()->logger->disable();
	}

	public function createService() {
		$svc = _elgg_services();
		
		$svc->set('session', $this->session);
		$svc->set('config', $this->config);
		$svc->set('events', $this->events);
		$svc->set('request', $this->request);
		$svc->set('system_messages', $this->system_messages);
		$svc->set('ajax', $this->ajax);

		$this->response_factory = new ResponseFactory($this->request, $this->ajax, $this->events);
		$svc->set('responseFactory', $this->response_factory);
		return $this->response_factory;
	}

	public function createRequest($uri = '', $method = 'POST', $parameters = [], $xhr = false) {
		$site_url = elgg_get_site_url();
		$path = substr(elgg_normalize_url($uri), strlen($site_url));
		$request = Request::create("/$path", $method, $parameters);

		$cookie_name = $this->config->getCookieConfig()['session']['name'];
		$session_id = $this->session->getID();
		$request->cookies->set($cookie_name, $session_id);

		if ($xhr) {
			$request->headers->set('X-Requested-With', 'XMLHttpRequest');
		}

		return $request;
	}

	public function testCanSetHeaders() {
		$this->createService();

		$this->assertInstanceOf(ResponseHeaderBag::class, _elgg_services()->responseFactory->getHeaders());

		elgg_set_http_header('X-Elgg-Testing: 1');

		$this->assertEquals('1', _elgg_services()->responseFactory->getHeaders()->get('X-Elgg-Testing'));

		_elgg_services()->responseFactory->setHeader('X-Elgg-Testing', '2', false);
		$this->assertEquals('1', _elgg_services()->responseFactory->getHeaders()->get('X-Elgg-Testing'));

		_elgg_services()->responseFactory->setHeader('X-Elgg-Testing', '2', true);
		$this->assertEquals('2', _elgg_services()->responseFactory->getHeaders()->get('X-Elgg-Testing'));

		_elgg_services()->responseFactory->setHeader('x-elgg-testing', '3', true);
		$this->assertEquals('3', _elgg_services()->responseFactory->getHeaders()->get('X-Elgg-Testing'));
	}

	public function testCanPrepareResponse() {
		$service = $this->createService();

		elgg_set_http_header('X-Elgg-Testing: 1');
		elgg_set_http_header('X-Elgg-Testing:2');
		elgg_set_http_header('content-type: text/html;charset=utf-8');
		elgg_set_http_header('Content-Type: application/json;charset=utf-8');
		elgg_set_http_header('X-Elgg-Override: 1');

		$content = json_encode(['foo' => 'bar']);
		$status_code = ELGG_HTTP_NOT_FOUND;
		$headers = [
			'X-Elgg-Response' => '1',
			'X-Elgg-Override' => '2',
		];

		$response = $service->prepareResponse($content, $status_code, $headers);

		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals($content, $response->getContent());
		$this->assertEquals($status_code, $response->getStatusCode());
		$this->assertEquals('1', $response->headers->get('X-Elgg-Response'));

		$this->assertEquals('2', $response->headers->get('X-Elgg-Testing'));
		$this->assertEquals('application/json;charset=utf-8', $response->headers->get('Content-Type'));
		$this->assertEquals('2', $response->headers->get('X-Elgg-Override'));
	}

	public function testCanPrepareRedirectResponse() {
		$service = $this->createService();

		elgg_set_http_header('X-Elgg-Testing: 2', true);
		elgg_set_http_header('X-Elgg-Override: 1');

		$url = 'http://localhost/foo';
		$status_code = ELGG_HTTP_MOVED_PERMANENTLY;
		$headers = [
			'X-Elgg-Response' => '1',
			'X-Elgg-Override' => '2',
		];

		$response = $service->prepareRedirectResponse($url, $status_code, $headers);

		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals($url, $response->getTargetURL());
		$this->assertEquals($status_code, $response->getStatusCode());
		$this->assertEquals('1', $response->headers->get('X-Elgg-Response'));

		$this->assertEquals('2', $response->headers->get('X-Elgg-Testing'));
		$this->assertEquals('2', $response->headers->get('X-Elgg-Override'));
	}

	public function testCanNotPrepareRedirectResponseWithInvalidCode() {
		$service = $this->createService();

		$url = 'http://localhost/foo';
		$status_code = ELGG_HTTP_OK;

		$this->expectException(\InvalidArgumentException::class);
		$service->prepareRedirectResponse($url, $status_code);
	}

	public function testCanSendReponse() {
		$service = $this->createService();

		ob_start();

		$response = $service->prepareResponse('foo');
		$this->assertInstanceOf(Response::class, $service->send($response));
		$this->assertEquals($response, $service->getSentResponse());

		$output = ob_get_clean();
		$this->assertEquals('foo', $output);
	}

	public function testSendsReponseOnlyOnce() {
		$service = $this->createService();

		ob_start();

		$response = $service->prepareResponse('foo');
		$sent_response = $service->send($response);
		$this->assertInstanceOf(Response::class, $sent_response);
		$this->assertEquals($sent_response, $service->send($response));

		$output = ob_get_clean();
		$this->assertEquals('foo', $output);
	}

	public function testCanNotSendModifiedResponse() {
		$service = $this->createService();

		$response = $service->prepareResponse('foo');
		ob_start();
		$sent_response = $service->send($response);
		ob_get_clean();

		$this->assertInstanceOf(Response::class, $sent_response);
		$response->headers->set('X-Elgg-Modified', '1');
		$this->assertEquals($sent_response, $service->send($response));
	}

	public function testCanNotSendASecondResponse() {
		$service = $this->createService();

		$response = $service->prepareResponse('foo');
		ob_start();
		$sent_response = $service->send($response);
		ob_get_clean();
		$this->assertInstanceOf(Response::class, $sent_response);

		$response2 = $service->prepareResponse('bar');
		$this->assertEquals($response, $service->send($response2));
	}

	public function testCanSendAjaxResponseFromOutput() {
		$service = $this->createService();

		ob_start();

		$data = ['foo' => 'bar'];
		$content = json_encode($data);
		$wrapped_content = json_encode(['value' => $data]);

		$response = $service->send($this->ajax->respondFromOutput($content));
		$this->assertInstanceOf(JsonResponse::class, $response);
		$this->assertEquals($wrapped_content, $response->getContent());

		$output = ob_get_clean();
		$this->assertEquals($wrapped_content, $output);
	}

	public function testCanNotSendANewResponseAfterAjaxResponseFromOutputIsSent() {
		$service = $this->createService();
		ob_start();
		$json_response = $this->ajax->respondFromOutput('foo');
		ob_get_clean();
		$this->assertEquals($json_response, $service->send($service->prepareResponse('bar')));
	}

	public function testCanSendAjaxResponseFromApiResponse() {
		$service = $this->createService();

		ob_start();

		$data = ['foo' => 'bar'];
		$wrapped_content = json_encode(['value' => $data]);

		$api_response = new \Elgg\Ajax\Response();
		$api_response->setData((object) [
					'value' => $data,
		]);

		$response = $service->send($this->ajax->respondFromApiResponse($api_response));

		$this->assertInstanceOf(JsonResponse::class, $response);
		$this->assertEquals($wrapped_content, $response->getContent());

		$output = ob_get_clean();
		$this->assertEquals($wrapped_content, $output);
	}

	public function testCanNotSendANewResponseAfterAjaxResponseFromApiResponseIsSent() {
		$service = $this->createService();
		$api_response = new \Elgg\Ajax\Response();
		$api_response->setData((object) [
					'value' => 'foo',
		]);
		ob_start();
		$json_response = $this->ajax->respondFromApiResponse($api_response);
		ob_get_clean();

		$this->assertEquals($json_response, $service->send($service->prepareResponse('bar')));
	}

	public function testCanSendAjaxErrorResponse() {
		$service = $this->createService();

		ob_start();

		$error = 'error';
		$wrapped_content = json_encode(['error' => $error]);

		$response = $service->send($this->ajax->respondWithError($error));

		$this->assertInstanceOf(JsonResponse::class, $response);
		$this->assertEquals($wrapped_content, $response->getContent());

		$output = ob_get_clean();
		$this->assertEquals($wrapped_content, $output);
	}

	public function testCanNotSendANewResponseAfterAjaxErrorResponseIsSent() {
		$service = $this->createService();
		ob_start();
		$json_response = $this->ajax->respondWithError('error');
		ob_get_clean();
		$this->assertEquals($json_response, $service->send($service->prepareResponse('bar')));
	}

	public function testCanDetectXhrRequest() {
		$service = $this->createService();
		$this->assertFalse($service->isXhr());

		$this->request = $this->createRequest('foo', 'POST', [], true);
		$service = $this->createService();

		$this->assertTrue($service->isXhr());
	}

	public function testCanDetectActionRequest() {
		$service = $this->createService();
		$this->assertFalse($service->isAction());

		$this->request = $this->createRequest('action/foo/bar', 'POST', [], true);
		$service = $this->createService();

		$this->assertTrue($service->isXhr());
		$this->assertTrue($service->isAction());

		$this->request = $this->createRequest('action/foo/bar', 'POST');
		$service = $this->createService();

		$this->assertFalse($service->isXhr());
		$this->assertTrue($service->isAction());
	}

	/**
	 * @dataProvider requestContextDataProvider
	 */
	public function testCanParseContext($path, $expected) {
		$this->request = $this->createRequest($path);
		$service = $this->createService();
		$this->assertEquals($expected, $service->parseContext());
	}

	public static function requestContextDataProvider() {
		return [
			['ajax/view/foo/bar/', 'view:foo/bar'],
			['ajax/form/foo/bar/baz/', 'form:foo/bar/baz'],
			['ajax/foo/bar', 'path:ajax/foo/bar'],
			['ajax/baz/', 'path:ajax/baz'],
			['action/foo/bar', 'action:foo/bar'],
			['action/baz/', 'action:baz'],
			['cache/foo', 'path:cache/foo'],
			['foo/bar/ajax', 'path:foo/bar/ajax'],
		];
	}

	/**
	 * @dataProvider stringifyProvider
	 */
	public function testStringify($input, $expected_output) {
		$this->createService();
		
		$this->assertEquals($expected_output, $this->response_factory->stringify($input));
	}
	
	public static function stringifyProvider() {
		$std = new \stdClass();
		$std->foo = 'bar';
		
		return [
			['foo', 'foo'],
			[['foo'], '["foo"]'],
			['', ''],
			[[], ''],
			[false, ''],
			[true, '1'],
			[0, '0'],
			[0.0, '0'],
			[0.1, '0.1'],
			[null, ''],
			[new \stdClass(), '{}'],
			[$std, '{"foo":"bar"}'],
		];
	}
}
