<?php
/**
 * Elgg friends picker
 *
 * @uses $vars['name'] Name of the returned data array (default "friend")
 */

if (!isset($vars['name'])) {
	$vars['name'] = 'friend';
}

$vars['only_friends'] = true;

echo elgg_view('input/userpicker', $vars);
