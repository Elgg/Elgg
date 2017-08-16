<?php

namespace Elgg\Security;

/**
 * @group Security
 * @group UnitTests
 */
class SignedUrlTest extends \Elgg\UnitTestCase {

	/**
	 * @var UrlSigner
	 */
	private $service;

	public function up() {
		$this->service = new UrlSigner();
		$this->url = '/foo?a=b&c[]=1&c[]=2&c[]=0,5&_d=@username&e=%20';

		_elgg_services()->setValue('session', \ElggSession::getMock());
	}

	public function down() {

	}
	
	public function testCanSignUrl() {
		$signed_url = $this->service->sign($this->url);
		$this->assertTrue($this->service->isValid($signed_url));
	}
	
	public function testCanSignExpiringUrl() {
		$valid_signed_url = $this->service->sign($this->url, '+1 day');
		$invalid_signed_url = $this->service->sign($this->url, '-1 day');

		$this->assertTrue($this->service->isValid($valid_signed_url));
		$this->assertFalse($this->service->isValid($invalid_signed_url));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testCanNotDoubleSignUrl() {
		$signed_url = $this->service->sign($this->url);
		$this->service->sign($signed_url);
	}

	public function testCanNotValidateAlteredUrl() {
		$signed_url = $this->service->sign($this->url, '+1 hour');
		$this->assertTrue($this->service->isValid($signed_url));

		$signed_url = elgg_http_remove_url_query_element($signed_url, UrlSigner::KEY_EXPIRES);
		$this->assertFalse($this->service->isValid($signed_url));
	}

	public function testCanValidateAcrossMultipleSession() {
		$signed_url = $this->service->sign($this->url, '+1 day');
		$this->assertTrue($this->service->isValid($signed_url));

		_elgg_services()->session->invalidate();
		_elgg_services()->session->start();
		
		$this->assertTrue($this->service->isValid($signed_url));
	}

}