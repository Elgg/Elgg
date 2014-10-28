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

	// URI specification: http://www.ietf.org/rfc/rfc3986.txt
	// This varies from the specification in the following ways:
	//  * Supports non-ascii characters
	//  * Does not allow parentheses and single quotes
	//  * Cuts off commas, exclamation points, and periods off as last character

	// @todo this causes problems with <attr = "val">
	// must be in <attr="val"> format (no space).
	// By default htmlawed rewrites tags to this format.
	// if PHP supported conditional negative lookbehinds we could use this:
	// $r = preg_replace_callback('/(?<!=)(?<![ ])?(?<!["\'])((ht|f)tps?:\/\/[^\s\r\n\t<>"\'\!\(\),]+)/i',
	$r = preg_replace_callback('/(?<![=\/"\'])((ht|f)tps?:\/\/[^\s\r\n\t<>"\']+)/i',
	create_function(
		'$matches',
		'
			$url = $matches[1];
			$punc = "";
			$last = substr($url, -1, 1);
			if (in_array($last, array(".", "!", ",", "(", ")"))) {
				$punc = $last;
				$url = rtrim($url, ".!,()");
			}
			$urltext = str_replace("/", "/<wbr />", $url);
			return "<a href=\"$url\" rel=\"nofollow\">$urltext</a>$punc";
		'
	), $text);

	return $r;
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
	$text = trim(elgg_strip_tags($text));
	$string_length = elgg_strlen($text);

	if ($string_length <= $num_chars) {
		return $text;
	}

	// handle cases
	$excerpt = elgg_substr($text, 0, $num_chars);
	$space = elgg_strrpos($excerpt, ' ', 0);

	// don't crop if can't find a space.
	if ($space === false) {
		$space = $num_chars;
	}
	$excerpt = trim(elgg_substr($excerpt, 0, $space));

	if ($string_length != elgg_strlen($excerpt)) {
		$excerpt .= '...';
	}

	return $excerpt;
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
 * Converts an associative array into a string of well-formed attributes
 *
 * @note usually for HTML, but could be useful for XML too...
 *
 * @param array $attrs An associative array of attr => val pairs
 *
 * @return string HTML attributes to be inserted into a tag (e.g., <tag $attrs>)
 */
function elgg_format_attributes(array $attrs = array()) {
	if (!is_array($attrs) || !count($attrs)) {
		return '';
	}

	$attrs = _elgg_clean_vars($attrs);
	$attributes = array();

	if (isset($attrs['js'])) {
		elgg_deprecated_notice('Use associative array of attr => val pairs instead of $vars[\'js\']', 1.8);

		if (!empty($attrs['js'])) {
			$attributes[] = $attrs['js'];
		}

		unset($attrs['js']);
	}

	foreach ($attrs as $attr => $val) {
		$attr = strtolower($attr);

		if ($val === true) {
			$val = $attr; //e.g. checked => true ==> checked="checked"
		}

		/**
		 * Ignore non-array values and allow attribute values to be an array
		 *  <code>
		 *  $attrs = array(
		 *		'entity' => <ElggObject>, // will be ignored
		 * 		'class' => array('elgg-input', 'elgg-input-text'), // will be imploded with spaces
		 * 		'style' => array('margin-left:10px;', 'color: #666;'), // will be imploded with spaces
		 *		'alt' => 'Alt text', // will be left as is
		 *  );
		 *  </code>
		 */
		if ($val !== NULL && $val !== false && (is_array($val) || !is_object($val))) {
			if (is_array($val)) {
				$val = implode(' ', $val);
			}

			$val = htmlspecialchars($val, ENT_QUOTES, 'UTF-8', false);
			$attributes[] = "$attr=\"$val\"";
		}
	}

	return implode(' ', $attributes);
}

/**
 * Format an HTML element
 *
 * @param string $tag_name   The element tagName. e.g. "div". This will not be validated.
 *
 * @param array  $attributes The element attributes. This is passed to elgg_format_attributes().
 *
 * @param string $text       The contents of the element. Assumed to be HTML unless encode_text is true.
 *
 * @param array  $options    Options array with keys:
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
	if (!is_string($tag_name)) {
		throw new InvalidArgumentException('$tag_name is required');
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
 * Preps an associative array for use in {@link elgg_format_attributes()}.
 *
 * Removes all the junk that {@link elgg_view()} puts into $vars.
 * Maintains backward compatibility with attributes like 'internalname' and 'internalid'
 *
 * @note This function is called automatically by elgg_format_attributes(). No need to
 *       call it yourself before using elgg_format_attributes().
 *
 * @param array $vars The raw $vars array with all it's dirtiness (config, url, etc.)
 *
 * @return array The array, ready to be used in elgg_format_attributes().
 * @access private
 */
