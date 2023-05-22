<?php
/**
 * Output functions
 * Processing text for output such as pulling out URLs and extracting excerpts
 */

/**
 * Prepare HTML output
 *
 * @param string $html    HTML string
 * @param array  $options Formatting options
 *
 * @option bool $parse_urls   Replace URLs with anchor tags
 * @option bool $parse_emails Replace email addresses with anchor tags
 * @option bool $sanitize     Sanitize HTML tags
 * @option bool $autop        Add paragraphs instead of new lines
 *
 * @return string
 */
function elgg_format_html(string $html, array $options = []): string {
	return _elgg_services()->html_formatter->formatBlock($html, $options);
}

/**
 * Takes a string and turns any URLs into formatted links
 *
 * @param string $text The input string
 *
 * @return string The output string with formatted links
 * @since 4.3
 */
function elgg_parse_urls(string $text): string {
	return _elgg_services()->html_formatter->parseUrls($text);
}

/**
 * Takes a string and turns any email addresses into formatted links
 *
 * @param string $text The input string
 *
 * @return string The output string with formatted links
 * @since 2.3
 */
function elgg_parse_emails(string $text): string {
	return _elgg_services()->html_formatter->parseEmails($text);
}

/**
 * Takes a string and turns any @ mentions into formatted links
 *
 * @param string $text The input string
 *
 * @return string The output string with formatted links
 * @since 5.0
 */
function elgg_parse_mentions(string $text): string {
	return _elgg_services()->html_formatter->parseMentions($text);
}

/**
 * Create paragraphs from text with line spacing
 *
 * @param string $string The string
 *
 * @return string
 **/
function elgg_autop(string $string): string {
	return _elgg_services()->html_formatter->addParagaraphs($string);
}

/**
 * Returns an excerpt.
 * Will return up to n chars stopping at the nearest space.
 * If no spaces are found (like in Japanese) will crop off at the
 * n char mark. Adds ... if any text was chopped.
 *
 * @param string $text      The full text to excerpt
 * @param int    $num_chars Return a string up to $num_chars long
 *
 * @return string
 * @since 1.7.2
 */
function elgg_get_excerpt(string $text, int $num_chars = 250): string {
	$view = 'output/excerpt';
	$vars = [
		'text' => $text,
		'num_chars' => $num_chars,
	];
	$viewtype = elgg_view_exists($view) ? '' : 'default';

	return _elgg_view_under_viewtype($view, $vars, $viewtype);
}

/**
 * Format bytes to a human readable format
 *
 * @param int $size      File size in bytes to format
 * @param int $precision Precision to round formatting bytes to
 *
 * @return string
 * @since 1.9.0
 */
