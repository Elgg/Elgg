<?php
/**
 * Elgg URL display
 * Displays a URL as a link
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses string $vars['text']        The string between the <a></a> tags.
 * @uses string $vars['href']        The unencoded url string
 * @uses string $vars['rel']         The relationship of the destination url relative to the page
 * @uses string $vars['class']       The class names (if any) for the hyperlink.
 * @uses bool   $vars['encode_text'] Run $vars['text'] through htmlspecialchars() (false)
 * @uses bool   $vars['is_action']   Is this a link to an action (false)
 * @uses bool   $vars['is_trusted']  Is this link trusted (false)
 */

$url = elgg_extract('href', $vars, null);
if (!$url and isset($vars['value'])) {
	$url = trim($vars['value']);
	unset($vars['value']);
}

if (isset($vars['text'])) {
	if (elgg_extract('encode_text', $vars, false)) {
		$text = htmlspecialchars($vars['text'], ENT_QUOTES, 'UTF-8', false);
	} else {
		$text = $vars['text'];
	}
	unset($vars['text']);
} else {
	$text = htmlspecialchars($url, ENT_QUOTES, 'UTF-8', false);
}

unset($vars['encode_text']);

if ($url) {
	$url = elgg_normalize_url($url);

	if (elgg_extract('is_action', $vars, false)) {
		$url = elgg_add_action_tokens_to_url($url, false);
	}

	if (!elgg_extract('is_trusted', $vars, false)) {
		if (!isset($vars['rel'])) {
			$vars['rel'] = 'nofollow';
			$url = strip_tags($url);  // should this be inside the rel isset test?
		}
	}

	$vars['href'] = $url;
	
	if (!isset($vars['class'])) {
		$vars['class'] .= ' ';
	} else {
		$vars['class'] = '';
	}
	$vars['class'] .= 'u-url url';
}

unset($vars['is_action']);
unset($vars['is_trusted']);

$attributes = elgg_format_attributes($vars);
echo "<a $attributes>$text</a>";
