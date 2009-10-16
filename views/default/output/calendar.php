<?php
/**
 * Elgg calendar output
 * Displays a calendar output field
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['value'] The current value, if any
 *
 */

if (is_int($vars['value'])) {
	echo date("F j, Y", $vars['value']);
} else {
	echo htmlentities($vars['value'], ENT_QUOTES, 'UTF-8');
}