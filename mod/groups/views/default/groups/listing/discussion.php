<?php

/**
 * Renders a list of latest group discussions
 */
// Get only the discussions that have been created inside a group
$dbprefix = elgg_get_config('dbprefix');
echo elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'discussion',
	'order_by' => 'e.last_action desc',
	'limit' => 40,
	'full_view' => false,
	'no_results' => elgg_echo('discussion:none'),
	'joins' => array("JOIN {$dbprefix}entities ce ON ce.guid = e.container_guid"),
	'wheres' => array('ce.type = "group"'),
	'distinct' => false,
	'preload_containers' => true,
));
