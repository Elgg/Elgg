<?php

namespace Elgg\Http;

use Elgg\IntegrationTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Cookie;

class ResponseFactoryIntegrationTest extends IntegrationTestCase {
	
	/**
	 * @var ResponseFactory
	 */
	private $service;
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\BaseTestCase::up()
	 */
	public function up() {
		self::createApplication(['isolate'=> true]);
		
		$this->service = _elgg_services()->responseFactory;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\BaseTestCase::down()
	 */
	public function down() {
		if (isset($this->service)) {
			unset($this->service);
		}
	}
	
	public function testCanSetCookie() {
		$cookie = new \ElggCookie('foo');
		$cookie->value = 'bar';
		
		$this->assertTrue($this->service->setCookie($cookie));
		
		$headers = $this->service->getHeaders();
		$this->assertNotEmpty($headers);
		
		$header_cookies = $headers->getCookies();
		$this->assertIsArray($header_cookies);
		$this->assertCount(1, $header_cookies);
		
		$this->assertEquals($cookie->name, $header_cookies[0]->getName());
		$this->assertEquals($cookie->value, $header_cookies[0]->getValue());
	}
	
	public function testPrepareResponseContainsFactoryCookies() {
		$cookie = new \ElggCookie('foo');
		$cookie->value = 'bar';
		
		$this->assertTrue($this->service->setCookie($cookie));
		
		$this->service->getHeaders()->getCookies();
		
		$response = $this->service->prepareResponse();
		$this->assertInstanceOf(Response::class, $response);
		
		$cookie = $this->findCookie($response->headers, 'foo');
		
		$this->assertInstanceOf(Cookie::class, $cookie);
		$this->assertEquals('bar', $cookie->getValue());
	}
	
	public function testPrepareRedirectResponseContainsFactoryCookies() {
		$cookie = new \ElggCookie('foo');
		$cookie->value = 'bar';
		
		$this->assertTrue($this->service->setCookie($cookie));
		
		$this->service->getHeaders()->getCookies();
		
		$response = $this->service->prepareRedirectResponse('foo/bar');
		$this->assertInstanceOf(RedirectResponse::class, $response);
		
		$cookie = $this->findCookie($response->headers, 'foo');
		
		$this->assertInstanceOf(Cookie::class, $cookie);
		$this->assertEquals('bar', $cookie->getValue());
	}
	
	public function testPrepareJsonResponseContainsFactoryCookies() {
		$cookie = new \ElggCookie('foo');
		$cookie->value = 'bar';
		
		$this->assertTrue($this->service->setCookie($cookie));
		
		$this->service->getHeaders()->getCookies();
		
		$response = $this->service->prepareJsonResponse();
		$this->assertInstanceOf(JsonResponse::class, $response);
		
		$cookie = $this->findCookie($response->headers, 'foo');
		
		$this->assertInstanceOf(Cookie::class, $cookie);
		$this->assertEquals('bar', $cookie->getValue());
	}
	
	private function findCookie(ResponseHeaderBag $headerbag, string $cookie_name) {
		foreach ($headerbag->getCookies() as $cookie) {
			if ($cookie->getName() === $cookie_name) {
				return $cookie;
			}
		}
		
		return false;
	}
	
	/**
	 * @dataProvider respondWithErrorProvider
	 */
	public function testRespondWithErrorDefaultContentText($status_code, $elgg_echo_part) {
		
		ob_start();
		$response = new ErrorResponse('', $status_code);
		$response = $this->service->respondWithError($response);
		ob_end_clean();
		
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals($status_code, $response->getStatusCode());
		
		$content = $response->getContent();
		$this->assertStringContainsString(elgg_echo("error:{$elgg_echo_part}:title"), $content);
		$this->assertStringContainsString(elgg_echo("error:{$elgg_echo_part}:content"), $content);
	}
	
	public static function respondWithErrorProvider() {
		return [
			[ELGG_HTTP_BAD_REQUEST, 400],
			[ELGG_HTTP_FORBIDDEN, 403],
			[ELGG_HTTP_NOT_FOUND, 404],
			[ELGG_HTTP_UNAUTHORIZED, 'default'],
		];
	}
	
	public function testRespondWithErrorPassesException() {
		
		$exception_found = false;
		
		elgg_register_event_handler('view_vars', 'resources/error', function (\Elgg\Event $event) use (&$exception_found) {
			if (elgg_extract('exception', $event->getValue()) instanceof \Exception) {
				$exception_found = true;
			}
		});
		
		ob_start();
		$response = new ErrorResponse('');
		$response->setException(new \Exception('foo'));
		$this->service->respondWithError($response);
		ob_end_clean();
		
		$this->assertTrue($exception_found, 'No exception found in view vars of resource/error');
	}
	
	/**
	 * @dataProvider redirectCodeProvider
	 */
	public function testRespondWithRedirectCode(int $status_code, int $expected_code) {
		$request = $this->prepareHttpRequest('action/foo', 'POST', [], 0, true);
		_elgg_services()->request = $request;
		
		$response = new OkResponse('', $status_code, '/forward');
		
		ob_start();
		$result = $this->service->respond($response);
		ob_end_clean();
		
		$this->assertInstanceOf(Response::class, $result);
		$this->assertEquals($expected_code, $result->getStatusCode());
	}
	
	public static function redirectCodeProvider() {
		return [
			[ELGG_HTTP_OK, ELGG_HTTP_FOUND],
			[ELGG_HTTP_CREATED, ELGG_HTTP_CREATED],
			[ELGG_HTTP_MOVED_PERMANENTLY, ELGG_HTTP_MOVED_PERMANENTLY],
			[ELGG_HTTP_FOUND, ELGG_HTTP_FOUND],
			[ELGG_HTTP_SEE_OTHER, ELGG_HTTP_SEE_OTHER],
			[ELGG_HTTP_TEMPORARY_REDIRECT, ELGG_HTTP_TEMPORARY_REDIRECT],
			[ELGG_HTTP_PERMANENTLY_REDIRECT, ELGG_HTTP_PERMANENTLY_REDIRECT],
		];
	}
}
