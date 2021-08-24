<?php

$user = elgg_get_page_owner_entity();

elgg_push_breadcrumb(elgg_echo('groups'), elgg_generate_url('collection:group:group:all'));

$content = elgg_call(ELGG_IGNORE_ACCESS, function() use ($user) {
	return elgg_list_relationships([
		'relationship' => 'invited',
		'relationship_guid' => $user->guid,
		'inverse_relationship' => true,
		'no_results' => elgg_echo('groups:invitations:none'),
	]);
});

// draw page
echo elgg_view_page(elgg_echo('groups:invitations'), [
	'content' => $content,
	'filter_id' => 'groups/invitations',
	'filter_value' => 'invitations',
]);
