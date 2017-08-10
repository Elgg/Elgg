<?php

/**
 * @see \ElggCoreHelpersTest
 * @todo migrate similar simpletest tests to this class
 */
class ElggCoreUrlHelpersUnitTest extends \Elgg\UnitTestCase {

	/**
	 * Test if elgg_http_add_url_query_elements() preserves original url when no params are passed
	 *
	 * @dataProvider providerElggHttpAddURLQueryElementsPreserveURL
	 */
	public function testElggHttpAddURLQueryElementsPreserveURL($input, $params, $output) {
		$this->assertEquals($output, elgg_http_add_url_query_elements($input, $params));
	}

	function providerElggHttpAddURLQueryElementsPreserveURL() {
		return[
			['', [], '?'],
			['/', [], '/'],
			['/path', [], '/path'],
			['example.com', [], 'example.com'],
			['example.com/path', [], 'example.com/path'],
			['//example.com/path', [], '//example.com/path'],
			['http://example.com', [], 'http://example.com?'],
			['http://example.com/path', [], 'http://example.com/path'],
			['http://example.com/path#anchor', [], 'http://example.com/path#anchor'],
			['https://example.com', [], 'https://example.com?'],
			['https://example.com#anchor', [], 'https://example.com?#anchor'],
			['https://example.com/path', [], 'https://example.com/path'],
			['http://example-time.com', [], 'http://example-time.com?'],
			['http://example-time.com/path', [], 'http://example-time.com/path'],
			['ftp://example.com/', [], 'ftp://example.com/'],
			['ftp://example.com/file', [], 'ftp://example.com/file'],
			['app://endpoint', [], 'app://endpoint?'],
			['app://endpoint/path', [], 'app://endpoint/path'],
			['https://example.com?foo=123&bar=abc', [], 'https://example.com?foo=123&bar=abc'],
			['https://example.com/path?foo=123&bar=abc', [], 'https://example.com/path?foo=123&bar=abc'],
		];
	}

	/**
	 * Test elgg_http_add_url_query_elements() addition of parameters
	 *
	 * @dataProvider providerElggHttpAddURLQueryElementsAddElements
	 */
	public function testElggHttpAddURLQueryElementsAddElements($input, $params, $output) {
		$this->assertEquals($output, elgg_http_add_url_query_elements($input, $params));
	}

	function providerElggHttpAddURLQueryElementsAddElements() {
		return [
			['', ['foo' => 'bar'], '?foo=bar'],
			['/', ['foo' => 'bar'], '/?foo=bar'],
			['/path', ['foo' => 'bar'], '/path?foo=bar'],
			['example.com', ['foo' => 'bar'], 'example.com?foo=bar'],
			['example.com/path', ['foo' => 'bar'], 'example.com/path?foo=bar'],
			['//example.com/path', ['foo' => 'bar'], '//example.com/path?foo=bar'],
			['http://example.com', ['foo' => 'bar'], 'http://example.com?foo=bar'],
			['http://example.com/#anchor', ['foo' => 'bar'], 'http://example.com/?foo=bar#anchor'],
			['http://example.com/path', ['foo' => 'bar'], 'http://example.com/path?foo=bar'],
			['https://example.com', ['foo' => 'bar'], 'https://example.com?foo=bar'],
			['https://example.com/path', ['foo' => 'bar'], 'https://example.com/path?foo=bar'],
			['http://example-time.com', ['foo' => 'bar'], 'http://example-time.com?foo=bar'],
			['http://example-time.com/path', ['foo' => 'bar'], 'http://example-time.com/path?foo=bar'],
			['ftp://example.com/', ['foo' => 'bar'], 'ftp://example.com/?foo=bar'],
			['ftp://example.com/file', ['foo' => 'bar'], 'ftp://example.com/file?foo=bar'],
			['app://endpoint', ['foo' => 'bar'], 'app://endpoint?foo=bar'],
			['app://endpoint/path', ['foo' => 'bar'], 'app://endpoint/path?foo=bar'],
			['https://example.com?foo=123&bar=abc', ['foo2' => 'bar2'], 'https://example.com?foo=123&bar=abc&foo2=bar2'],
			['https://example.com/path?foo=123&bar=abc', ['foo' => 'bar'], 'https://example.com/path?foo=bar&bar=abc'],
			['https://example.com?foo=123&bar=abc', ['foo2' => 'bar2', '123' => 456], 'https://example.com?foo=123&bar=abc&foo2=bar2&123=456'],
			['https://example.com/path?foo=123&bar=abc', ['foo' => 'bar'], 'https://example.com/path?foo=bar&bar=abc'],
		];
	}

	/**
	 * Test elgg_http_add_url_query_elements() removal of parameters
	 *
	 * @dataProvider providerElggHttpAddURLQueryElementsRemoveElements
	 */
	public function testElggHttpAddURLQueryElementsRemoveElements($input, $params, $output) {
		$this->assertEquals($output, elgg_http_add_url_query_elements($input, $params));
		if ($params === ['foo' => null]) {
			$this->assertEquals($output, elgg_http_remove_url_query_element($input, 'foo'));
		}
	}

