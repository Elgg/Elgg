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
 * @return string The output stirng with formatted links
 **/
function parse_urls($text) {
	// @todo this causes problems with <attr = "val">
	// must be ing <attr="val"> format (no space).
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
			return "<a href=\"$url\" style=\"text-decoration:underline;\">$urltext</a>$period";
		'
	), $text);

	return $r;
}

/**
 * Create paragraphs from text with line spacing
 * Borrowed from Wordpress.
 *
 * @param string $pee
 * @param bool $br
 * @return string
 **/
function autop($pee, $br = 1) {
	$pee = $pee . "\n"; // just to make things a little easier, pad the end
	$pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);
	// Space things out a little
	$allblocks = '(?:table|thead|tfoot|caption|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|map|area|blockquote|address|math|style|input|p|h[1-6]|hr)';
	$pee = preg_replace('!(<' . $allblocks . '[^>]*>)!', "\n$1", $pee);
	$pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);
	$pee = str_replace(array("\r\n", "\r"), "\n", $pee); // cross-platform newlines
	if ( strpos($pee, '<object') !== false ) {
		$pee = preg_replace('|\s*<param([^>]*)>\s*|', "<param$1>", $pee); // no pee inside object/embed
		$pee = preg_replace('|\s*</embed>\s*|', '</embed>', $pee);
	}
	$pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
	$pee = preg_replace('/\n?(.+?)(?:\n\s*\n|\z)/s', "<p>$1</p>\n", $pee); // make paragraphs, including one at the end
	$pee = preg_replace('|<p>\s*?</p>|', '', $pee); // under certain strange conditions it could create a P of entirely whitespace
	$pee = preg_replace('!<p>([^<]+)\s*?(</(?:div|address|form)[^>]*>)!', "<p>$1</p>$2", $pee);
	$pee = preg_replace( '|<p>|', "$1<p>", $pee );
	$pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee); // don't pee all over a tag
	$pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
	$pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
	$pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
	$pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);
	$pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);
	if ($br) {
		$pee = preg_replace_callback('/<(script|style).*?<\/\\1>/s', create_function('$matches', 'return str_replace("\n", "<WPPreserveNewline />", $matches[0]);'), $pee);
		$pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
		$pee = str_replace('<WPPreserveNewline />', "\n", $pee);
	}
	$pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);
	$pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
//	if (strpos($pee, '<pre') !== false) {
//		mind the space between the ? and >.  Only there because of the comment.
//		$pee = preg_replace_callback('!(<pre.*? >)(.*?)</pre>!is', 'clean_pre', $pee );
//	}
	$pee = preg_replace( "|\n</p>$|", '</p>', $pee );

	return $pee;
}

/**
 * Returns an excerpt.
 * Will return up to n chars stopping at the nearest space.
 * If no spaces are found (like in Japanese) will crop off at the
 * n char mark. Adds ... if any text was chopped.
 *
 * @param string $text
 * @param int $num_chars Return a string up to $num_chars long
 * @return string
 * @since 1.7.2
 */
function elgg_get_excerpt($text, $num_chars = 250) {
	$text = trim(strip_tags($text));
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
 * @param string $url
 * @return string
 * @since 1.7.1
 */
function elgg_format_url($url) {
	return preg_replace('/&(?!amp;)/', '&amp;', $url);
}

/**
 * When given a title, returns a version suitable for inclusion in a URL
 *
 * @param string $title The title
 * @return string The optimised title
 */
function friendly_title($title) {
	return elgg_get_friendly_title($title);
}

/**
 * When given a title, returns a version suitable for inclusion in a URL
 *
 * @param string $title The title
 * @return string The optimised title
 * @since 1.7.2
 */
function elgg_get_friendly_title($title) {

	// return a URL friendly title to short circuit normal title formatting
	$params = array('title' => $title);
	$result = trigger_plugin_hook('format', 'friendly:title', $params, NULL);
	if ($result) {
		return $result;
	}

	//$title = iconv('UTF-8', 'ASCII//TRANSLIT', $title);
	$title = preg_replace("/[^\w ]/","",$title);
	$title = str_replace(" ","-",$title);
	$title = str_replace("--","-",$title);
	$title = trim($title);
	$title = strtolower($title);
	return $title;
}

/**
 * Displays a UNIX timestamp in a friendly way (eg "less than a minute ago")
 *
 * @param int $time A UNIX epoch timestamp
 * @return string The friendly time
 */
function friendly_time($time) {
	return elgg_view_friendly_time($time);
}

/**
 * Formats a UNIX timestamp in a friendly way (eg "less than a minute ago")
 *
 * @see elgg_view_friendly_time()
 * 
 * @param int $time A UNIX epoch timestamp
 * @return string The friendly time string
 * @since 1.7.2
 */
function elgg_get_friendly_time($time) {

	// return a time string to short circuit normal time formatting
	$params = array('time' => $time);
	$result = trigger_plugin_hook('format', 'friendly:time', $params, NULL);
	if ($result) {
		return $result;
	}

	$diff = time() - (int)$time;

	$minute = 60;
	$hour = $minute * 60;
	$day = $hour * 24;

	if ($diff < $minute) {
			return elgg_echo("friendlytime:justnow");
	} else if ($diff < $hour) {
		$diff = round($diff / $minute);
		if ($diff == 0) {
			$diff = 1;
		}

		if ($diff > 1) {
			return sprintf(elgg_echo("friendlytime:minutes"), $diff);
		} else {
			return sprintf(elgg_echo("friendlytime:minutes:singular"), $diff);
		}
	} else if ($diff < $day) {
		$diff = round($diff / $hour);
		if ($diff == 0) {
			$diff = 1;
		}

		if ($diff > 1) {
			return sprintf(elgg_echo("friendlytime:hours"), $diff);
		} else {
			return sprintf(elgg_echo("friendlytime:hours:singular"), $diff);
		}
	} else {
		$diff = round($diff / $day);
		if ($diff == 0) {
			$diff = 1;
		}

		if ($diff > 1) {
			return sprintf(elgg_echo("friendlytime:days"), $diff);
		} else {
			return sprintf(elgg_echo("friendlytime:days:singular"), $diff);
		}
	}
}
