<?php
namespace Elgg\Http;

use PHPUnit_Framework_TestCase as TestCase;

class RequestTest extends TestCase {

	public function testCanDetectElggPath() {
		$req = new Request([
			'__elgg_uri' => '/foo/bar/',
		]);
		$this->assertEquals(['foo', 'bar'], $req->getUrlSegments());
	}

	public function testClientIpChecksXRealIp() {
		$req = new Request();
		$req->server->set('HTTP_X_REAL_IP', '127.0.0.1');
		$this->assertEquals('127.0.0.1', $req->getClientIp());
	}

	public function testDetectsMixedCaseXhrHeader() {
		$req = new Request();
		$req->headers->set('X-Requested-With', 'xmlhttprequest');
		$this->assertTrue($req->isXmlHttpRequest());
	}

	public function testDetectsXhrFromGet() {
		$req = new Request([
			'X-Requested-With' => 'XMLHttpRequest',
		]);
		$this->assertTrue($req->isXmlHttpRequest());
	}

	public function testDetectsXhrFromPost() {
		$req = new Request([], [
			'X-Requested-With' => 'XMLHttpRequest',
		]);
		$this->assertTrue($req->isXmlHttpRequest());
	}

}