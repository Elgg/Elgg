<?php
/**
 * Resource to list discussions in the groups of the page owner
 *
 * @since 3.3
 */

$owner = elgg_get_page_owner_entity();

elgg_push_collection_breadcrumbs('object', 'discussion', $owner);

echo elgg_view_page(elgg_echo('collection:object:discussion:my_groups'), [
	'content' => elgg_view('discussion/listing/my_groups', [
		'entity' => $owner,
	]),
	'filter_value' => $owner->guid === elgg_get_logged_in_user_guid() ? 'my_groups' : 'none',
]);
