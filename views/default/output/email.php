<?php
/**
 * Elgg email output
 * Displays an email address that was entered using an email input field
 *
 * @uses $vars['value'] The email address to display
 */

$value = elgg_extract('value', $vars);
unset($vars['value']);

if (empty($value)) {
	return;
}

$vars['href'] = "mailto:{$value}";
$vars['encode_text'] = true;
$vars['text'] = elgg_extract('text', $vars, $value);

echo elgg_view('output/url', $vars);
