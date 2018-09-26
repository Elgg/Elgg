<?php

/**
 * @see \ElggCoreHelpersTest
 * @todo migrate similar simpletest tests to this class
 *
 * @group UnitTests
 */
class ElggCoreUrlHelpersUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

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
			array('', array(), '?'),
			array('/', array(), '/'),
			array('/path', array(), '/path'),
			array('example.com', array(), 'example.com'),
			array('example.com/path', array(), 'example.com/path'),
			array('//example.com/path', array(), '//example.com/path'),
			array('http://example.com', array(), 'http://example.com?'),
			array('http://example.com/path', array(), 'http://example.com/path'),
			array('http://example.com/path#anchor', array(), 'http://example.com/path#anchor'),
			array('https://example.com', array(), 'https://example.com?'),
			array('https://example.com#anchor', array(), 'https://example.com?#anchor'),
			array('https://example.com/path', array(), 'https://example.com/path'),
			array('http://example-time.com', array(), 'http://example-time.com?'),
			array('http://example-time.com/path', array(), 'http://example-time.com/path'),
			array('ftp://example.com/', array(), 'ftp://example.com/'),
			array('ftp://example.com/file', array(), 'ftp://example.com/file'),
			array('app://endpoint', array(), 'app://endpoint?'),
			array('app://endpoint/path', array(), 'app://endpoint/path'),
			array('https://example.com?foo=123&bar=abc', array(), 'https://example.com?foo=123&bar=abc'),
			array('https://example.com/path?foo=123&bar=abc', array(), 'https://example.com/path?foo=123&bar=abc'),
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
			array('', array('foo' => 'bar'), '?foo=bar'),
			array('/', array('foo' => 'bar'), '/?foo=bar'),
			array('/path', array('foo' => 'bar'), '/path?foo=bar'),
			array('example.com', array('foo' => 'bar'), 'example.com?foo=bar'),
			array('example.com/path', array('foo' => 'bar'), 'example.com/path?foo=bar'),
			array('//example.com/path', array('foo' => 'bar'), '//example.com/path?foo=bar'),
			array('http://example.com', array('foo' => 'bar'), 'http://example.com?foo=bar'),
			array('http://example.com/#anchor', array('foo' => 'bar'), 'http://example.com/?foo=bar#anchor'),
			array('http://example.com/path', array('foo' => 'bar'), 'http://example.com/path?foo=bar'),
			array('https://example.com', array('foo' => 'bar'), 'https://example.com?foo=bar'),
			array('https://example.com/path', array('foo' => 'bar'), 'https://example.com/path?foo=bar'),
			array('http://example-time.com', array('foo' => 'bar'), 'http://example-time.com?foo=bar'),
			array('http://example-time.com/path', array('foo' => 'bar'), 'http://example-time.com/path?foo=bar'),
			array('ftp://example.com/', array('foo' => 'bar'), 'ftp://example.com/?foo=bar'),
			array('ftp://example.com/file', array('foo' => 'bar'), 'ftp://example.com/file?foo=bar'),
			array('app://endpoint', array('foo' => 'bar'), 'app://endpoint?foo=bar'),
			array('app://endpoint/path', array('foo' => 'bar'), 'app://endpoint/path?foo=bar'),
			array('https://example.com?foo=123&bar=abc', array('foo2' => 'bar2'), 'https://example.com?foo=123&bar=abc&foo2=bar2'),
			array('https://example.com/path?foo=123&bar=abc', array('foo' => 'bar'), 'https://example.com/path?foo=bar&bar=abc'),
			array('https://example.com?foo=123&bar=abc', array('foo2' => 'bar2', '123' => 456), 'https://example.com?foo=123&bar=abc&foo2=bar2&123=456'),
			array('https://example.com/path?foo=123&bar=abc', array('foo' => 'bar'), 'https://example.com/path?foo=bar&bar=abc'),
		];
	}

	/**
	 * Test elgg_http_add_url_query_elements() removal of parameters
	 *
	 * @dataProvider providerElggHttpAddURLQueryElementsRemoveElements
	 */
	public function testElggHttpAddURLQueryElementsRemoveElements($input, $params, $output) {
		$this->assertEquals($output, elgg_http_add_url_query_elements($input, $params));
		if ($params === array('foo' => null)) {
			$this->assertEquals($output, elgg_http_remove_url_query_element($input, 'foo'));
		}
	}

	function providerElggHttpAddURLQueryElementsRemoveElements() {
		return [
			array('?foo=bar', array('foo' => ''), '?foo='),
			array('?foo=bar', array('foo' => 0), '?foo=0'),
			array('?foo=bar', array('foo' => false), '?foo=0'),
			array('?foo=bar', array('foo' => null), '?'),
			array('/?foo=bar', array('foo' => null), '/'),
			array('/path?foo=bar', array('foo' => null), '/path'),
			array('example.com', array('foo' => null), 'example.com'),
			array('example.com?foo=bar', array('foo' => null), 'example.com'),
			array('example.com/path?foo=bar', array('foo' => null), 'example.com/path'),
			array('//example.com/path?foo=bar', array('foo' => null), '//example.com/path'),
			array('http://example.com', array('foo' => null), 'http://example.com?'),
			array('http://example.com?foo=bar', array('foo' => null), 'http://example.com?'),
			array('http://example.com/?foo=bar#anchor', array('foo' => null), 'http://example.com/#anchor'),
			array('http://example.com/path?foo=bar', array('foo' => null), 'http://example.com/path'),
			array('https://example.com?foo=bar', array('foo' => null), 'https://example.com?'),
			array('https://example.com/path?foo=bar', array('foo' => null), 'https://example.com/path'),
			array('http://example-time.com?foo=bar', array('foo' => null), 'http://example-time.com?'),
			array('http://example-time.com/path?foo=bar', array('foo' => null), 'http://example-time.com/path'),
			array('ftp://example.com/?foo=bar', array('foo' => null), 'ftp://example.com/'),
			array('ftp://example.com/file?foo=bar', array('foo' => null), 'ftp://example.com/file'),
			array('app://endpoint?foo=bar', array('foo' => null), 'app://endpoint?'),
			array('app://endpoint/path?foo=bar', array('foo' => null), 'app://endpoint/path'),
			//add and delete at the same time
			array('https://example.com?foo=123&bar=abc', array('foo' => null, 'foo2' => 'bar2'), 'https://example.com?bar=abc&foo2=bar2'),
			array('https://example.com/path?bar=abc&foo=123', array('foo' => null, 'foo2' => 'bar'), 'https://example.com/path?bar=abc&foo2=bar'),
			array('https://example.com?foo=123&bar=abc', array('foo' => null, 'foo2' => 'bar2', '123' => 456), 'https://example.com?bar=abc&foo2=bar2&123=456'),
			array('https://example.com/path?foo=123&bar=abc', array('foo2' => 'bar', 'foo' => null), 'https://example.com/path?bar=abc&foo2=bar'),
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
		self::createApplication();

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

			'foobar#test' => 'foobar',
			'/' => elgg_get_site_url(),
			'#test' => '#test',

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
	 * @dataProvider providerHttpUrlIsNotIdentical
	 */
	public function testHttpUrlIsNotIdentical($input, $output) {
		$this->assertFalse(elgg_http_url_is_identical($output, $input), "Failed to determine URLs as NOT identical for: '$output' and '$input'");
		$this->assertFalse(elgg_http_url_is_identical($input, $output), "Failed to determine URLs as NOT identical for: '$input' and '$output'");
	}

	function providerHttpUrlIsNotIdentical() {
		self::createApplication();

		$data = [
			'http://example1.com' => 'http://example2.com',
			'https://example.com' => 'http://example.com',
			'0' => 0,
			false => elgg_get_site_url(),
			true => elgg_get_site_url(),
			'#test' => elgg_get_site_url(),
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
		self::createApplication();

		return [
			array('page/handler', elgg_get_site_url() . 'page/handler', array('p', 'p2'), true),
			array('page/handler?p=v&p2=q2', elgg_get_site_url() . 'page/handler?p=q&p2=v2', array('p', 'p2'), true),
			array('/rootfile.php', elgg_get_site_url() . 'rootfile.php?param=23', array('param'), true),
			array('/rootfile.php?p=v&p2=v2', elgg_get_site_url() . 'rootfile.php?p=v&p2=q', array('p', 'p2'), true),
			array('mod/plugin/file.php?other_param=123', elgg_get_site_url() . 'mod/plugin/file.php', array('q', 'p2'), false),
			array('/rootfile.php', elgg_get_site_url() . 'rootfile.php?param=23', array(), false),
		];
	}
}
