<?php
/**
 * Elgg display long text
 * Displays a large amount of text, with new lines converted to line breaks
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] The text to display
 * @uses $vars['parse_urls'] Whether to turn urls into links. Default is true.
 * @uses $vars['class']
 */

$vars['class'] = elgg_extract_class($vars, 'elgg-output');

$parse_urls = elgg_extract('parse_urls', $vars, true);
unset($vars['parse_urls']);

$text = $vars['value'];
unset($vars['value']);

if ($parse_urls) {
	$text = parse_urls($text);
}

$text = filter_tags($text);

$text = elgg_autop($text);

echo elgg_format_element('div', $vars, $text);
