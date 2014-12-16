<?php
/**
 * Banned users admin widget
 */

$num_display = sanitize_int($vars['entity']->num_display, false);
// set default value for display number
if (!$num_display) {
	$num_display = 5;
}

$db_prefix = elgg_get_config('dbprefix');

echo elgg_list_entities(array(
	'type' => 'user',
	'subtype'=> null,
	'wheres' => array("ue.banned = 'yes'"),
	'joins' => array("JOIN {$db_prefix}users_entity ue on ue.guid = e.guid"),
	'full_view' => false,
	'pagination' => false,
	'limit' => $num_display
));
