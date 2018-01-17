<?php
/**
 * List a user's friends' pages
 */

$owner = elgg_get_page_owner_entity();
if (!$owner instanceof ElggUser) {
	throw new \Elgg\EntityNotFoundException();
}

elgg_push_breadcrumb(elgg_echo('pages'), 'pages/all');
elgg_push_breadcrumb($owner->getDisplayName(), "pages/owner/{$owner->username}");
elgg_push_breadcrumb(elgg_echo('friends'));

elgg_register_title_button('pages', 'add', 'object', 'page');

$title = elgg_echo('collection:object:page:friends');

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'page',
	'metadata_name_value_pairs' => [
		'parent_guid' => 0,
	],
	'full_view' => false,
	'relationship' => 'friend',
	'relationship_guid' => $owner->guid,
	'relationship_join_on' => 'container_guid',
	'no_results' => elgg_echo('pages:none'),
	'preload_owners' => true,
	'preload_containers' => true,
]);

$body = elgg_view_layout('content', [
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
]);

echo elgg_view_page($title, $body);
