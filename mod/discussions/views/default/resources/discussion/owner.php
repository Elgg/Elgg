<?php

use Elgg\Exceptions\Http\EntityNotFoundException;

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner instanceof ElggUser) {
	throw new EntityNotFoundException();
}

elgg_push_collection_breadcrumbs('object', 'discussion', $page_owner);

elgg_register_title_button('discussion', 'add', 'object', 'discussion');

echo elgg_view_page(elgg_echo('collection:object:discussion'), [
	'content' => elgg_view('discussion/listing/owner', [
		'entity' => $page_owner,
	]),
	'filter_value' => $page_owner->guid === elgg_get_logged_in_user_guid() ? 'mine' : 'none',
]);
