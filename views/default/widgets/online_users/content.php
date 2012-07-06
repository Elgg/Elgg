<?php
/**
 * Online users widget
 */

$count = find_active_users(600, 10, 0, true);
$objects = find_active_users(600, 10);

if ($objects) {
	echo elgg_view_entity_list($objects, array(
		'count' => $count,
		'limit' => 10,
		'pagination' => false,
	));
}
