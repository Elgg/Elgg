<?php
/**
 * Elgg global system message list
 * Lists all system messages
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['object'] The array of message registers
 */

if (!empty($vars['object']) && is_array($vars['object']) && sizeof($vars['object']) > 0) {
	foreach($vars['object'] as $register => $list ) {
		echo elgg_view("messages/{$register}/list", array('object' => $list));
	}
}
