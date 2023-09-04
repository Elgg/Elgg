<?php

namespace Elgg\Views;

use Elgg\EventsService;
use Elgg\Exceptions\Configuration\RegistrationException;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Traits\Loggable;
use Elgg\ViewsService;
use Pelago\Emogrifier\CssInliner;
use Pelago\Emogrifier\HtmlProcessor\CssToAttributeConverter;

/**
 * Various helper method for formatting and sanitizing output
 */
class HtmlFormatter {

	use Loggable;
	
	/**
	 * Mentions regex
	 *
	 * Match anchor tag with all attributes and wrapped html
	 * we want to exclude matches that have already been wrapped in an anchor
	 * '<a[^>]*?>.*?<\/a>'
	 *
	 * Match tag name and attributes
	 * we want to exclude matches that found within tag attributes
	 * '<.*?>'
	 *
	 * Match at least one space or punctuation char before a match
	 * '(^|\s|\!|\.|\?|>|\G)+'
	 *
	 * Match @ followed by username
	 * @see \Elgg\Users\Accounts::assertValidUsername()
	 * '(@([^\s<&]+))'
	 */
	public const MENTION_REGEX = '/<a[^>]*?>.*?<\/a>|<.*?>|(^|\s|\!|\.|\?|>|\G)+(@([^\s<&]+))/iu';

	protected ViewsService $views;

	protected EventsService $events;
	
	protected AutoParagraph $autop;

