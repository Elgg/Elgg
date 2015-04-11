<?php
/**
 * Friends/Private access upgrade page
 */


$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

$dbprefix = elgg_get_config('dbprefix');
$options = array(
	'type' => 'user',
	'count' => true
);
		
$count = elgg_get_entities($options);

echo elgg_view('admin/upgrades/view', array(
	'count' => $count,
	'action' => 'action/admin/upgrades/friendsprivateacls',
));

access_show_hidden_entities($access_status);
