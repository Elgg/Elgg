<?php

namespace Elgg\Views;

use Elgg\Loggable;
use Elgg\PluginHooksService;
use Elgg\ViewsService;
use Psr\Log\LoggerInterface;

/**
 * Various helper method for formatting and sanitizing output
 */
class HtmlFormatter {

	use Loggable;

	/**
	 * @var ViewsService
	 */
	protected $views;

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;

	/**
	 * @var
	 */
	protected $autop;

	/**
	 * Output constructor.
	 *
	 * @param LoggerInterface    $logger Logger
	 * @param ViewsService       $views  Views service
	 * @param PluginHooksService $hooks  Hooks
	 * @param \ElggAutoP         $autop  Paragraph wrapper
	 */
	public function __construct(
		LoggerInterface $logger,
		ViewsService $views,
		PluginHooksService $hooks,
		\ElggAutoP $autop
	) {
		$this->logger = $logger;
		$this->views = $views;
		$this->hooks = $hooks;
		$this->autop = $autop;

	}

	/**
	 * Prepare HTML output
	 *
	 * @param string $html    HTML string
	 * @param array  $options Formatting options
	 *
	 * @option bool $parse_urls Replace URLs with anchor tags
	 * @option bool $parse_emails Replace email addresses with anchor tags
	 * @option bool $sanitize Sanitize HTML tags
	 * @option bool $autop Add paragraphs instead of new lines
	 *
	 * @return string
	 */
	public function formatBlock($html, array $options = []) {
		if (!is_string($html)) {
			return '';
		}

		$options = array_merge([
			'parse_urls' => true,
			'parse_emails' => true,
			'sanitize' => true,
			'autop' => true,
		], $options);

		$params = [
			'options' => $options,
			'html' => $html,
		];

		$params = $this->hooks->trigger('prepare', 'html', null, $params);

		$html = elgg_extract('html', $params);
		$options = elgg_extract('options', $params);

		if (elgg_extract('parse_urls', $options)) {
			$html = $this->parseUrls($html);
		}

		if (elgg_extract('parse_emails', $options)) {
			$html = $this->parseEmails($html);
		}

		if (elgg_extract('sanitize', $options)) {
			$html = filter_tags($html);
		}

		if (elgg_extract('autop', $options)) {
			$html = $this->addParagaraphs($html);
		}

		return $html;
	}

	/**
	 * Takes a string and turns any URLs into formatted links
	 *
	 * @param string $text The input string
	 *
	 * @return string The output string with formatted links
	 */
	public function parseUrls($text) {

		$linkify = new \Misd\Linkify\Linkify();

		return $linkify->processUrls($text, ['attr' => ['rel' => 'nofollow']]);
	}

	/**
	 * Takes a string and turns any email addresses into formatted links
	 *
	 * @param string $text The input string
	 *
	 * @return string The output string with formatted links
	 *
	 * @since 2.3
	 */
	public function parseEmails($text) {
		$linkify = new \Misd\Linkify\Linkify();

		return $linkify->processEmails($text, ['attr' => ['rel' => 'nofollow']]);
	}

	/**
	 * Create paragraphs from text with line spacing
	 *
	 * @param string $string The string
	 *
	 * @return string
	 **/
	public function addParagaraphs($string) {
		try {
			return $this->autop->process($string);
		} catch (\RuntimeException $e) {
			$this->logger->warning('ElggAutoP failed to process the string: ' . $e->getMessage());

			return $string;
		}
	}

	/**
	 * Converts an associative array into a string of well-formed HTML/XML attributes
	 * Returns a concatenated string of HTML attributes to be inserted into a tag (e.g., <tag $attrs>)
	 *
	 * @param array $attrs Attributes
	 *                     An array of attribute => value pairs
	 *                     Attribute value can be a scalar value, an array of scalar values, or true
	 *                     <code>
	 *                     $attrs = array(
	 *                         'class' => ['elgg-input', 'elgg-input-text'], // will be imploded with spaces
	 *                         'style' => ['margin-left:10px;', 'color: #666;'], // will be imploded with spaces
	 *                         'alt' => 'Alt text', // will be left as is
	 *                         'disabled' => true, // will be converted to disabled="disabled"
	 *                         'data-options' => json_encode(['foo' => 'bar']), // will be output as an escaped JSON string
	 *                         'batch' => <\ElggBatch>, // will be ignored
	 *                         'items' => [<\ElggObject>], // will be ignored
	 *                     );
	 *                     </code>
	 *
	 * @return string
	 *
	 * @see elgg_format_element()
	 */
	public function formatAttributes(array $attrs = []) {
		if (!is_array($attrs) || empty($attrs)) {
			return '';
		}

		$attributes = [];

		foreach ($attrs as $attr => $val) {
			if (0 !== strpos($attr, 'data-') && false !== strpos($attr, '_')) {
				// this is probably a view $vars variable not meant for output
				continue;
			}

			$attr = strtolower($attr);

			if (!isset($val) || $val === false) {
				continue;
			}

			if ($val === true) {
				$val = $attr; //e.g. checked => true ==> checked="checked"
			}

			if (is_array($val) && empty($val)) {
				//e.g. ['class' => []]
				continue;
			}
			
			if (is_scalar($val)) {
				$val = [$val];
			}

			if (!is_array($val)) {
				continue;
			}

			// Check if array contains non-scalar values and bail if so
			$filtered_val = array_filter($val, function($e) {
				return is_scalar($e);
			});

			if (count($val) != count($filtered_val)) {
				continue;
			}

			$val = implode(' ', $val);

			$val = htmlspecialchars($val, ENT_QUOTES, 'UTF-8', false);
			$attributes[] = "$attr=\"$val\"";
		}

		return implode(' ', $attributes);
	}

