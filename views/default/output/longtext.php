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
 */

$parse_urls = isset($vars['parse_urls']) ? $vars['parse_urls'] : TRUE;

$text = $vars['value'];

$text = filter_tags($text);

if ($parse_urls) {
	$text = parse_urls($text);
}

$text = autop($text);

echo $text;
