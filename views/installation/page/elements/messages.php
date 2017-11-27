<?php
/**
 * Lists all system messages
 *
 * @uses $vars['object'] The array of message registers
 */

if (isset($vars['object']) && is_array($vars['object']) && sizeof($vars['object']) > 0) {
	echo '<ul class="elgg-system-messages">';

	foreach ($vars['object'] as $type => $list) {
		foreach ($list as $message) {
			echo elgg_format_element('li', [], elgg_view_message($type, $message, ['title' => false]));
		}
	}

	echo '</ul>';
}
