<?php
/**
 * Elgg friends picker
 *
 * @uses $vars['values'] Array of user guids for already selected users or null
 * @uses $vars['limit'] Limit number of users (default 0 = no limit)
 * @uses $vars['name'] Name of the returned data array (default "friend")
 * @uses $vars['handler'] Name of page handler used to power search (default "livesearch")
 */

if (!isset($vars['name'])) {
	$vars['name'] = 'friend';
}

$vars['only_friends'] = true;

echo elgg_view('input/userpicker', $vars);
