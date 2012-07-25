<?php
/**
 * Elgg email output
 * Displays an email address that was entered using an email input field
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] The email address to display
 * @uses $vars['class'] Any class or classes (separated by spaces) to add to the anchor tag
 *
 */

if (!empty($vars['class'])) $class = "class=\"{$vars['class']}\""; else $class = '';
if (!empty($vars['value'])) {
	echo "<a href=\"mailto:{$vars['value']}\" {$class}>". htmlspecialchars($vars['value'], ENT_QUOTES, 'UTF-8', false) ."</a>";
}