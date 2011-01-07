<?php
/**
 * Lists all system messages
 *
 * @uses $vars['object'] The array of message registers
 */

if (isset($vars['object']) && is_array($vars['object']) && sizeof($vars['object']) > 0) {

	echo '<ul class="elgg-system-messages">';

	foreach ($vars['object'] as $type => $list ) {
		foreach ($list as $message) {
			echo "<li class=\"elgg-state-$type\">";
			echo autop($message);
			echo '</li>';
		}
	}

	echo '</ul>';
}
