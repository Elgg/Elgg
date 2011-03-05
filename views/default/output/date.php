<?php
/**
 * Date
 * Displays a properly formatted date
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] A UNIX epoch timestamp
 *
 */

if ($vars['value'] > 86400) {
	echo date("n/d/Y", $vars['value']);
}