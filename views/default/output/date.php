<?php
/**
 * Date
 * Displays a properly formatted date
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['value'] A UNIX epoch timestamp
 *
 */

if ($vars['value'] > 86400) {
	echo date("F j, Y",$vars['value']);
}