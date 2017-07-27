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

// hidden li so we validate, we need this for javascript added system messages
$list_items = '<li class="hidden"></li>';

if (isset($vars['object']) && is_array($vars['object']) && sizeof($vars['object']) > 0) {
	foreach ($vars['object'] as $type => $list) {
		foreach ($list as $message) {
			$list_items .= elgg_format_element('li', [
				'class' => "elgg-message elgg-state-$type",
			], elgg_autop($message));
		}
	}
}

echo elgg_format_element('ul', ['class' => 'elgg-system-messages'], $list_items);
