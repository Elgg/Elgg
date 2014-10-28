<?php

class HtmLawedTest extends ElggCoreUnitTest {

	protected $configHooks = array();
	protected $styleHooks = array();

	// 'schemes' => '*:http,https,ftp,news,mailto,rtsp,teamspeak,gopher,mms,callto',
	protected $validSchemes = array(
		'http', 'https', 'ftp', 'news', 'mailto',
		'rtsp', 'teamspeak', 'gopher', 'mms', 'callto'
	);

	protected $validStyles = array(
		'color', 'cursor', 'text-align', 'vertical-align', 'font-size',
		'font-weight', 'font-style', 'border', 'border-top', 'background-color',
		'border-bottom', 'border-left', 'border-right',
		'margin', 'margin-top', 'margin-bottom', 'margin-left',
		'margin-right',	'padding', 'float', 'text-decoration'
	);

	/**
	 * Called before each test object.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Called before each test method.
	 */
	public function setUp() {
		// unregister all config hooks to test stock
		// no way to do this through the API.
		global $CONFIG;

		if (isset($CONFIG->hooks['config']['htmlawed'])) {
			$this->configHooks = $CONFIG->hooks['config']['htmlawed'];
			$CONFIG->hooks['config']['htmlawed'] = array();
		}

		if (isset($CONFIG->hooks['allowed_styles']['htmlawed'])) {
			$this->styleHooks = $CONFIG->hooks['allowed_styles']['htmlawed'];
			$CONFIG->hooks['allowed_styles']['htmlawed'] = array();
		}
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		global $CONFIG;

		$CONFIG->hooks['config']['htmlawed'] = $this->configHooks;
		$CONFIG->hooks['allowed_styles']['htmlawed'] = $this->styleHooks;
	}

	/**
	 * Called after each test object.
	 */
	public function __destruct() {
		// all __destruct() code should go above here
		parent::__destruct();
	}

	/**
	 * Test anchor tags as input
	 */
	public function testHtmlawedFilterTagsAnchorsInput() {
		$tests = array();

		// these should all work
		foreach ($this->validSchemes as $scheme) {
			$input = "<a href=\"$scheme://test\">Test</a>";
			$tests[$input] = "<a href=\"$scheme://test\">Test</a>";
		}

		$bad_schemes = array('javascript', 'itmss', 'magnet');

		// these should be denied
		foreach ($bad_schemes as $scheme) {
			$input = "<a href=\"$scheme://test\">Test</a>";
			$tests[$input] = "<a href=\"denied:$scheme://test\">Test</a>";
		}

		// set context to input to avoid adding nofollow
		elgg_push_context('input');

		foreach ($tests as $input => $expected) {
			$result = htmlawed_filter_tags(null, null, $input);
			$this->assertEqual($expected, $result);
		}

		$weird_schemes = array(
			'<a href="http://javascript:alert">Test</a>' => '<a href="http://javascript:alert">Test</a>',
			'<a href="javascript:https://">Test</a>' => '<a href="denied:javascript:https://">Test</a>',
			'<a href="ftp:\/\/">Test</a>' => '<a href="ftp:\/\/">Test</a>',
		);

		foreach ($weird_schemes as $input => $expected) {
			$result = htmlawed_filter_tags(null, null, $input);
			$this->assertEqual($expected, $result);
		}

		elgg_pop_context();
	}

	/**
	 * Test anchor tags as output (adds nofollow)
	 */
	public function testHtmlawedFilterTagsAnchorsOutput() {
		$tests = array();

		foreach ($this->validSchemes as $scheme) {
			$input = "<a href=\"$scheme://test\">Test</a>";
			$tests[$input] = "<a rel=\"nofollow\" href=\"$scheme://test\">Test</a>";
		}

		foreach ($tests as $input => $expected) {
			$result = htmlawed_filter_tags(null, null, $input);
			$this->assertEqual($expected, $result);
		}
	}

	/**
	 * Test styles attribute
	 */
	public function testHtmlawedFilterTagsStyles() {
		$tests = array();

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
		$bad_styles = array(
			'height', 'width', 'z-index'
		);

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
			$result = htmlawed_filter_tags(null, null, $input);
			$this->assertEqual($expected, $result);
		}
	}

	/**
	 * Test other tags and attributes
	 */
	public function testHtmlawedFilterTags() {
		// test input => expected
		$tests = array(
			// duplicate closing tags, #311
			'<ul><li>item</li></ul>' => '<ul><li>item</li></ul>',

			// 'deny_attribute' => 'class, on*',
			'<span class="elgg-inner">Test</span>' => '<span>Test</span>',
			'<button onclick="javascript:alert(\'test\')">Test</button>' => '<button>Test</button>',
		);

		// test elements filtered by "safe" option
		// http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/htmLawed_README.htm#s3.3
		$unsafe = array('applet', 'embed', 'iframe', 'object', 'script');

		foreach ($unsafe as $tag) {
			$input = "<$tag>Test</$tag>";
			$tests[$input] = 'Test';
		}

		foreach ($tests as $input => $expected) {
			$result = htmlawed_filter_tags(null, null, $input);
			$this->assertEqual($expected, $result);
		}
	}

}