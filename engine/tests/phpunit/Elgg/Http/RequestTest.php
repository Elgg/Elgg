<?php

namespace Elgg\Http;

class RequestTest extends \Elgg\TestCase {

	public function testCanDetectElggPath() {
		$req = new Request([
			'__elgg_uri' => '/foo/bar/',
		]);
		$this->assertEquals(['foo', 'bar'], $req->getUrlSegments());
	}

	public function testUrlSegmentsAutoHtmlEscaped() {
		$req = new Request([
			'__elgg_uri' => '/fo<script>alert("/ba&r/',
		]);
		$this->assertEquals(['fo&lt;script&gt;alert(&quot;', 'ba&amp;r'], $req->getUrlSegments());
	}

	public function testCanAccessRawUrlSegments() {
		$req = new Request([
			'__elgg_uri' => '/fo<script>alert("/ba&r/',
		]);
		$this->assertEquals(['fo<script>alert("', 'ba&r'], $req->getUrlSegments(true));
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
