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

$text = elgg_extract('value', $vars);
unset($vars['value']);

$text = elgg_format_html($text, $vars);

if (empty($text)) {
	return;
}

unset($vars['parse_urls'], $vars['parse_emails'], $vars['sanitize'], $vars['autop']);

echo elgg_format_element('div', $vars, $text);
