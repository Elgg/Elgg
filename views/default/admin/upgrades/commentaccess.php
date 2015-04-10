<?php
/**
 * Comment access upgrade page
 */

// Upgrade also possible hidden comments. This feature gets run
// by an administrator so there's no need to ignore access.
$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

$dbprefix = elgg_get_config('dbprefix');
$options = array(
	'type' => 'object',
	'subtype' => 'comment',
	'joins' => array(
		"JOIN {$dbprefix}entities e2 ON e.container_guid = e2.guid"
	),
	'wheres' => array(
		"e.access_id != e2.access_id"
	),
	'count' => true
);
		
$count = elgg_get_entities($options);

echo elgg_view('admin/upgrades/view', array(
	'count' => $count,
	'action' => 'action/admin/upgrades/upgrade_comments_access',
));

access_show_hidden_entities($access_status);
