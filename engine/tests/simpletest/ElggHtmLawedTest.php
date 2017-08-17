<?php

class ElggHtmLawedTest extends ElggCoreUnitTest {

	protected $configHooks = [];
	protected $styleHooks = [];

	// 'schemes' => '*:http,https,ftp,news,mailto,rtsp,teamspeak,gopher,mms,callto',
	protected $validSchemes = [
		'http',
		'https',
		'ftp',
		'news',
		'mailto',
		'rtsp',
		'teamspeak',
		'gopher',
		'mms',
		'callto'
	];

	protected $validStyles = [
		'color',
		'cursor',
		'text-align',
		'vertical-align',
		'font-size',
		'font-weight',
		'font-style',
		'border',
		'border-top',
		'background-color',
		'border-bottom',
		'border-left',
		'border-right',
		'margin',
		'margin-top',
		'margin-bottom',
		'margin-left',
		'margin-right',
		'padding',
		'float',
		'text-decoration'
	];

	public function up() {

	}

	public function down() {

	}

	/**
	 * Test anchor tags as input
	 */
	public function testHtmlawedFilterTagsAnchorsInput() {
		$tests = [];

		// these should all work
		foreach ($this->validSchemes as $scheme) {
			$input = "<a href=\"$scheme://test\">Test</a>";
			$tests[$input] = "<a href=\"$scheme://test\">Test</a>";
		}

		$bad_schemes = [
			'javascript',
			'itmss',
			'magnet'
		];

		// these should be denied
		foreach ($bad_schemes as $scheme) {
			$input = "<a href=\"$scheme://test\">Test</a>";
			$tests[$input] = "<a href=\"denied:$scheme://test\">Test</a>";
		}

		// set context to input to avoid adding nofollow
		elgg_push_context('input');

		foreach ($tests as $input => $expected) {
			$result = _elgg_htmlawed_filter_tags(null, null, $input);
			$this->assertEqual($expected, $result);
		}

		$weird_schemes = [
			'<a href="http://javascript:alert">Test</a>' => '<a href="http://javascript:alert">Test</a>',
			'<a href="javascript:https://">Test</a>' => '<a href="denied:javascript:https://">Test</a>',
			'<a href="ftp:\/\/">Test</a>' => '<a href="ftp:\/\/">Test</a>',
		];

		foreach ($weird_schemes as $input => $expected) {
			$result = _elgg_htmlawed_filter_tags(null, null, $input);
			$this->assertEqual($expected, $result);
		}

		elgg_pop_context();
	}

	/**
	 * Test anchor tags as output (adds nofollow)
	 */
	public function testHtmlawedFilterTagsAnchorsOutput() {
		$tests = [];

		foreach ($this->validSchemes as $scheme) {
			$input = "<a href=\"$scheme://test\">Test</a>";
			$tests[$input] = "<a rel=\"nofollow\" href=\"$scheme://test\">Test</a>";
		}

		foreach ($tests as $input => $expected) {
			$result = _elgg_htmlawed_filter_tags(null, null, $input);
			$this->assertEqual($expected, $result);
		}
	}

	/**
	 * Test styles attribute
	 */
	public function testHtmlawedFilterTagsStyles() {
		$tests = [];

		// valid
		foreach ($this->validStyles as $style) {
			$input = "<div style=\"{$style}: inherit;\">Test</div>";
			$tests[$input] = $input;
		}

		// valid without trailing ;
		foreach ($this->validStyles as $style) {
			$input = "<div style=\"{$style}: inherit\">Test</div>";
			$tests[$input] = "<div style=\"{$style}: inherit;\">Test</div>";
		}

		// invalid
		$bad_styles = [
			'height',
			'width',
			'z-index'
		];

		foreach ($bad_styles as $style) {
			$input = "<div style=\"{$style}: inherit;\">Test</div>";
			$tests[$input] = "<div>Test</div>";
		}

		// combine valid with invalid, should only get valid
		foreach ($this->validStyles as $style) {
			$input = "<div style=\"{$style}: inherit; height: 15px;\">Test</div>";
			$tests[$input] = "<div style=\"{$style}: inherit;\">Test</div>";
		}

		foreach ($tests as $input => $expected) {
			$result = _elgg_htmlawed_filter_tags(null, null, $input);
			$this->assertEqual($expected, $result);
		}
	}

	/**
	 * Test other tags and attributes
	 */
	public function testHtmlawedFilterTags() {
		// test input => expected
		$tests = [
			// duplicate closing tags, #311
			'<ul><li>item</li></ul>' => '<ul><li>item</li></ul>',

			// 'deny_attribute' => 'class, on*',
			'<div class="elgg-inner">Test</div>' => '<div>Test</div>',
			'<button onclick="javascript:alert(\'test\')">Test</button>' => '<button>Test</button>',

			// naked span stripping
			// https://github.com/vanilla/htmlawed/commit/995ef0ae4865d817a391ac62978f9d0e41d8a18b
			'<span>Test</span>' => 'Test',
			'<span id="test">Test</span>' => '<span id="test">Test</span>',
		];

		// test elements filtered by "safe" option
		// http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/htmLawed_README.htm#s3.3
		$unsafe = [
			'applet',
			'embed',
			'iframe',
			'object',
			'script'
		];

		foreach ($unsafe as $tag) {
			$input = "<$tag>Test</$tag>";
			$tests[$input] = 'Test';
		}

		foreach ($tests as $input => $expected) {
			$result = _elgg_htmlawed_filter_tags(null, null, $input);
			$this->assertEqual($expected, $result);
		}
	}

}