	/**
	 * Format an HTML element
	 *
	 * @param string|array $tag_name   The element tagName. e.g. "div". This will not be validated.
	 *                                 All function arguments can be given as a single array: The array will be used
	 *                                 as $attributes, except for the keys "#tag_name", "#text", and "#options", which
	 *                                 will be extracted as the other arguments.
	 *
	 * @param array        $attributes The element attributes. This is passed to elgg_format_attributes().
	 *
	 * @param string       $text       The contents of the element. Assumed to be HTML unless encode_text is true.
	 *
	 * @param array        $options    Options array with keys:
	 *
	 *   encode_text   => (bool, default false) If true, $text will be HTML-escaped. Already-escaped entities
	 *                    will not be double-escaped.
	 *
	 *   double_encode => (bool, default false) If true, the $text HTML escaping will be allowed to double
	 *                    encode HTML entities: '&times;' will become '&amp;times;'
	 *
	 *   is_void       => (bool) If given, this determines whether the function will return just the open tag.
	 *                    Otherwise this will be determined by the tag name according to this list:
	 *                    http://www.w3.org/html/wg/drafts/html/master/single-page.html#void-elements
	 *
	 *   is_xml        => (bool, default false) If true, void elements will be formatted like "<tag />"
	 *
	 * @return string
	 * @since 1.9.0
	 */
	public function formatElement($tag_name, array $attributes = [], $text = '', array $options = []) {
		if (is_array($tag_name)) {
			$args = $tag_name;

			if ($attributes !== [] || $text !== '' || $options !== []) {
				throw new \InvalidArgumentException('If $tag_name is an array, the other arguments must not be set');
			}

			if (isset($args['#tag_name'])) {
				$tag_name = $args['#tag_name'];
			}
			if (isset($args['#text'])) {
				$text = $args['#text'];
			}
			if (isset($args['#options'])) {
				$options = $args['#options'];
			}

			unset($args['#tag_name'], $args['#text'], $args['#options']);
			$attributes = $args;
		}

		if (!is_string($tag_name) || $tag_name === '') {
			throw new \InvalidArgumentException('$tag_name is required');
		}

		if (isset($options['is_void'])) {
			$is_void = $options['is_void'];
		} else {
			// from http://www.w3.org/TR/html-markup/syntax.html#syntax-elements
			$is_void = in_array(strtolower($tag_name), [
				'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'menuitem',
				'meta', 'param', 'source', 'track', 'wbr'
			]);
		}

		if (!empty($options['encode_text'])) {
			$double_encode = empty($options['double_encode']) ? false : true;
			$text = htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', $double_encode);
		}

		if ($attributes) {
			$attrs = $this->formatAttributes($attributes);
			if ($attrs !== '') {
				$attrs = " $attrs";
			}
		} else {
			$attrs = '';
		}

		if ($is_void) {
			return empty($options['is_xml']) ? "<{$tag_name}{$attrs}>" : "<{$tag_name}{$attrs} />";
		} else {
			return "<{$tag_name}{$attrs}>$text</$tag_name>";
		}
	}

	/**
	 * Strip tags and offer plugins the chance.
	 * Plugins register for output:strip_tags plugin hook.
	 * Original string included in $params['original_string']
	 *
	 * @param string $string         Formatted string
	 * @param string $allowable_tags Optional parameter to specify tags which should not be stripped
	 *
	 * @return string String run through strip_tags() and any plugin hooks.
	 */
	public function stripTags($string, $allowable_tags = null) {
		$params['original_string'] = $string;
		$params['allowable_tags'] = $allowable_tags;

		$string = strip_tags($string, $allowable_tags);
		$string = $this->hooks->trigger('format', 'strip_tags', $params, $string);

		return $string;
	}

	/**
	 * Decode HTML markup into a raw text string
	 *
	 * This applies html_entity_decode() to a string while re-entitising HTML
	 * special char entities to prevent them from being decoded back to their
	 * unsafe original forms.
	 *
	 * This relies on html_entity_decode() not translating entities when
	 * doing so leaves behind another entity, e.g. &amp;gt; if decoded would
	 * create &gt; which is another entity itself. This seems to escape the
	 * usual behaviour where any two paired entities creating a HTML tag are
	 * usually decoded, i.e. a lone &gt; is not decoded, but &lt;foo&gt; would
	 * be decoded to <foo> since it creates a full tag.
	 *
	 * Note: html_entity_decode() is poorly explained in the manual - which is really
	 * bad given its potential for misuse on user input already escaped elsewhere.
	 * Stackoverflow is littered with advice to use this function in the precise
	 * way that would lead to user input being capable of injecting arbitrary HTML.
	 *
	 * @param string $string Encoded HTML
	 *
	 * @return string
	 *
	 * @author Pádraic Brady
	 * @copyright Copyright (c) 2010 Pádraic Brady (http://blog.astrumfutura.com)
	 * @license Released under dual-license GPL2/MIT by explicit permission of Pádraic Brady
	 */
	public function decode($string) {
		$string = str_replace(
			['&gt;', '&lt;', '&amp;', '&quot;', '&#039;'],
			['&amp;gt;', '&amp;lt;', '&amp;amp;', '&amp;quot;', '&amp;#039;'],
			$string
		);
		$string = html_entity_decode($string, ENT_NOQUOTES, 'UTF-8');
		$string = str_replace(
			['&amp;gt;', '&amp;lt;', '&amp;amp;', '&amp;quot;', '&amp;#039;'],
			['&gt;', '&lt;', '&amp;', '&quot;', '&#039;'],
			$string
		);
		return $string;
	}
}