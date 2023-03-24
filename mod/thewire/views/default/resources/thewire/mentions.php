<?php
/**
 * Show Wire posts which mention the user
 */

$user = elgg_get_page_owner_entity();

elgg_push_collection_breadcrumbs('object', 'thewire', $user);

echo elgg_view_page(elgg_echo('collection:object:thewire:mentions', [$user->username]), [
	'content' => elgg_view('thewire/listing/mentions', [
		'entity' => $user,
	]),
	'sidebar' => elgg_view('thewire/sidebar'),
	'filter_value' => 'mentions',
]);
