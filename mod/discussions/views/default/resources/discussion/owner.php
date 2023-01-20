<?php

$page_owner = elgg_get_page_owner_entity();

elgg_push_collection_breadcrumbs('object', 'discussion', $page_owner);

elgg_register_title_button('add', 'object', 'discussion');

echo elgg_view_page(elgg_echo('collection:object:discussion'), [
	'content' => elgg_view('discussion/listing/owner', [
		'entity' => $page_owner,
	]),
	'filter_value' => $page_owner->guid === elgg_get_logged_in_user_guid() ? 'mine' : 'none',
]);
