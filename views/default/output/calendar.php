<?php
/**
 * Elgg calendar output
 * Displays a calendar output field
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] The current value, if any
 *
 */

if (is_int($vars['value'])) {
	echo date("F j, Y", $vars['value']);
} else {
	echo htmlentities($vars['value'], ENT_QUOTES, 'UTF-8');
}