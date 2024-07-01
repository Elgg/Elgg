<?php

$user = elgg_get_page_owner_entity();

elgg_push_collection_breadcrumbs('group', 'group');

$content = elgg_call(ELGG_IGNORE_ACCESS, function() use ($user) {
	return elgg_list_relationships([
		'type' => 'group',
		'relationship' => 'invited',
		'relationship_guid' => $user->guid,
		'inverse_relationship' => true,
		'no_results' => elgg_echo('groups:invitations:none'),
	]);
});

echo elgg_view_page(elgg_echo('groups:invitations'), [
	'content' => $content,
	'filter_id' => 'groups/invitations',
	'filter_value' => 'invitations',
]);
