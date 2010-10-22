<?php
/**
 * Elgg standard message
 * Displays a single Elgg system message
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['object'] A system message (string)
 */

echo elgg_view('output/longtext', array(
	'value' => $vars['object'],
	'parse_urls' => FALSE)
);
