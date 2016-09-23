<?php

/**
 * Renders a list of groups ordered alphabetically
 */
$dbprefix = elgg_get_config('dbprefix');
echo elgg_list_entities(array(
	'type' => 'group',
	'joins' => ["JOIN {$dbprefix}groups_entity ge ON e.guid = ge.guid"],
	'order_by' => 'ge.name',
	'full_view' => false,
	'no_results' => elgg_echo('groups:none'),
	'distinct' => false,
));
