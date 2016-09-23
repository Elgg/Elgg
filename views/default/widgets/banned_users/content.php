<?php
/**
 * Banned users admin widget
 */
$widget = elgg_extract('entity', $vars);

$num_display = sanitize_int($widget->num_display, false);
// set default value for display number
if (!$num_display) {
	$num_display = 5;
}

$db_prefix = elgg_get_config('dbprefix');

echo elgg_list_entities([
	'type' => 'user',
	'subtype'=> null,
	'wheres' => ["ue.banned = 'yes'"],
	'joins' => ["JOIN {$db_prefix}users_entity ue on ue.guid = e.guid"],
	'pagination' => false,
	'limit' => $num_display,
]);
