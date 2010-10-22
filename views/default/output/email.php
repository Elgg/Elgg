<?php
/**
 * Elgg email output
 * Displays an email address that was entered using an email input field
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] The email address to display
 *
 */

if (!empty($vars['value'])) {
	echo "<a href=\"mailto:" . $vars['value'] . "\">". htmlentities($vars['value'], ENT_QUOTES, 'UTF-8') ."</a>";
}