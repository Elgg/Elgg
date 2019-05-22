<?php

namespace Elgg\Http;

use Elgg\IntegrationTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

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
		$this->assertInternalType('array', $header_cookies);
		$this->assertCount(1, $header_cookies);
		
		$this->assertEquals($cookie->name, $header_cookies[0]->getName());
		$this->assertEquals($cookie->value, $header_cookies[0]->getValue());
	}
	
	public function testPrepareResponseContainsFactoryCookies() {
		$cookie = new \ElggCookie('foo');
		$cookie->value = 'bar';
		
		$this->assertTrue($this->service->setCookie($cookie));
		
		$factory_cookies = $this->service->getHeaders()->getCookies();
		
		$response = $this->service->prepareResponse();
		$this->assertInstanceOf(Response::class, $response);
		
		$response_cookies = $response->headers->getCookies();
		
		$this->assertEquals($factory_cookies, $response_cookies);
	}
	
	public function testPrepareRedirectResponseContainsFactoryCookies() {
		$cookie = new \ElggCookie('foo');
		$cookie->value = 'bar';
		
		$this->assertTrue($this->service->setCookie($cookie));
		
		$factory_cookies = $this->service->getHeaders()->getCookies();
		
		$response = $this->service->prepareRedirectResponse('foo/bar');
		$this->assertInstanceOf(RedirectResponse::class, $response);
		
		$response_cookies = $response->headers->getCookies();
		
		$this->assertEquals($factory_cookies, $response_cookies);
	}
	
	public function testPrepareJsonResponseContainsFactoryCookies() {
		$cookie = new \ElggCookie('foo');
		$cookie->value = 'bar';
		
		$this->assertTrue($this->service->setCookie($cookie));
		
		$factory_cookies = $this->service->getHeaders()->getCookies();
		
		$response = $this->service->prepareJsonResponse();
		$this->assertInstanceOf(JsonResponse::class, $response);
		
		$response_cookies = $response->headers->getCookies();
		
		$this->assertEquals($factory_cookies, $response_cookies);
	}
}
