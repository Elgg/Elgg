<?php
/**
 * Output functions
 * Processing text for output such as pulling out URLs and extracting excerpts
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * Takes a string and turns any URLs into formatted links
 *
 * @param string $text The input string
 *
 * @return string The output string with formatted links
 */
function parse_urls($text) {

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
function elgg_parse_emails($text) {
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
function elgg_autop($string) {
	return _elgg_services()->autoP->process($string);
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
function elgg_get_excerpt($text, $num_chars = 250) {
	$view = 'output/excerpt';
	$vars = [
		'text' => $text,
		'num_chars' => $num_chars,
	];
	$viewtype = elgg_view_exists($view) ? '' : 'default';

	return _elgg_view_under_viewtype($view, $vars, $viewtype);
}

/**
 * Handles formatting of ampersands in urls
 *
 * @param string $url The URL
 *
 * @return string
 * @since 1.7.1
 */
function elgg_format_url($url) {
	return preg_replace('/&(?!amp;)/', '&amp;', $url);
}

/**
 * Format bytes to a human readable format
 *
 * @param int $size      File size in bytes to format
 *
 * @param int $precision Precision to round formatting bytes to
 *
 * @return string
 * @since 1.9.0
 */
function elgg_format_bytes($size, $precision = 2) {
	if (!$size || $size < 0) {
		return false;
	}

	$base = log($size) / log(1024);
	$suffixes = array('B', 'kB', 'MB', 'GB', 'TB');

	return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}

/**
 * Converts an associative array into a string of well-formed HTML/XML attributes
 * Returns a concatenated string of HTML attributes to be inserted into a tag (e.g., <tag $attrs>)
 *
 * @see elgg_format_element
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
 */
function elgg_format_attributes(array $attrs = array()) {
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
 * @throws InvalidArgumentException
 * @since 1.9.0
 */
function elgg_format_element($tag_name, array $attributes = array(), $text = '', array $options = array()) {
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
		$is_void = in_array(strtolower($tag_name), array(
			'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'menuitem',
			'meta', 'param', 'source', 'track', 'wbr'
		));
	}

	if (!empty($options['encode_text'])) {
		$double_encode = empty($options['double_encode']) ? false : true;
		$text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8', $double_encode);
	}

	if ($attributes) {
		$attrs = elgg_format_attributes($attributes);
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
 * Converts shorthand urls to absolute urls.
 *
 * No change is made if the URL: is absolute, protocol-relative, starts with a protocol/fragment/query.
 *
 * @example
 * elgg_normalize_url('');                   // 'http://my.site.com/'
 * elgg_normalize_url('dashboard');          // 'http://my.site.com/dashboard'
 * elgg_normalize_url('http://google.com/'); // no change
 * elgg_normalize_url('//google.com/');      // no change
 *
 * @param string $url The URL to normalize
 *
 * @return string The absolute url
 */
function elgg_normalize_url($url) {
	$url = str_replace(' ', '%20', $url);

	if (_elgg_sane_validate_url($url)) {
		return $url;
	}

	if (preg_match("#^([a-z]+)\\:#", $url, $m)) {
		// we don't let http/https: URLs fail filter_var(), but anything else starting with a protocol
		// is OK
		if ($m[1] !== 'http' && $m[1] !== 'https') {
			return $url;
		}
	}

	if (preg_match("#^(\\#|\\?|//)#", $url)) {
		// starts with '//' (protocol-relative link), query, or fragment
		return $url;
	}

	if (preg_match("#^[^/]*\\.php(\\?.*)?$#", $url)) {
		// root PHP scripts: 'install.php', 'install.php?step=step'. We don't want to confuse these
		// for domain names.
		return elgg_get_site_url() . $url;
	}

	if (preg_match("#^[^/?]*\\.#", $url)) {
		// URLs starting with domain: 'example.com', 'example.com/subpage'
		return "http://$url";
	}

	// 'page/handler', 'mod/plugin/file.php'
	// trim off any leading / because the site URL is stored
	// with a trailing /
	return elgg_get_site_url() . ltrim($url, '/');
}

/**
 * When given a title, returns a version suitable for inclusion in a URL
 *
 * @param string $title The title
 *
 * @return string The optimized title
 * @since 1.7.2
 */
function elgg_get_friendly_title($title) {

	// return a URL friendly title to short circuit normal title formatting
	$params = array('title' => $title);
	$result = elgg_trigger_plugin_hook('format', 'friendly:title', $params, null);
	if ($result) {
		return $result;
	}

	// titles are often stored HTML encoded
	$title = html_entity_decode($title, ENT_QUOTES, 'UTF-8');
	
	$title = \Elgg\Translit::urlize($title);

	return $title;
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
function elgg_get_friendly_time($time, $current_time = null) {
	
	if (!$current_time) {
		$current_time = time();
	}

	// return a time string to short circuit normal time formatting
	$params = array('time' => $time, 'current_time' => $current_time);
	$result = elgg_trigger_plugin_hook('format', 'friendly:time', $params, null);
	if ($result) {
		return $result;
	}

	$diff = abs((int)$current_time - (int)$time);

	$minute = 60;
	$hour = $minute * 60;
	$day = $hour * 24;

	if ($diff < $minute) {
		return elgg_echo("friendlytime:justnow");
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
	
	$future = ((int)$current_time - (int)$time < 0) ? ':future' : '';
	$singular = ($diff == 1) ? ':singular' : '';

	return elgg_echo("friendlytime{$future}{$granularity}{$singular}", array($diff));
}

/**
 * Returns a human-readable message for PHP's upload error codes
 *
 * @param int $error_code The code as stored in $_FILES['name']['error']
 * @return string
 */
function elgg_get_friendly_upload_error($error_code) {
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

	return elgg_echo("upload:error:$key");
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
function elgg_strip_tags($string, $allowable_tags = null) {
	$params['original_string'] = $string;
	$params['allowable_tags'] = $allowable_tags;

	$string = strip_tags($string, $allowable_tags);
	$string = elgg_trigger_plugin_hook('format', 'strip_tags', $params, $string);

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
function elgg_html_decode($string) {
	$string = str_replace(
		array('&gt;', '&lt;', '&amp;', '&quot;', '&#039;'),
		array('&amp;gt;', '&amp;lt;', '&amp;amp;', '&amp;quot;', '&amp;#039;'),
		$string
	);
	$string = html_entity_decode($string, ENT_NOQUOTES, 'UTF-8');
	$string = str_replace(
		array('&amp;gt;', '&amp;lt;', '&amp;amp;', '&amp;quot;', '&amp;#039;'),
		array('&gt;', '&lt;', '&amp;', '&quot;', '&#039;'),
		$string
	);
	return $string;
}

/**
 * Alias of elgg_html_decode
 *
 * This is kept in 2.0 because it was used in public views and might have been copied into plugins.
 *
 * @param string $string Encoded HTML
 *
 * @return string
 * @see elgg_html_decode
 * @deprecated
 */
function _elgg_html_decode($string) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_html_decode()', '2.0');
	return elgg_html_decode($string);
}

/**
 * Prepares query string for output to prevent CSRF attacks.
 *
 * @param string $string
 * @return string
 *
 * @access private
 */
function _elgg_get_display_query($string) {
	//encode <,>,&, quotes and characters above 127
	if (function_exists('mb_convert_encoding')) {
		$display_query = mb_convert_encoding($string, 'HTML-ENTITIES', 'UTF-8');
	} else {
		// if no mbstring extension, we just strip characters
		$display_query = preg_replace("/[^\x01-\x7F]/", "", $string);
	}
	return htmlspecialchars($display_query, ENT_QUOTES, 'UTF-8', false);
}

/**
 * Use a "fixed" filter_var() with FILTER_VALIDATE_URL that handles multi-byte chars.
 *
 * @param string $url URL to validate
 * @return string|false
 * @access private
 */
function _elgg_sane_validate_url($url) {
	// based on http://php.net/manual/en/function.filter-var.php#104160
	$res = filter_var($url, FILTER_VALIDATE_URL);
	if ($res) {
		return $res;
	}

	// Check if it has unicode chars.
	$l = elgg_strlen($url);
	if (strlen($url) == $l) {
		return $res;
	}

	// Replace wide chars by “X”.
	$s = '';
	for ($i = 0; $i < $l; ++$i) {
		$ch = elgg_substr($url, $i, 1);
		$s .= (strlen($ch) > 1) ? 'X' : $ch;
	}

	// Re-check now.
	return filter_var($s, FILTER_VALIDATE_URL) ? $url : false;
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {

};
