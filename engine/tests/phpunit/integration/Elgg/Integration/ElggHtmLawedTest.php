<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;
use Elgg\HooksRegistrationService\Hook;

class ElggHtmLawedTest extends IntegrationTestCase {

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
		'callto',
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
		'text-decoration',
	];

	public function up() {
		// only use the Elgg core htmlAwed configuration
		elgg()->hooks->backup();
		elgg()->hooks->registerHandler('validate', 'input', '_elgg_htmlawed_filter_tags', 1);
	}

	public function down() {
		elgg()->hooks->restore();
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
			'magnet',
		];

		// these should be denied
		foreach ($bad_schemes as $scheme) {
			$input = "<a href=\"$scheme://test\">Test</a>";
			$tests[$input] = "<a href=\"denied:$scheme://test\">Test</a>";
		}

		// set context to input to avoid adding nofollow
		elgg_push_context('input');

		foreach ($tests as $input => $expected) {
			$handler = new \Elgg\Input\ValidateInputHandler();
			$result = $handler(new Hook(elgg(), null, null, $input, []));
			$this->assertEquals($expected, $result);
		}

		$weird_schemes = [
			'<a href="http://javascript:alert">Test</a>' => '<a href="http://javascript:alert">Test</a>',
			'<a href="javascript:https://">Test</a>' => '<a href="denied:javascript:https://">Test</a>',
			'<a href="ftp:\/\/">Test</a>' => '<a href="ftp:\/\/">Test</a>',
		];

		foreach ($weird_schemes as $input => $expected) {
			$handler = new \Elgg\Input\ValidateInputHandler();
			$result = $handler(new Hook(elgg(), null, null, $input, []));
			$this->assertEquals($expected, $result);
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
			$handler = new \Elgg\Input\ValidateInputHandler();
			$result = $handler(new Hook(elgg(), null, null, $input, []));
			$this->assertEquals($expected, $result);
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
			'z-index',
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
			$handler = new \Elgg\Input\ValidateInputHandler();
			$result = $handler(new Hook(elgg(), null, null, $input, []));
			$this->assertEquals($expected, $result);
		}
	}

	/**
	 * Test other tags and attributes
	 *
	 * @dataProvider htmlawedFilterTagsProvider
	 */
	public function testHtmlawedFilterTags($input, $expected) {
		$handler = new \Elgg\Input\ValidateInputHandler();
		$result = $handler(new Hook(elgg(), null, null, $input, []));
		
		$this->assertEquals($expected, $result);
	}
	
	public function htmlawedFilterTagsProvider() {
		$tests = [
			// duplicate closing tags, #311
			['<ul><li>item</li></ul>', '<ul><li>item</li></ul>'],
			
			// 'deny_attribute' => 'class, on*',
			['<div class="elgg-inner">Test</div>', '<div>Test</div>'],
			['<a onclick="javascript:alert(\'test\')">Test</a>', '<a>Test</a>'],
			
			// naked span stripping
			// https://github.com/vanilla/htmlawed/commit/995ef0ae4865d817a391ac62978f9d0e41d8a18b
			['<span>Test</span>', 'Test'],
			['<span id="test">Test</span>', '<span id="test">Test</span>'],
			
			// test elements filtered by "safe" option
			// http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/htmLawed_README.htm#s3.3
			['<applet>Test</applet>', 'Test'],
			['<embed>Test', 'Test'],
			['<iframe></iframe>Test', 'Test'],
			['<object>Test</object>', 'Test'],
			['<script>Test</script>', 'Test'],
			
			// some of the prevented 'elements'
			['<button>Test</button>', 'Test',],
			['<form>Test</form>', 'Test'],
			['<textarea>Test</textarea>', 'Test'],
		];
		
		return $tests;
	}
}
