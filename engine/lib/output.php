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
 **/
function parse_urls($text) {
	// @todo this causes problems with <attr = "val">
	// must be in <attr="val"> format (no space).
	// By default htmlawed rewrites tags to this format.
	// if PHP supported conditional negative lookbehinds we could use this:
	// $r = preg_replace_callback('/(?<!=)(?<![ ])?(?<!["\'])((ht|f)tps?:\/\/[^\s\r\n\t<>"\'\!\(\),]+)/i',
	//
	// we can put , in the list of excluded char but need to keep . because of domain names.
	// it is removed in the callback.
	$r = preg_replace_callback('/(?<!=)(?<!["\'])((ht|f)tps?:\/\/[^\s\r\n\t<>"\'\!\(\),]+)/i',
	create_function(
		'$matches',
		'
			$url = $matches[1];
			$period = \'\';
			if (substr($url, -1, 1) == \'.\') {
				$period = \'.\';
				$url = trim($url, \'.\');
			}
			$urltext = str_replace("/", "/<wbr />", $url);
			return "<a href=\"$url\">$urltext</a>$period";
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
	if ($space === FALSE) {
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
function elgg_format_attributes(array $attrs) {
	$attrs = elgg_clean_vars($attrs);
	$attributes = array();

	if (isset($attrs['js'])) {
		//@todo deprecated notice?

		if (!empty($attrs['js'])) {
			$attributes[] = $attrs['js'];
		}

		unset($attrs['js']);
	}

	foreach ($attrs as $attr => $val) {
		$attr = strtolower($attr);

		if ($val === TRUE) {
			$val = $attr; //e.g. checked => TRUE ==> checked="checked"
		}

		// ignore $vars['entity'] => ElggEntity stuff
		if ($val !== NULL && $val !== false && (is_array($val) || !is_object($val))) {

			// allow $vars['class'] => array('one', 'two');
			// @todo what about $vars['style']? Needs to be semi-colon separated...
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
function elgg_clean_vars(array $vars = array()) {
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

	} elseif (preg_match("#^[^/]*\.#i", $url)) {
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
 * @return string The optimised title
 * @since 1.7.2
 */
function elgg_get_friendly_title($title) {

	// return a URL friendly title to short circuit normal title formatting
	$params = array('title' => $title);
	$result = elgg_trigger_plugin_hook('format', 'friendly:title', $params, NULL);
	if ($result) {
		return $result;
	}

	// handle some special cases
	$title = str_replace('&amp;', 'and', $title);
	// quotes and angle brackets stored in the database as html encoded
	$title = htmlspecialchars_decode($title);

	$title = ElggTranslit::urlize($title);

	return $title;
}

/**
 * Formats a UNIX timestamp in a friendly way (eg "less than a minute ago")
 *
 * @see elgg_view_friendly_time()
 *
 * @param int $time A UNIX epoch timestamp
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
	$result = elgg_trigger_plugin_hook('format', 'friendly:time', $params, NULL);
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
 * Strip tags and offer plugins the chance.
 * Plugins register for output:strip_tags plugin hook.
 * Original string included in $params['original_string']
 *
 * @param string $string Formatted string
 *
 * @return string String run through strip_tags() and any plugin hooks.
 */
function elgg_strip_tags($string) {
	$params['original_string'] = $string;

	$string = strip_tags($string);
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
function output_unit_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = "{$CONFIG->path}engine/tests/ElggCoreOutputAutoPTest.php";
	return $value;
}

/**
 * Initialise the Output subsystem.
 *
 * @return void
 * @access private
 */
function output_init() {
	elgg_register_plugin_hook_handler('unit_test', 'system', 'output_unit_test');
}

elgg_register_event_handler('init', 'system', 'output_init');
