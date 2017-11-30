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

// hidden li so we validate, we need this for javascript added system messages
$list_items = elgg_format_element('li', ['class' => 'hidden']);

foreach ($messages as $type => $list) {
	foreach ($list as $message) {
		$list_items .= elgg_format_element('li', [], elgg_view_message($type, $message, ['title' => false]));
	}
}

echo elgg_format_element('ul', ['class' => 'elgg-system-messages'], $list_items);
