<?php
namespace Elgg;

use UFCOE\Elgg\SitePath;

class UrlsServiceTest extends \Elgg\TestCase {

	protected function getSvc($path = '', $uri = '/elgg/', $site_url = 'http://example.com/elgg/') {
		$request = $this->prepareHttpRequest($path);
		$request->server->set('REQUEST_URI', $uri);
		return new UrlsService($request, $site_url);
	}

	function testCanGetCurrentUrl() {
		$svc = $this->getSvc('a', '/elgg/a?b=c');
		$this->assertSame('http://example.com/elgg/a?b=c', $svc->getCurrentUrl());
	}

	function testCanGetPath() {
		$this->assertSame('a&amp;/b', $this->getSvc('a&/b')->getPath());
	}

	function testCanGetSegments() {
		$this->assertSame(['a&amp;', 'b'], $this->getSvc('a&/b')->getUrlSegments());
	}

	function testWithinPath() {
		$this->assertTrue($this->getSvc('')->isWithinPath(''));
		$this->assertTrue($this->getSvc('foo')->isWithinPath(''));

		$this->assertFalse($this->getSvc('')->isWithinPath('foo'));
		$this->assertTrue($this->getSvc('foo')->isWithinPath('foo'));
		$this->assertTrue($this->getSvc('foo/bar')->isWithinPath('foo'));
		$this->assertFalse($this->getSvc('fo')->isWithinPath('foo'));
		$this->assertTrue($this->getSvc('foo/b')->isWithinPath('foo'));
	}

	function testIsSafeRedirect() {
		$this->assertFalse($this->getSvc()->isSafeRedirect('foo'));
		$this->assertFalse($this->getSvc()->isSafeRedirect('ftp://example.com/foo'));
		$this->assertTrue($this->getSvc()->isSafeRedirect('http://example.com/foo'));
		$this->assertTrue($this->getSvc()->isSafeRedirect('https://example.com'));

		$this->assertFalse($this->getSvc()->isSafeRedirect('http://example.com/foo', true));
		$this->assertFalse($this->getSvc()->isSafeRedirect('https://example.com', true));
		$this->assertTrue($this->getSvc()->isSafeRedirect('http://example.com/elgg', true));

		$this->assertFalse($this->getSvc()->isSafeRedirect('http://unsafe.com'));
		$validator = function ($host) {
			return $host === 'unsafe.com';
		};
		$this->assertTrue($this->getSvc()->isSafeRedirect('http://unsafe.com', false, $validator));
		$this->assertFalse($this->getSvc()->isSafeRedirect('http://unsafe2.com', false, $validator));
	}

	function testAnalyzeUrl() {
		$this->assertFalse($this->getSvc()->analyzePathWithinSite('not-a-url'));
		$this->assertFalse($this->getSvc()->analyzePathWithinSite('http://example.com/'));
		$this->assertInstanceOf(SitePath::class, $this->getSvc()->analyzePathWithinSite('http://example.com/elgg'));

		// basic tests. Component has its own tests
		$res = $this->getSvc()->analyzePathWithinSite('https://example.com/elgg/blog/view/123');
		// scheme mismatch
		$this->assertFalse($res);

		// allow scheme mismatch
		$res = $this->getSvc()->analyzePathWithinSite('https://example.com/elgg/blog/view/123', false);
		$this->assertInstanceOf(SitePath::class, $res);
		$this->assertSame(123, $res->getGuid());
	}

	function testNormalizeURL() {
		$svc = _elgg_services()->urls;

		$conversions = array(
			'http://example.com' => 'http://example.com',
			'https://example.com' => 'https://example.com',
			'http://example-time.com' => 'http://example-time.com',

			'//example.com' => '//example.com',
			'ftp://example.com/file' => 'ftp://example.com/file',
			'mailto:brett@elgg.org' => 'mailto:brett@elgg.org',
			'javascript:alert("test")' => 'javascript:alert("test")',
			'app://endpoint' => 'app://endpoint',

			'example.com' => 'http://example.com',
			'example.com/subpage' => 'http://example.com/subpage',

			'page/handler' =>                	elgg_get_site_url() . 'page/handler',
			'page/handler?p=v&p2=v2' =>      	elgg_get_site_url() . 'page/handler?p=v&p2=v2',
			'mod/plugin/file.php' =>            elgg_get_site_url() . 'mod/plugin/file.php',
			'mod/plugin/file.php?p=v&p2=v2' =>  elgg_get_site_url() . 'mod/plugin/file.php?p=v&p2=v2',
			'rootfile.php' =>                   elgg_get_site_url() . 'rootfile.php',
			'rootfile.php?p=v&p2=v2' =>         elgg_get_site_url() . 'rootfile.php?p=v&p2=v2',

			'/page/handler' =>               	elgg_get_site_url() . 'page/handler',
			'/page/handler?p=v&p2=v2' =>     	elgg_get_site_url() . 'page/handler?p=v&p2=v2',
			'/mod/plugin/file.php' =>           elgg_get_site_url() . 'mod/plugin/file.php',
			'/mod/plugin/file.php?p=v&p2=v2' => elgg_get_site_url() . 'mod/plugin/file.php?p=v&p2=v2',
			'/rootfile.php' =>                  elgg_get_site_url() . 'rootfile.php',
			'/rootfile.php?p=v&p2=v2' =>        elgg_get_site_url() . 'rootfile.php?p=v&p2=v2',
		);

		foreach ($conversions as $input => $output) {
			$this->assertSame($output, $svc->normalizeUrl($input));
		}
	}
}