function elgg_format_bytes(int $size, int $precision = 2): string {
	if ($size < 0) {
		return (string) $size;
	}
	
	if ($size === 0) {
		return '0 B';
	}
	
	if ($precision < 0) {
		$precision = 2;
	}

	$base = log($size) / log(1024);
	$suffixes = ['B', 'kB', 'MB', 'GB', 'TB'];

	return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
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
 *                           - encode_text   => (bool, default false) If true, $text will be HTML-escaped. Already-escaped entities will not be double-escaped.
 *                           - double_encode => (bool, default false) If true, the $text HTML escaping will be allowed to double encode HTML entities: '&times;' will become '&amp;times;'
 *
 *                           - is_void       => (bool) If given, this determines whether the function will return just the open tag.
 *                           Otherwise this will be determined by the tag name according to this list:
 *                           http://www.w3.org/html/wg/drafts/html/master/single-page.html#void-elements
 *
 *                           - is_xml        => (bool, default false) If true, void elements will be formatted like "<tag />"
 *
 * @return string
 * @throws \Elgg\Exceptions\InvalidArgumentException
 * @since 1.9.0
 */
function elgg_format_element(string $tag_name, array $attributes = [], string $text = '', array $options = []): string {
	return _elgg_services()->html_formatter->formatElement($tag_name, $attributes, $text, $options);
}

/**
 * Converts shorthand URLs to absolute URLs, unless the given URL is absolute, protocol-relative,
 * or starts with a protocol/fragment/query
 *
 * @example
 * elgg_normalize_url('');                   // 'http://my.site.com/'
 * elgg_normalize_url('dashboard');          // 'http://my.site.com/dashboard'
 * elgg_normalize_url('http://google.com/'); // no change
 * elgg_normalize_url('//google.com/');      // no change
 *
 * @param string $url The URL to normalize
 *
 * @return string The absolute URL
 */
function elgg_normalize_url(string $url): string {
	return _elgg_services()->urls->normalizeUrl($url);
}

/**
 * From untrusted input, get a site URL safe for forwarding.
 *
 * @param string $unsafe_url URL from untrusted input
 *
 * @return null|string Normalized URL or null if given URL was not a path.
 * @since 1.12.18
 */
function elgg_normalize_site_url(string $unsafe_url): ?string {
	$unsafe_url = _elgg_services()->urls->normalizeUrl($unsafe_url);
	if (elgg_strpos($unsafe_url, elgg_get_site_url()) === 0) {
		return $unsafe_url;
	}

	return null;
}

/**
 * When given a title, returns a version suitable for inclusion in a URL
 *
 * @param string $title The title
 *
 * @return string The optimized title
 * @since 1.7.2
 */
function elgg_get_friendly_title(string $title): string {
	// return a URL friendly title to short circuit normal title formatting
	$params = ['title' => $title];
	$result = elgg_trigger_event_results('format', 'friendly:title', $params, null);
	if (is_string($result)) {
		return $result;
	}

	// titles are often stored HTML encoded
	$title = html_entity_decode($title ?? '', ENT_QUOTES, 'UTF-8');
	
	$title = \Elgg\Translit::urlize($title);

	// limit length to prevent issues with too long URLS (Request-URI Too Large)
	return elgg_substr($title, 0, 100);
}

/**
 * Formats a UNIX timestamp in a friendly way (eg "less than a minute ago")
 *
 * @see elgg_view_friendly_time()
 *
 * @param int $time         A UNIX epoch timestamp
 * @param int $current_time Current UNIX epoch timestamp (optional)
 *
 * @return string The friendly time string
 * @since 1.7.2
 */
function elgg_get_friendly_time(int $time, int $current_time = null): string {

	if (!isset($current_time)) {
		$current_time = time();
	}

	// return a time string to short circuit normal time formatting
	$params = ['time' => $time, 'current_time' => $current_time];
	$result = elgg_trigger_event_results('format', 'friendly:time', $params, null);
	if (is_string($result)) {
		return $result;
	}

	$diff = abs($current_time - $time);

	$minute = 60;
	$hour = $minute * 60;
	$day = $hour * 24;

	if ($diff < $minute) {
		return elgg_echo('friendlytime:justnow');
	}
	
	if ($diff < $hour) {
		$granularity = ':minutes';
		$diff = round($diff / $minute);
	} else if ($diff < $day) {
		$granularity = ':hours';
		$diff = round($diff / $hour);
	} else {
		$granularity = ':days';
		$diff = round($diff / $day);
	}

	if ($diff == 0) {
		$diff = 1;
	}
	
	$future = ($current_time - $time < 0) ? ':future' : '';
	$singular = ($diff == 1) ? ':singular' : '';

	return elgg_echo("friendlytime{$future}{$granularity}{$singular}", [$diff]);
}

/**
 * Returns a human-readable message for PHP's upload error codes
 *
 * @param int $error_code The code as stored in $_FILES['name']['error']
 *
 * @return string
 */
function elgg_get_friendly_upload_error(int $error_code): string {
	switch ($error_code) {
		case UPLOAD_ERR_OK:
			return '';
			
		case UPLOAD_ERR_INI_SIZE:
			$key = 'ini_size';
			break;
		
		case UPLOAD_ERR_FORM_SIZE:
			$key = 'form_size';
			break;

		case UPLOAD_ERR_PARTIAL:
			$key = 'partial';
			break;

		case UPLOAD_ERR_NO_FILE:
			$key = 'no_file';
			break;

		case UPLOAD_ERR_NO_TMP_DIR:
			$key = 'no_tmp_dir';
			break;

		case UPLOAD_ERR_CANT_WRITE:
			$key = 'cant_write';
			break;

		case UPLOAD_ERR_EXTENSION:
			$key = 'extension';
			break;
		
		default:
			$key = 'unknown';
			break;
	}

	return elgg_echo("upload:error:{$key}");
}

/**
 * Strip tags and offer plugins the chance.
 * Plugins register for output:strip_tags event.
 * Original string included in $params['original_string']
 *
 * @param string $string         Formatted string
 * @param string $allowable_tags Optional parameter to specify tags which should not be stripped
 *
 * @return string String run through strip_tags() and any events.
 */
function elgg_strip_tags(string $string, string $allowable_tags = null): string {
	return _elgg_services()->html_formatter->stripTags($string, $allowable_tags);
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
function elgg_html_decode(string $string): string {
	return _elgg_services()->html_formatter->decode($string);
}

/**
 * Prepares query string for output to prevent CSRF attacks.
 *
 * @param string $string string to prepare
 *
 * @return string
 * @internal
 */
function _elgg_get_display_query(string $string): string {
	if (empty($string)) {
		return $string;
	}
	
	$string = htmlentities($string,  ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, 'UTF-8');
	
	return htmlspecialchars($string, ENT_QUOTES, 'UTF-8', false);
}
