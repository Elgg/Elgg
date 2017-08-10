<?php

class ElggViewHelpersUnitTest extends \Elgg\UnitTestCase {

	public function test_elgg_prepend_css_path() {
		// basic tests
		$css = 'foo{background:url(foo/bar.png)}';
		$out = elgg_prepend_css_urls($css, '../');
		$this->assertEquals('foo{background:url(../foo/bar.png)}', $out);

		$css = 'foo{background:url("foo/bar.png")}';
		$out = elgg_prepend_css_urls($css, 'bing/');
		$this->assertEquals('foo{background:url("bing/foo/bar.png")}', $out);

		$css = 'foo{background:url(/foo/bar.png)}';
		$out = elgg_prepend_css_urls($css, '../');
		$this->assertEquals($css, $out);

		$css = 'foo{background:url("http://example.org/foo/bar.png")}';
		$out = elgg_prepend_css_urls($css, '../');
		$this->assertEquals($css, $out);

		// more complete: https://github.com/mrclay/minify/blob/master/tests/MinifyCSSUriRewriterTest.php
	}

	/**
	 * @dataProvider providerElggFormatElement
	 */
	public function testElggFormatElement($expected, $vars) {
		$tag_name = $vars['tag_name'];
		$text = isset($vars['text']) ? $vars['text'] : null;
		$opts = isset($vars['opts']) ? $vars['opts'] : [];
		$attrs = isset($vars['attrs']) ? $vars['attrs'] : [];
		$message = isset($vars['_msg']) ? $vars['_msg'] : null;
		unset($vars['tag_name'], $vars['text'], $vars['_msg']);

		$this->assertSame(elgg_format_element($tag_name, $attrs, $text, $opts), $expected, $message);

		$attrs['#tag_name'] = $tag_name;
		$attrs['#text'] = $text;
		$attrs['#options'] = $opts;
		$this->assertSame(elgg_format_element($attrs), $expected, $message);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testElggFormatElementThrows() {
		elgg_format_element([]);
	}

	function providerElggFormatElement() {
		$data = [
			'<span>a & b</span>' => [
				'tag_name' => 'span',
				'text' => 'a & b',
				'_msg' => 'Basic formatting, span recognized as non-void element',
			],
			'<span>a &amp; &amp; b</span>' => [
				'tag_name' => 'span',
				'text' => 'a & &amp; b',
				'opts' => ['encode_text' => true],
				'_msg' => 'HTML escaping, does not double encode',
			],
			'<span>a &amp;times; b</span>' => [
				'tag_name' => 'span',
				'text' => 'a &times; b',
				'opts' => ['encode_text' => true, 'double_encode' => true],
				'_msg' => 'HTML escaping double encodes',
			],
			'<IMG src="a &amp; b">' => [
				'tag_name' => 'IMG',
				'attrs' => ['src' => 'a & b'],
				'text' => 'should not appear',
				'_msg' => 'IMG recognized as void element, text ignored',
			],
			'<foo />' => [
				'tag_name' => 'foo',
				'opts' => ['is_void' => true, 'is_xml' => true],
				'_msg' => 'XML syntax for self-closing elements',
			],
		];
		$ret = [];
		foreach ($data as $one => $two) {
			$ret[] = [$one, $two];
		}
		return $ret;
	}

	/**
	 * @dataProvider providerElggGetFriendlyTime
	 */
	public function testElggGetFriendlyTime($num_seconds, $friendlytime) {
		$current_time = time();
		$this->assertSame(elgg_get_friendly_time($current_time + $num_seconds, $current_time), $friendlytime);
	}

	function providerElggGetFriendlyTime() {
		return [
			['0', elgg_echo('friendlytime:justnow')],
			['-120', elgg_echo('friendlytime:minutes', ['2'])],
			['-60', elgg_echo('friendlytime:minutes:singular')],
			['-10800', elgg_echo('friendlytime:hours', ['3'])],
			['-86400', elgg_echo('friendlytime:days:singular')],
			['120', elgg_echo('friendlytime:future:minutes', ['2'])],
			['86400', elgg_echo('friendlytime:future:days:singular')],
		];
	}
}
