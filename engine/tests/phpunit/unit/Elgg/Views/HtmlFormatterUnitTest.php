<?php

namespace Elgg\Views;

use Elgg\Exceptions\InvalidArgumentException;
use Elgg\UnitTestCase;

class HtmlFormatterUnitTest extends UnitTestCase {

	protected ?HtmlFormatter $htmlFormatter;
	
	public function up() {
		elgg_register_event_handler('sanitize', 'input', \Elgg\Input\ValidateInputHandler::class);
		
		$this->htmlFormatter = _elgg_services()->html_formatter;
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

	public static function htmlBlockProvider() {

		$attrs = _elgg_services()->html_formatter->formatAttributes([
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

		$event = $this->registerTestingEvent('prepare', 'html', function(\Elgg\Event $event) use ($html) {
			$value = $event->getValue();

			$this->assertEquals([
				'parse_urls' => true,
				'parse_emails' => true,
				'parse_mentions' => true,
				'autop' => true,
				'sanitize' => true,
			], $value['options']);

			$this->assertEquals($html, $value['html']);

			$value['html'] = 'Hello, world!!!';

			return $value;
		});

		$actual = elgg_format_html($html);

		$event->assertNumberOfCalls(1);

		$this->assertXmlStringEqualsXmlString('<p>Hello, world!!!</p>', $actual);

		$event->unregister();
	}

	public function testCanFilterHtmlBlockFormattingOptions() {
		$html = 'Hello, http://world <script></script>';

		$event = $this->registerTestingEvent('prepare', 'html', function(\Elgg\Event $event) use ($html) {
			$value = $event->getValue();

			$this->assertEquals([
				'parse_urls' => true,
				'parse_emails' => true,
				'parse_mentions' => true,
				'autop' => true,
				'sanitize' => true,
			], $value['options']);

			$this->assertEquals($html, $value['html']);

			$value['options'] = [];

			return $value;
		});

		$actual = elgg_format_html($html);

		$event->assertNumberOfCalls(1);

		$this->assertEquals('Hello, http://world <script></script>', $actual);

		$event->unregister();
	}

	/**
	 * @dataProvider providerElggFormatElement
	 */
	public function testElggFormatElement($expected, $vars) {
		$tag_name = $vars['tag_name'];
		$text = $vars['text'] ?? '';
		$opts = $vars['opts'] ?? [];
		$attrs = $vars['attrs'] ?? [];
		$message = $vars['_msg'] ?? null;
		unset($vars['tag_name'], $vars['text'], $vars['_msg']);

		$this->assertSame($expected, elgg_format_element($tag_name, $attrs, $text, $opts), $message);
	}

	public function testElggFormatElementThrows() {
		$this->expectException(InvalidArgumentException::class);
		elgg_format_element('');
	}

	public static function providerElggFormatElement() {
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

	public static function providerElggGetFriendlyTime() {
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

		$this->assertEquals($expected, $this->htmlFormatter->formatAttributes($attrs));
	}

	public function testFiltersUnderscoreKeysExceptDataAttributes() {
		$attrs = [
			'foo_bar' => 'a',
			'data-foo_bar' => 'b',
		];
		$expected = 'data-foo_bar="b"';

		$this->assertEquals($expected, $this->htmlFormatter->formatAttributes($attrs));
	}

	public function testLowercasesAllAttributes() {
		$attrs = [
			'A-B' => true,
			'C-D' => 'C-D',
		];
		$expected = 'a-b="a-b" c-d="C-D"';

		$this->assertEquals($expected, $this->htmlFormatter->formatAttributes($attrs));
	}
	
	public function testInlineCss() {
		$html = '<p>test</p>';
		$css = 'p { color: red; }';
		$expected = '<p style="color: red;">test</p>';
		
		$this->assertEquals($expected, $this->htmlFormatter->inlineCss($html, $css, true));
	}
	
	public function testNormalizeUrls() {
		$text = 'foo <a href="/blog">link</a> /bar.php <img src="/link_to_image.jpg"/>';
		$expected = 'foo <a href="' . elgg_get_site_url() . 'blog">link</a> /bar.php <img src="' . elgg_get_site_url() . 'link_to_image.jpg"/>';
		
		$this->assertEquals($expected, $this->htmlFormatter->normalizeUrls($text));
	}
	
	/**
	 * @dataProvider stripTagsProvider
	 */
	public function testStripTags($input, $expected_output, $allowed_tags) {
		$event = $this->registerTestingEvent('format', 'strip_tags', function (\Elgg\Event $event) {
		});
		
		$result = $this->htmlFormatter->stripTags($input, $allowed_tags);
		
		$event->assertNumberOfCalls(1);
		
		$event->assertParamBefore('original_string', $input);
		$event->assertParamBefore('allowable_tags', $allowed_tags);
		
		$event->assertParamAfter('original_string', $input);
		$event->assertParamAfter('allowable_tags', $allowed_tags);
		
		$this->assertEquals($expected_output, $result);
	}
	
	public static function stripTagsProvider() {
		return [
			['This is a test.', 'This is a test.', null],
			[' This is a test. ', ' This is a test. ', null],
			[' This is a  test. ', ' This is a  test. ', null],
			['<p>This is a test.</p>', 'This is a test.', null],
			['This is a<br/>test.', 'This is a test.', null],
			['This is a<br />test.', 'This is a test.', null],
			['This is a<hr/>test.', 'This is a test.', null],
			['This is a<hr />test.', 'This is a test.', null],
			['.<p>This is a test.</p> ', '. This is a test. ', null],
			[' <p>This is a test.</p> ', ' This is a test. ', null],
			[' <p>This is a   test.</p> ', ' This is a   test. ', null],
			[' <p>This is a  <p></p> test.</p> ', ' This is a   test. ', null],
			['This is a <div>test.</div>', 'This is a test.', null],
			['This is a<div>test.</div>', 'This is a test.', null],
			['This is a<b>test.</b>', 'This is atest.', null],
			['<p>This is a test.', 'This is a test.', null],
			['This is a test.</p>', 'This is a test.', null],
			['<p>This is a test.</p>', '<p>This is a test.</p>', '<p>'],
			['<foo>This is a test.</foo>', 'This is a test.', null],
			['<foo>This is a test.</foo>', '<foo>This is a test.</foo>', '<foo>'],
			['aaaaaaaaa<p></p><p></p><p></p><p></p><p></p><p></p>b', 'aaaaaaaaa b', null],
			['<p>This is a test.</p><p>This is a test.</p>', 'This is a test. This is a test.', null],
			[str_repeat("<p>This is a test.</p>\n", 10), str_repeat("This is a test.\n", 10), null],
			['<div>This is a test.</div><p>This is a test.</p>', 'This is a test. This is a test.', null],
			["<div>This is a test.</div>\n<p>This is a test.</p>", "This is a test.\nThis is a test.", null],
			['<div>This is a test.</div><div>This is a test.</div>', 'This is a test. This is a test.', null],
			["<div>This is a test.</div>\n<div>This is a test.</div>", "This is a test.\nThis is a test.", null],
			['<p>This is a <a href="javascript:void(0);">test</a>.</p>', 'This is a test.', null],
			['<p>This is a<a href="javascript:void(0);">test</a>.</p>', 'This is atest.', null],
			['<p>This is a <a href="javascript:void(0);">test</a>.</p>', '<p>This is a test.</p>', '<p>'],
			['<p>This is a <a href="javascript:void(0);">test</a>.</p>', 'This is a <a href="javascript:void(0);">test</a>.', '<a>'],
		];
	}
}
