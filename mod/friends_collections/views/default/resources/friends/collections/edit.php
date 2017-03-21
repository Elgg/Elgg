<?php
/**
 * Edit an existing collection
 */

elgg_gatekeeper();

$collection_id = elgg_extract('collection_id', $vars);
$collection = get_access_collection($collection_id);

if (!$collection || !$collection->canEdit()) {
	forward('', '403');
}

$user = get_entity($collection->owner_guid);
if (!$user) {
	forward('', '404');
}

elgg_set_page_owner_guid($user->guid);

$title = elgg_echo('friends:collections:edit');

elgg_push_breadcrumb($user->getDisplayName(), $user->getURL());
elgg_push_breadcrumb(elgg_echo('friends'), 'friends');
elgg_push_breadcrumb(elgg_echo('friends:collections'), 'collections');
elgg_push_breadcrumb($collection->name, $collection->getURL());
elgg_push_breadcrumb($title);

$form_name = 'friends/collections/edit';
$form_vars = [
	'collection_id' => $collection->id,
	'collection_name' => $collection->name,
];

if (elgg_is_sticky_form($form_name)) {
	$form_vars = elgg_get_sticky_values($form_name);
	elgg_clear_sticky_form($form_name);
}

$content = elgg_view_form($form_name, [], $form_vars);

$body = elgg_view_layout('one_sidebar', [
	'title' => $title,
	'content' => $content
]);

echo elgg_view_page($title, $body);
