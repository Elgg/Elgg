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

if (!empty($vars['value'])) {
	echo elgg_view('output/url', [
		'href' => 'mailto:' . $vars['value'],
		'text' => elgg_extract('value', $vars),
		'encode_text' => true,
	]);
}