	/**
	 * Output constructor.
	 *
	 * @param ViewsService  $views  Views service
	 * @param EventsService $events Events service
	 * @param AutoParagraph $autop  Paragraph wrapper
	 */
	public function __construct(
		ViewsService $views,
		EventsService $events,
		AutoParagraph $autop
	) {
		$this->views = $views;
		$this->events = $events;
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
	public function formatBlock(string $html, array $options = []): string {
		$options = array_merge([
			'parse_urls' => true,
			'parse_emails' => true,
			'parse_mentions' => true,
			'sanitize' => true,
			'autop' => true,
		], $options);

		$params = [
			'options' => $options,
			'html' => $html,
		];

		$params = $this->events->triggerResults('prepare', 'html', [], $params);

		$html = (string) elgg_extract('html', $params);
		$options = (array) elgg_extract('options', $params);

		if (elgg_extract('parse_urls', $options)) {
			$html = $this->parseUrls($html);
		}

		if (elgg_extract('parse_emails', $options)) {
			$html = $this->parseEmails($html);
		}
		
		if (elgg_extract('parse_mentions', $options)) {
			$html = $this->parseMentions($html);
		}

		if (elgg_extract('sanitize', $options)) {
			$html = elgg_sanitize_input($html);
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
	public function parseUrls(string $text): string {

		$linkify = new \Misd\Linkify\Linkify();

		return $linkify->processUrls($text, ['attr' => ['rel' => 'nofollow']]);
	}

	/**
	 * Takes a string and turns any email addresses into formatted links
	 *
	 * @param string $text The input string
	 *
	 * @return string The output string with formatted links
	 * @since 2.3
	 */
	public function parseEmails(string $text): string {
		$linkify = new \Misd\Linkify\Linkify();

		return $linkify->processEmails($text, ['attr' => ['rel' => 'nofollow']]);
	}
	
	/**
	 * Takes a string and turns any @ mentions into a formatted link
	 *
	 * @param string $text The input string
	 *
	 * @return string
	 * @since 5.0
	 */
	public function parseMentions(string $text): string {
		$callback = function (array $matches) {
			$source = elgg_extract(0, $matches);
			$preceding_char = elgg_extract(1, $matches);
			$username = elgg_extract(3, $matches);
			
			if (empty($username)) {
				return $source;
			}
			
			try {
				_elgg_services()->accounts->assertValidUsername($username);
			} catch (RegistrationException $e) {
				return $source;
			}
			
			$user = elgg_get_user_by_username($username);
			
			// Catch the trailing period when used as punctuation and not a username.
			$period = '';
			if (!$user && str_ends_with($username, '.')) {
				$user = elgg_get_user_by_username(substr($username, 0, -1));
				$period = '.';
			}
			
			if (!$user) {
				return $source;
			}
			
			if (elgg_get_config('mentions_display_format') === 'username') {
				$replacement = elgg_view_url($user->getURL(), "@{$user->username}");
			} else {
				$replacement = elgg_view_url($user->getURL(), $user->getDisplayName());
			}
			
			return $preceding_char . $replacement . $period;
		};
		
		return preg_replace_callback(self::MENTION_REGEX, $callback, $text) ?? $text;
	}

	/**
	 * Create paragraphs from text with line spacing
	 *
	 * @param string $string The string
	 *
	 * @return string
	 **/
	public function addParagaraphs(string $string): string {
		try {
			$result = $this->autop->process($string);
			if ($result !== false) {
				return $result;
			}
		} catch (\RuntimeException $e) {
			$this->getLogger()->warning('AutoParagraph failed to process the string: ' . $e->getMessage());
		}
		
		return $string;
	}

	/**
	 * Converts an associative array into a string of well-formed HTML/XML attributes
	 * Returns a concatenated string of HTML attributes to be inserted into a tag (e.g., <tag $attrs>)
	 *
	 * An example of the attributes:
	 * Attribute value can be a scalar value, an array of scalar values, or true
	 * <code>
	 *     $attrs = [
	 *         'class' => ['elgg-input', 'elgg-input-text'], // will be imploded with spaces
	 *         'style' => ['margin-left:10px;', 'color: #666;'], // will be imploded with spaces
	 *         'alt' => 'Alt text', // will be left as is
	 *         'disabled' => true, // will be converted to disabled="disabled"
	 *         'data-options' => json_encode(['foo' => 'bar']), // will be output as an escaped JSON string
	 *         'batch' => <\ElggBatch>, // will be ignored
	 *         'items' => [<\ElggObject>], // will be ignored
	 *     ];
	 * </code>
	 *
	 * @param array $attrs An array of attribute => value pairs
	 *
	 * @return string
	 *
	 * @see elgg_format_element()
	 */
	public function formatAttributes(array $attrs = []): string {
		if (empty($attrs)) {
			return '';
		}

		$attributes = [];

		foreach ($attrs as $attr => $val) {
			if (!str_starts_with($attr, 'data-') && str_contains($attr, '_')) {
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
	 * @param string $tag_name   The element tagName. e.g. "div". This will not be validated.
	 *
	 * @param array  $attributes The element attributes.
	 *
	 * @param string $text       The contents of the element. Assumed to be HTML unless encode_text is true.
	 *
	 * @param array  $options    Options array with keys:
	 *
	 *                           - encode_text   => (bool, default false) If true, $text will be HTML-escaped. Already-escaped entities
	 *                           will not be double-escaped.
	 *
	 *                           - double_encode => (bool, default false) If true, the $text HTML escaping will be allowed to double
	 *                           encode HTML entities: '&times;' will become '&amp;times;'
	 *
	 *                           - is_void       => (bool) If given, this determines whether the function will return just the open tag.
	 *                           Otherwise this will be determined by the tag name according to this list:
	 *                           http://www.w3.org/html/wg/drafts/html/master/single-page.html#void-elements
	 *
	 *                           - is_xml        => (bool, default false) If true, void elements will be formatted like "<tag />"
	 *
	 * @return string
	 * @since 1.9.0
	 * @throws InvalidArgumentException
	 */
	public function formatElement(string $tag_name, array $attributes = [], string $text = '', array $options = []): string {
		if ($tag_name === '') {
			throw new InvalidArgumentException('$tag_name is required');
		}
		
		// from http://www.w3.org/TR/html-markup/syntax.html#syntax-elements
		$is_void = $options['is_void'] ?? in_array(strtolower($tag_name), [
			'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'menuitem',
			'meta', 'param', 'source', 'track', 'wbr'
		]);

		if (!empty($options['encode_text']) && is_string($text)) {
			$double_encode = !empty($options['double_encode']);
			$text = htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', $double_encode);
		}

		$attrs = '';
		if (!empty($attributes)) {
			$attrs = $this->formatAttributes($attributes);
			if ($attrs !== '') {
				$attrs = " $attrs";
			}
		}

		if ($is_void) {
			return empty($options['is_xml']) ? "<{$tag_name}{$attrs}>" : "<{$tag_name}{$attrs} />";
		}
		
		return "<{$tag_name}{$attrs}>$text</$tag_name>";
	}

	/**
	 * Strip tags and offer plugins the chance.
	 * Plugins register for output:strip_tags event.
	 * Original string included in $params['original_string']
	 *
	 * @param string $string         Formatted string
	 * @param string $allowable_tags Optional parameter to specify tags which should not be stripped
	 *
	 * @return string String run through strip_tags() and any event.
	 */
	public function stripTags(string $string, string $allowable_tags = null): string {
		$params = [
			'original_string' => $string,
			'allowable_tags' => $allowable_tags,
		];

		$string = strip_tags($string, $allowable_tags);
		return (string) $this->events->triggerResults('format', 'strip_tags', $params, $string);
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
	public function decode(string $string): string {
		$string = str_replace(
			['&gt;', '&lt;', '&amp;', '&quot;', '&#039;'],
			['&amp;gt;', '&amp;lt;', '&amp;amp;', '&amp;quot;', '&amp;#039;'],
			$string
		);
		$string = html_entity_decode($string, ENT_NOQUOTES, 'UTF-8');
		return str_replace(
			['&amp;gt;', '&amp;lt;', '&amp;amp;', '&amp;quot;', '&amp;#039;'],
			['&gt;', '&lt;', '&amp;', '&quot;', '&#039;'],
			$string
		);
	}
	
	/**
	 * Adds inline style to html content
	 *
	 * @param string $html      html content
	 * @param string $css       style text
	 * @param bool   $body_only toggle to return the body contents instead of a full html
	 *
	 * @return string
	 *
	 * @since 4.0
	 */
	public function inlineCss(string $html, string $css, bool $body_only = false): string {
		if (empty($html) || empty($css)) {
			return $html;
		}
		
		$html_with_inlined_css = CssInliner::fromHtml($html)->disableStyleBlocksParsing()->inlineCss($css)->render();
		$inlined_attribute_converter = CssToAttributeConverter::fromHtml($html_with_inlined_css)->convertCssToVisualAttributes();
		
		return $body_only ? $inlined_attribute_converter->renderBodyContent() : $inlined_attribute_converter->render();
	}
	
	/**
	 * Replaces relative urls in href or src attributes in text
	 *
	 * @param string $text source content
	 *
	 * @return string
	 *
	 * @since 4.0
	 */
	public function normalizeUrls(string $text): string {
		$pattern = '/\s(?:href|src)=([\'"]\S+[\'"])/i';
		
		// find all matches
		$matches = [];
		preg_match_all($pattern, $text, $matches);
		
		if (empty($matches) || !isset($matches[1])) {
			return $text;
		}
		
		// go through all the matches
		$urls = $matches[1];
		$urls = array_unique($urls);
		
		foreach ($urls as $url) {
			// remove wrapping quotes from the url
			$real_url = substr($url, 1, -1);
			// normalize url
			$new_url = elgg_normalize_url($real_url);
			// make the correct replacement string
			$replacement = str_replace($real_url, $new_url, $url);
			
			// replace the url in the content
			$text = str_replace($url, $replacement, $text);
		}
		
		return $text;
	}
}
