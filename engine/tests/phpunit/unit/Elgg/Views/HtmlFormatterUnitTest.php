<?php

namespace Elgg\Views;
use Elgg\Hook;
use Elgg\UnitTestCase;

/**
 * @group HTML
 * @group Views
 * @group Output
 */
class HtmlFormatterUnitTest extends UnitTestCase {

	public function up() {
		_elgg_input_init();
	}

	public function down() {

	}

	/**
	 * @dataProvider htmlBlockProvider
	 */
	public function testCanFormatHTMLBlock($before, $after) {

		$actual = elgg_format_html($before);

		$this->assertXmlStringEqualsXmlString($after, $actual);

		// Second run mustn't make any changes
		$actual = elgg_format_html($actual);

		$this->assertXmlStringEqualsXmlString($after, $actual);
	}

	public function htmlBlockProvider() {

		$attrs = elgg_format_attributes([
			'foo' => 'http://example.com',
			'bar' => 'me@example.com',
			'href' => 'http://example.com',
			'data-x' => json_encode([
				'email' => 'me@example.com',
				'href' => 'http://example.com',
			]),
		]);

		return [
			[
				'before' => 'Linkify http://example.com and me@example.com',
				'after' => '<p>Linkify <a href="http://example.com" rel="nofollow">http://example.com</a> and <a href="mailto:me@example.com" rel="nofollow">me@example.com</a></p>',
			],

			[
				'before' => 'Linkify <a foo="bar">http://example.com</a> and <a ' . $attrs . '>hello</a> and me@example.com and <b ' . $attrs . '>http://example.com</b>',
				'after' => '<p>Linkify <a>http://example.com</a> and <a rel="nofollow" href="http://example.com" data-x="{&quot;email&quot;:&quot;me@example.com&quot;,&quot;href&quot;:&quot;http:\/\/example.com&quot;}">hello</a> and <a href="mailto:me@example.com" rel="nofollow">me@example.com</a> and <b data-x="{&quot;email&quot;:&quot;me@example.com&quot;,&quot;href&quot;:&quot;http:\/\/example.com&quot;}"><a href="http://example.com" rel="nofollow">http://example.com</a></b></p>',
			],
			[
				'before' => "a\r\nb\rc\nd",
				'after' => '<p>a<br />b<br />c<br />d</p>',
			],
			[
				'before' => "a\r\n<a \r\n href=http://example.com>c\n\d</a> \t\r",
				'after' => '<p>a<br /><a rel="nofollow" href="http://example.com">c<br />\d</a></p>',
			],
			[
				'before' => elgg_http_add_url_query_elements('http://example.com', [
					'email' => 'me@example.com',
				]),
				'after' => '<p><a href="http://example.com?email=me%40example.com" rel="nofollow">http://example.com?email=me%40example.com</a></p>',
			],
			[
				'before' => 'http://example.com?a=\<script\>',
				'after' => '<p><a href="http://example.com?a=%5C" rel="nofollow">http://example.com?a=\</a></p>',
			],
		];
	}

	public function testCanFilterHtmlBlock() {
		$html = 'Hello, http://world';

		$hook = $this->registerTestingHook('prepare', 'html', function(Hook $hook) use ($html) {
			$value = $hook->getValue();

			$this->assertEquals([
				'parse_urls' => true,
				'parse_emails' => true,
				'autop' => true,
				'sanitize' => true,
			], $value['options']);

			$this->assertEquals($html, $value['html']);

			$value['html'] = 'Hello, world!!!';

			return $value;
		});

		$actual = elgg_format_html($html);

		$hook->assertNumberOfCalls(1);

		$this->assertXmlStringEqualsXmlString('<p>Hello, world!!!</p>', $actual);

		$hook->unregister();
	}

