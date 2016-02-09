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
 * @uses bool   $vars['encode_text'] Run $vars['text'] through htmlspecialchars() (false)
 * @uses bool   $vars['is_action']   Is this a link to an action (false)
 * @uses bool   $vars['is_trusted']  Is this link trusted (false)
 * @uses mixed  $vars['confirm']     Confirmation dialog text | (bool) true
 * 
 * Note: if confirm is set to true or has dialog text 'is_action' will default to true
 * 
 */

if (!empty($vars['confirm']) && !isset($vars['is_action'])) {
	$vars['is_action'] = true;
}

if (!empty($vars['confirm'])) {
	$vars['data-confirm'] = elgg_extract('confirm', $vars, elgg_echo('question:areyousure'));
	
	// if (bool) true use defaults
	if ($vars['data-confirm'] === true) {
		$vars['data-confirm'] = elgg_echo('question:areyousure');
	}
}

$url = elgg_extract('href', $vars, null);
if (!$url && isset($vars['value'])) {
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

	$is_trusted = elgg_extract('is_trusted', $vars);
	if (!$is_trusted) {
		$url = strip_tags($url);
		if (!isset($vars['rel'])) {
			if ($is_trusted === null) {
				$url_host = parse_url($url, PHP_URL_HOST);
				$site_url = elgg_get_site_url();
				$site_url_host = parse_url($site_url, PHP_URL_HOST);
				$is_trusted = $url_host == $site_url_host;
			}
			if ($is_trusted === false) {
				// this is an external URL, which we do not want to be indexed by crawlers
				$vars['rel'] = 'nofollow';
			}
		}
	}

	$vars['href'] = $url;
}

if (!isset($vars['title']) && isset($vars['data-confirm'])) {
	$vars['title'] = $vars['data-confirm'];
}

unset($vars['is_action']);
unset($vars['is_trusted']);
unset($vars['confirm']);

$attributes = elgg_format_attributes($vars);
echo "<a $attributes>$text</a>";
