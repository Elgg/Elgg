<?php
/**
 * Elgg URL display
 * Displays a URL as a link
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses string $vars['text']        The HTML between the <a></a> tags.
 * @uses string $vars['href']        The raw, un-encoded URL.
 *                                   "" = current URL.
 *                                   "/" = site home page.
 * @uses bool   $vars['encode_text'] Run $vars['text'] through htmlspecialchars() (false)
 * @uses bool   $vars['is_action']   Is this a link to an action (default: false, unless 'confirm' parameter is set)
 * @uses bool   $vars['is_trusted']  Is this link trusted (false)
 * @uses mixed  $vars['confirm']     Confirmation dialog text | (bool) true
 *                                   Note that if 'confirm' is set to true or a dialog text,
 *                                   'is_action' parameter will default to true
 * @uses string $vars['icon']        Name of the Elgg icon, or icon HTML, appended before the text label
 * @uses string $vars['icon_alt']        Name of the Elgg icon, or icon HTML, appended after the text label
 * @uses string $vars['badge']       HTML content of the badge appended after the text label
 * @uses int    $vars['excerpt_length'] Length of the URL excerpt if text is not given.
 */

$excerpt_length = elgg_extract('excerpt_length', $vars, 100);
unset($vars['excerpt_length']);

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
if ($url === false) {
	$url = 'javascript:void(0);';
}

if (!$url && isset($vars['value'])) {
	$url = trim($vars['value']);
	unset($vars['value']);
}

if (isset($vars['text'])) {
	if (elgg_extract('encode_text', $vars, false)) {
		$text = htmlspecialchars($vars['text'], ENT_QUOTES, 'UTF-8', false);
	} else {
		$text = elgg_extract('text', $vars);
	}
	unset($vars['text']);
} else {
	$text = htmlspecialchars(elgg_get_excerpt($url, $excerpt_length), ENT_QUOTES, 'UTF-8', false);
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
	$vars['title'] = elgg_extract('data-confirm', $vars);
}

unset($vars['is_action']);
unset($vars['is_trusted']);
unset($vars['confirm']);

$vars['class'] = elgg_extract_class($vars, 'elgg-anchor');

if ($text !== false && $text !== '') {
	$text = elgg_format_element('span', [
		'class' => 'elgg-anchor-label',
	], $text);
}

$icon = elgg_extract('icon', $vars, '');
unset($vars['icon']);

if ($icon && !preg_match('/^</', $icon)) {
	$icon = elgg_view_icon($icon, [
		'class' => 'elgg-anchor-icon',
	]);
}

$icon_alt = elgg_extract('icon_alt', $vars, '');
unset($vars['icon_alt']);

if ($icon_alt && !preg_match('/^</', $icon_alt)) {
	$icon_alt = elgg_view_icon($icon_alt, [
		'class' => 'elgg-anchor-icon-alt',
	]);
}

$badge = elgg_extract('badge', $vars);
unset($vars['badge']);

if (!is_null($badge)) {
	$badge = elgg_format_element([
		'#tag_name' => 'span',
		'#text' => $badge,
		'class' => 'elgg-badge',
	]);
}

echo elgg_format_element('a', $vars, $icon . $text . $icon_alt . $badge);