function _elgg_clean_vars(array $vars = array()) {
	unset($vars['config']);
	unset($vars['url']);
	unset($vars['user']);

	// backwards compatibility code
	if (isset($vars['internalname'])) {
		if (!isset($vars['__ignoreInternalname'])) {
			$vars['name'] = $vars['internalname'];
		}
		unset($vars['internalname']);
	}

	if (isset($vars['internalid'])) {
		if (!isset($vars['__ignoreInternalid'])) {
			$vars['id'] = $vars['internalid'];
		}
		unset($vars['internalid']);
	}

	if (isset($vars['__ignoreInternalid'])) {
		unset($vars['__ignoreInternalid']);
	}

	if (isset($vars['__ignoreInternalname'])) {
		unset($vars['__ignoreInternalname']);
	}

	return $vars;
}

/**
 * Converts shorthand urls to absolute urls.
 *
 * If the url is already absolute or protocol-relative, no change is made.
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
	// see https://bugs.php.net/bug.php?id=51192
	// from the bookmarks save action.
	$php_5_2_13_and_below = version_compare(PHP_VERSION, '5.2.14', '<');
	$php_5_3_0_to_5_3_2 = version_compare(PHP_VERSION, '5.3.0', '>=') &&
			version_compare(PHP_VERSION, '5.3.3', '<');

	if ($php_5_2_13_and_below || $php_5_3_0_to_5_3_2) {
		$tmp_address = str_replace("-", "", $url);
		$validated = filter_var($tmp_address, FILTER_VALIDATE_URL);
	} else {
		$validated = filter_var($url, FILTER_VALIDATE_URL);
	}

	// work around for handling absoluate IRIs (RFC 3987) - see #4190
	if (!$validated && (strpos($url, 'http:') === 0) || (strpos($url, 'https:') === 0)) {
		$validated = true;
	}

	if ($validated) {
		// all normal URLs including mailto:
		return $url;

	} elseif (preg_match("#^(\#|\?|//)#i", $url)) {
		// '//example.com' (Shortcut for protocol.)
		// '?query=test', #target
		return $url;

	} elseif (stripos($url, 'javascript:') === 0 || stripos($url, 'mailto:') === 0) {
		// 'javascript:' and 'mailto:'
		// Not covered in FILTER_VALIDATE_URL
		return $url;

	} elseif (preg_match("#^[^/]*\.php(\?.*)?$#i", $url)) {
		// 'install.php', 'install.php?step=step'
		return elgg_get_site_url() . $url;

	} elseif (preg_match("#^[^/?]*\.#i", $url)) {
		// 'example.com', 'example.com/subpage'
		return "http://$url";

	} else {
		// 'page/handler', 'mod/plugin/file.php'

		// trim off any leading / because the site URL is stored
		// with a trailing /
		return elgg_get_site_url() . ltrim($url, '/');
	}
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

	$title = Elgg_Translit::urlize($title);

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
 * Apply html_entity_decode() to a string while re-entitising HTML
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
 * Note: This function is poorly explained in the manual - which is really
 * bad given its potential for misuse on user input already escaped elsewhere.
 * Stackoverflow is littered with advice to use this function in the precise
 * way that would lead to user input being capable of injecting arbitrary HTML.
 *
 * @param string $string
 *
 * @return string
 *
 * @author Pádraic Brady
 * @copyright Copyright (c) 2010 Pádraic Brady (http://blog.astrumfutura.com)
 * @license Released under dual-license GPL2/MIT by explicit permission of Pádraic Brady
 *
 * @access private
 */
function _elgg_html_decode($string) {
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
 * Unit tests for Output
 *
 * @param string $hook   unit_test
 * @param string $type   system
 * @param mixed  $value  Array of tests
 * @param mixed  $params Params
 *
 * @return array
 * @access private
 */
function _elgg_output_unit_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = "{$CONFIG->path}engine/tests/ElggCoreOutputAutoPTest.php";
	return $value;
}

/**
 * Initialize the output subsystem.
 *
 * @return void
 * @access private
 */
function _elgg_output_init() {
	elgg_register_plugin_hook_handler('unit_test', 'system', '_elgg_output_unit_test');
}

elgg_register_event_handler('init', 'system', '_elgg_output_init');