	public function testCanFilterHtmlBlockFormattingOptions() {
		$html = 'Hello, http://world <script></script>';

		$hook = $this->registerTestingHook('prepare', 'html', function(Hook $hook) use ($html) {
			$value = $hook->getValue();

			$this->assertEquals([
				'parse_urls' => true,
				'parse_emails' => true,
				'autop' => true,
				'sanitize' => true,
			], $value['options']);

			$this->assertEquals($html, $value['html']);

			$value['options'] = [];

			return $value;
		});

		$actual = elgg_format_html($html);

		$hook->assertNumberOfCalls(1);

		$this->assertEquals('Hello, http://world <script></script>', $actual);

		$hook->unregister();
	}

	/**
	 * @dataProvider providerElggFormatElement
	 */
	public function testElggFormatElement($expected, $vars) {
		$tag_name = $vars['tag_name'];
		$text = isset($vars['text']) ? $vars['text'] : null;
		$opts = isset($vars['opts']) ? $vars['opts'] : array();
		$attrs = isset($vars['attrs']) ? $vars['attrs'] : array();
		$message = isset($vars['_msg']) ? $vars['_msg'] : null;
		unset($vars['tag_name'], $vars['text'], $vars['_msg']);

		$this->assertSame($expected, elgg_format_element($tag_name, $attrs, $text, $opts), $message);

		$attrs['#tag_name'] = $tag_name;
		$attrs['#text'] = $text;
		$attrs['#options'] = $opts;
		$this->assertSame($expected, elgg_format_element($attrs), $message);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testElggFormatElementThrows() {
		elgg_format_element(array());
	}

	function providerElggFormatElement() {
		$data = [
			'<span>a & b</span>' => array(
				'tag_name' => 'span',
				'text' => 'a & b',
				'_msg' => 'Basic formatting, span recognized as non-void element',
			),
			'<span>a &amp; &amp; b</span>' => array(
				'tag_name' => 'span',
				'text' => 'a & &amp; b',
				'opts' => array('encode_text' => true),
				'_msg' => 'HTML escaping, does not double encode',
			),
			'<span>a &amp;times; b</span>' => array(
				'tag_name' => 'span',
				'text' => 'a &times; b',
				'opts' => array('encode_text' => true, 'double_encode' => true),
				'_msg' => 'HTML escaping double encodes',
			),
			'<div id="foo">no empty arrays in output</div>' => array(
				'tag_name' => 'div',
				'text' => 'no empty arrays in output',
				'attrs' => array('id' => 'foo', 'class' => []),
				'_msg' => 'No empty arrays outputted as values',
			),
			'<IMG src="a &amp; b">' => array(
				'tag_name' => 'IMG',
				'attrs' => array('src' => 'a & b'),
				'text' => 'should not appear',
				'_msg' => 'IMG recognized as void element, text ignored',
			),
			'<foo />' => array(
				'tag_name' => 'foo',
				'opts' => array('is_void' => true, 'is_xml' => true),
				'_msg' => 'XML syntax for self-closing elements',
			),
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
		self::createApplication();

		return [
			['0', elgg_echo('friendlytime:justnow')],
			['-120', elgg_echo('friendlytime:minutes', array('2'))],
			['-60', elgg_echo('friendlytime:minutes:singular')],
			['-10800', elgg_echo('friendlytime:hours', array('3'))],
			['-86400', elgg_echo('friendlytime:days:singular')],
			['120', elgg_echo('friendlytime:future:minutes', array('2'))],
			['86400', elgg_echo('friendlytime:future:days:singular')],
		];
	}

	public function testGeneralUsage() {
		$attrs = [
			'A' => 'Hello & &amp; < &lt;',
			'b' => false, // ignored
			'c' => true,
			'd' => null, // ignored
			'e' => ['&', '&amp;', '<', '&lt;'],
			'f' => (object) ['foo' => 'bar'], // ignored
			'g' => [
				'bar',
				true,
				1.5,
				2
			],
			'h' => [
				'foo',
				[],
			],
			'i' => [
				new \ElggObject(),
			],
		];
		$expected = 'a="Hello &amp; &amp; &lt; &lt;" c="c" e="&amp; &amp; &lt; &lt;" g="bar 1 1.5 2"';

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
