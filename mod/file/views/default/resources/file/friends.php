<?php
/**
 * Friends Files
 */

$owner = elgg_get_page_owner_entity();

elgg_push_collection_breadcrumbs('object', 'file', $owner, true);

elgg_register_title_button('add', 'object', 'file');

$params = $vars;
$params['entity'] = $owner;

echo elgg_view_page(elgg_echo('collection:object:file:friends'), [
	'filter_value' => 'friends',
	'content' => elgg_view('file/listing/friends', $params),
]);
