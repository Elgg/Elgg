<?php

namespace Elgg\Http;

/**
 * @group UnitTests
 */
class RequestUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testCanDetectElggPath() {
		$req = Request::create("/foo/bar/");
		$this->assertEquals(['foo', 'bar'], $req->getUrlSegments());
	}

	public function testUrlSegmentsAutoHtmlEscaped() {
		$req = Request::create('/fo<script>alert("/ba&r/');
		$this->assertEquals(['fo&lt;script&gt;alert(&quot;', 'ba&amp;r'], $req->getUrlSegments());
	}

	public function testCanAccessRawUrlSegments() {
		$req = Request::create('/fo<script>alert("/ba&r/');
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
	
	public function testSetParamNoOverride() {
		$request = Request::create('/foo?bar=a');
		
		$this->assertEquals('a', $request->getParam('bar'));
		
		$request->setParam('bar', 'b');
		$this->assertNotEquals('b', $request->getParam('bar'));
	}
	
	public function testSetParamOverride() {
		$request = Request::create('/foo?bar=a');
		
		$this->assertEquals('a', $request->getParam('bar'));
		
		$request->setParam('bar', 'b', true);
		$this->assertEquals('b', $request->getParam('bar'));
	}

}
