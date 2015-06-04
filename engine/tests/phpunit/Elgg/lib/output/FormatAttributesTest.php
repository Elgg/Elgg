<?php
namespace Elgg\lib\output;

class FormatAttributesTest extends \PHPUnit_Framework_TestCase {

	public function testGeneralUsage() {
		$attrs = [
			'A' => 'Hello & &amp; < &lt;',
			'b' => false, // ignored
			'c' => true,
			'd' => null, // ignored
			'e' => ['&', '&amp;', '<', '&lt;'],
			'f' => (object)['foo' => 'bar'], // ignored
		];
		$expected = 'a="Hello &amp; &amp; &lt; &lt;" c="c" e="&amp; &amp; &lt; &lt;"';

		$this->assertEquals($expected, elgg_format_attributes($attrs));
	}

	public function testFiltersUnderscoreKeysExceptDataAttributes() {
		$attrs = [
			'foo_bar' => 'a',
			'data-foo_bar' => 'b',
		];
		$expected = 'data-foo_bar="b"';

		$this->assertEquals($expected, elgg_format_attributes($attrs));
	}

	public function testLowercasesAllAttributes() {
		$attrs = [
			'A-B' => true,
			'C-D' => 'C-D',
		];
		$expected = 'a-b="a-b" c-d="C-D"';

		$this->assertEquals($expected, elgg_format_attributes($attrs));
	}
}
