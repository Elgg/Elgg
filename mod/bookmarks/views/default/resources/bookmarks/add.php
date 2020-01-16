<?php
/**
 * Add bookmark page
 */

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid);

$page_owner = get_entity($guid);

if (!$page_owner->canWriteToContainer(0, 'object', 'bookmarks')) {
	throw new \Elgg\EntityPermissionsException();
}

elgg_push_collection_breadcrumbs('object', 'bookmarks', $page_owner);

$vars = bookmarks_prepare_form_vars();


echo elgg_view_page(elgg_echo('add:object:bookmarks'), [
	'filter_id' => 'bookmarks/edit',
	'content' => elgg_view_form('bookmarks/save', ['prevent_double_submit' => true], $vars),
]);