	function providerElggHttpAddURLQueryElementsRemoveElements() {
		return [
			['?foo=bar', ['foo' => ''], '?foo='],
			['?foo=bar', ['foo' => 0], '?foo=0'],
			['?foo=bar', ['foo' => false], '?foo=0'],
			['?foo=bar', ['foo' => null], '?'],
			['/?foo=bar', ['foo' => null], '/'],
			['/path?foo=bar', ['foo' => null], '/path'],
			['example.com', ['foo' => null], 'example.com'],
			['example.com?foo=bar', ['foo' => null], 'example.com'],
			['example.com/path?foo=bar', ['foo' => null], 'example.com/path'],
			['//example.com/path?foo=bar', ['foo' => null], '//example.com/path'],
			['http://example.com', ['foo' => null], 'http://example.com?'],
			['http://example.com?foo=bar', ['foo' => null], 'http://example.com?'],
			['http://example.com/?foo=bar#anchor', ['foo' => null], 'http://example.com/#anchor'],
			['http://example.com/path?foo=bar', ['foo' => null], 'http://example.com/path'],
			['https://example.com?foo=bar', ['foo' => null], 'https://example.com?'],
			['https://example.com/path?foo=bar', ['foo' => null], 'https://example.com/path'],
			['http://example-time.com?foo=bar', ['foo' => null], 'http://example-time.com?'],
			['http://example-time.com/path?foo=bar', ['foo' => null], 'http://example-time.com/path'],
			['ftp://example.com/?foo=bar', ['foo' => null], 'ftp://example.com/'],
			['ftp://example.com/file?foo=bar', ['foo' => null], 'ftp://example.com/file'],
			['app://endpoint?foo=bar', ['foo' => null], 'app://endpoint?'],
			['app://endpoint/path?foo=bar', ['foo' => null], 'app://endpoint/path'],
			//add and delete at the same time
			['https://example.com?foo=123&bar=abc', ['foo' => null, 'foo2' => 'bar2'], 'https://example.com?bar=abc&foo2=bar2'],
			['https://example.com/path?bar=abc&foo=123', ['foo' => null, 'foo2' => 'bar'], 'https://example.com/path?bar=abc&foo2=bar'],
			['https://example.com?foo=123&bar=abc', ['foo' => null, 'foo2' => 'bar2', '123' => 456], 'https://example.com?bar=abc&foo2=bar2&123=456'],
			['https://example.com/path?foo=123&bar=abc', ['foo2' => 'bar', 'foo' => null], 'https://example.com/path?bar=abc&foo2=bar'],
		];
	}


	/**
	 * @dataProvider providerHttpUrlIsIdentical
	 */
	public function testHttpUrlIsIdentical($input, $output) {
		$this->assertTrue(elgg_http_url_is_identical($output, $input), "Failed to determine URLs as identical for: '$output' and '$input'");
		$this->assertTrue(elgg_http_url_is_identical($input, $output), "Failed to determine URLs as identical for: '$input' and '$output'");
	}

	function providerHttpUrlIsIdentical() {
		$data = [
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
			'search?foo.bar' =>                 elgg_get_site_url() . 'search?foo.bar',
			'rootfile.php' =>                   elgg_get_site_url() . 'rootfile.php',
			'rootfile.php?p=v&p2=v2' =>         elgg_get_site_url() . 'rootfile.php?p=v&p2=v2',

			'/page/handler' =>               	elgg_get_site_url() . 'page/handler',
			'/page/handler?p=v&p2=v2' =>     	elgg_get_site_url() . 'page/handler?p=v&p2=v2',
			'/mod/plugin/file.php' =>           elgg_get_site_url() . 'mod/plugin/file.php',
			'/mod/plugin/file.php?p=v&p2=v2' => elgg_get_site_url() . 'mod/plugin/file.php?p=v&p2=v2',
			'/rootfile.php' =>                  elgg_get_site_url() . 'rootfile.php',
			'/rootfile.php?p=v&p2=v2' =>        elgg_get_site_url() . 'rootfile.php?p=v&p2=v2',
		];
		$ret = [];
		foreach ($data as $in => $out) {
			$ret[] = [$in, $out];
		}
		return $ret;
	}

	/**
	 * @dataProvider providerHttpUrlIsIdenticalIgnoreParamsHandling
	 */
	public function testHttpUrlIsIdenticalIgnoreParamsHandling($url1, $url2, $ignore_params, $result) {
		$this->assertSame(elgg_http_url_is_identical($url1, $url2, $ignore_params), $result, "Failed to determine URLs as "
			. ($result ? 'identical' : 'different') . " for: '$url1', '$url2' and ignore params set to " . print_r($ignore_params, true));
		$this->assertSame(elgg_http_url_is_identical($url2, $url1, $ignore_params), $result, "Failed to determine URLs as "
			. ($result ? 'identical' : 'different') . " for: '$url2', '$url1' and ignore params set to " . print_r($ignore_params, true));
	}

	function providerHttpUrlIsIdenticalIgnoreParamsHandling() {
		return [
			['page/handler', elgg_get_site_url() . 'page/handler', ['p', 'p2'], true],
			['page/handler?p=v&p2=q2', elgg_get_site_url() . 'page/handler?p=q&p2=v2', ['p', 'p2'], true],
			['/rootfile.php', elgg_get_site_url() . 'rootfile.php?param=23', ['param'], true],
			['/rootfile.php?p=v&p2=v2', elgg_get_site_url() . 'rootfile.php?p=v&p2=q', ['p', 'p2'], true],
			['mod/plugin/file.php?other_param=123', elgg_get_site_url() . 'mod/plugin/file.php', ['q', 'p2'], false],
			['/rootfile.php', elgg_get_site_url() . 'rootfile.php?param=23', [], false],
		];
	}
}
