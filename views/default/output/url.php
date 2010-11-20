<?php
/**
 * Elgg URL display
 * Displays a URL as a link
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses string $vars['text']        The string between the <a></a> tags.
 * @uses bool   $vars['encode_text'] Run $vars['text'] through htmlentities()?
 * @uses bool   $vars['is_action']   Is this a link to an action?
 *
 */

$url = trim($vars['href']);
if (!$url and isset($vars['value'])) {
	$url = trim($vars['value']);
	unset($vars['value']);
}

if (!empty($url)) {
	if (isset($vars['text'])) {
		if (isset($vars['encode_text']) && $vars['encode_text']) {
			$text = htmlentities($vars['text'], ENT_QUOTES, 'UTF-8');
		} else {
			$text = $vars['text'];
		}

		unset($vars['text']);
	} else {
		$text = htmlentities($url, ENT_QUOTES, 'UTF-8');
	}

	unset($vars['encode_text']);

	$url = elgg_normalize_url($url);

	if (isset($vars['is_action'])) {
		$url = elgg_add_action_tokens_to_url($url);
		unset($vars['is_action']);
	}

	$vars['href'] = $url;

	$attributes = elgg_format_attributes($vars);
	echo "<a $attributes>$text</a>";
}