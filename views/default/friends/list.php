<?php
/**
 * Elgg friends list
 * Lists a user's friends
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['friends'] The array of ElggUser objects
 */

if (is_array($vars['friends']) && sizeof($vars['friends']) > 0) {
	foreach($vars['friends'] as $friend) {
		echo elgg_view_entity($friend);
	}
}