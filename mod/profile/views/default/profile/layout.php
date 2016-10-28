<?php
/**
 * Profile layout
 */

$entity = elgg_extract('entity', $vars);

echo elgg_view('profile/wrapper');
echo elgg_view_layout('widgets', [
	'num_columns' => 2,
	'owner_guid' => $entity->guid,
]);
