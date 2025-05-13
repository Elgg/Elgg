<?php
/**
 * Elgg friends page
 */

$owner = elgg_get_page_owner_entity();

$title = elgg_echo('friends:owned', [$owner->getDisplayName()]);

$content = elgg_list_entities([
	'relationship' => 'friend',
	'relationship_guid' => $owner->guid,
	'inverse_relationship' => false,
	'type' => 'user',
	'no_results' => elgg_echo('friends:none'),
	'sort_by' => [
		'property' => 'name',
		'direction' => 'ASC',
	],
]);

echo elgg_view_page($title, [
	'content' => $content,
	'filter_id' => 'friends',
	'filter_value' => 'friends',
]);
