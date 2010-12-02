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

if (!empty($vars['object']) && is_array($vars['object']) && sizeof($vars['object']) > 0) {
?>

<ul class="elgg-system-messages">
<?php 
	foreach ($vars['object'] as $register => $list ) {
		echo elgg_view("messages/{$register}/list", array('object' => $list));
	}
?>
</ul>
<?php
}
