<?php
/**
 * Displays HTML, with new lines converted to line breaks
 *
 * @uses $vars['value'] HTML to display
 * @uses $vars['class']
 * @uses $vars['parse_urls'] Turn urls into links? Default is true.
 * @uses $vars['parse_emails'] Turn email addresses into mailto links? Default is true.
 * @uses $vars['sanitize'] Sanitize HTML? (highly recommended) Default is true.
 * @uses $vars['autop'] Convert line breaks to paragraphs? Default is true.
 */

$vars['class'] = elgg_extract_class($vars, 'elgg-output');

$parse_urls = elgg_extract('parse_urls', $vars, true);
unset($vars['parse_urls']);

$parse_emails = elgg_extract('parse_emails', $vars, true);
unset($vars['parse_emails']);

$sanitize = elgg_extract('sanitize', $vars, true);
unset($vars['sanitize']);

$autop = elgg_extract('autop', $vars, true);
unset($vars['autop']);

$text = $vars['value'];
unset($vars['value']);

if ($parse_urls) {
	$text = parse_urls($text);
}

if ($parse_emails) {
	$text = elgg_parse_emails($text);
}

if ($sanitize) {
	$text = filter_tags($text);
}

if ($autop) {
	$text = elgg_autop($text);
}

echo elgg_format_element('div', $vars, $text);
