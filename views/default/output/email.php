<?php
/**
 * Elgg email output
 * Displays an email address that was entered using an email input field
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] The email address to display
 *
 */

if (empty($vars['value'])) {
	return;
}

if (empty($vars['text'])) {
	$vars['text'] = $vars['value'];
	$vars['encode_text'] = true;
}

$vars['href'] = "mailto:{$vars['value']}";

unset($vars['value']);

echo elgg_view('output/url', $vars);
