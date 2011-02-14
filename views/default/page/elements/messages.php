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

echo '<ul class="elgg-system-messages">';

if (isset($vars['object']) && is_array($vars['object']) && sizeof($vars['object']) > 0) {
	foreach ($vars['object'] as $type => $list ) {
		foreach ($list as $message) {
			echo "<li class=\"elgg-message elgg-state-$type\">";
			echo elgg_view('output/longtext', array(
				'value' => $message,
				'parse_urls' => false
			));
			echo '</li>';
		}
	}
}

echo '</ul>';
