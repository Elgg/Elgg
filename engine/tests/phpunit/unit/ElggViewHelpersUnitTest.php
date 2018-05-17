<?php

/**
 * @group UnitTests
 */
class ElggViewHelpersUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

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
}
