<?php
/**
 * Elgg global system message list
 * Lists all system messages
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['object'] The array of message registers
 */

$messages = (array) elgg_extract('object', $vars, []);

$list_items = '';

foreach ($messages as $type => $list) {
	foreach ($list as $message) {
		$list_items .= elgg_format_element('li', [
			'class' => "elgg-state-$type",
		], $message);
	}
}

echo elgg_format_element('ul', ['class' => 'elgg-system-messages'], $list_items);
