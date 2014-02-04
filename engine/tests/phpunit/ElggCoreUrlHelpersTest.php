<?php

$engine = dirname(dirname(dirname(__FILE__)));
require_once "$engine/lib/mb_wrapper.php";// for elgg_parse_str used by elgg_http_add_url_query_elements

/**
 * @see ElggCoreHelpersTest
 * @todo migrate similar simpletest tests to this class
 */
class ElggCoreUrlHelpersTest extends PHPUnit_Framework_TestCase {

	/**
	 * Test if elgg_http_add_url_query_elements() preserves original url when no params are passed
	 */
	public function testElggHttpAddURLQueryElementsPreserveURL() {
		$tests = array(
			array('', array(), '?'),
			array('/', array(), '/'),
			array('/path', array(), '/path'),
			array('example.com', array(), 'example.com'),
			array('example.com/path', array(), 'example.com/path'),
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
		);

		foreach ($tests as $test) {
			list($input, $params, $output) = $test;
			$this->assertEquals($output, elgg_http_add_url_query_elements($input, $params));
		}
	}

	/**
	 * Test elgg_http_add_url_query_elements() addition of parameters
	 */
	public function testElggHttpAddURLQueryElementsAddElements() {
		$tests = array(
			array('', array('foo' => 'bar'), '?foo=bar'),
			array('/', array('foo' => 'bar'), '/?foo=bar'),
			array('/path', array('foo' => 'bar'), '/path?foo=bar'),
			array('example.com', array('foo' => 'bar'), 'example.com?foo=bar'),
			array('example.com/path', array('foo' => 'bar'), 'example.com/path?foo=bar'),
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
		);

		foreach ($tests as $test) {
			list($input, $params, $output) = $test;
			$this->assertEquals($output, elgg_http_add_url_query_elements($input, $params));
		}
	}

	/**
	 * Test elgg_http_add_url_query_elements() removal of parameters
	 */
	public function testElggHttpAddURLQueryElementsRemoveElements() {
		$tests = array(
			array('?foo=bar', array('foo' => ''), '?foo='),
			array('?foo=bar', array('foo' => 0), '?foo=0'),
			array('?foo=bar', array('foo' => false), '?foo=0'),
			array('?foo=bar', array('foo' => null), '?'),
			array('/?foo=bar', array('foo' => null), '/'),
			array('/path?foo=bar', array('foo' => null), '/path'),

			array('example.com', array('foo' => null), 'example.com'),
			array('example.com?foo=bar', array('foo' => null), 'example.com'),
			array('example.com/path?foo=bar', array('foo' => null), 'example.com/path'),
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
		);

		foreach ($tests as $test) {
			list($input, $params, $output) = $test;
			$this->assertEquals($output, elgg_http_add_url_query_elements($input, $params));
			if ($params === array('foo' => null)) {
				$this->assertEquals($output, elgg_http_remove_url_query_element($input, 'foo'));
			}
		}
	}
}
