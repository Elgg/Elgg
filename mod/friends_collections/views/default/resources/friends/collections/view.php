<?php
/**
 * View a collection
 */

elgg_gatekeeper();

$collection_id = elgg_extract('collection_id', $vars);
$collection = get_access_collection($collection_id);

if (!$collection || !$collection->canEdit()) {
	// We don't want to leak friendship/collection information
	forward('', '403');
}

$user = get_entity($collection->owner_guid);
if (!$user) {
	forward('', '404');
}

elgg_set_page_owner_guid($user->guid);

$title = $collection->name;

elgg_push_breadcrumb($user->getDisplayName(), $user->getURL());
elgg_push_breadcrumb(elgg_echo('friends'), 'friends');
elgg_push_breadcrumb(elgg_echo('friends:collections'), 'collections');
elgg_push_breadcrumb($title);

$content = elgg_view('collections/collection', [
	'full_view' => true,
	'item' => $collection,
]);

$body = elgg_view_layout('one_sidebar', [
	'title' => $title,
	'content' => $content
]);

echo elgg_view_page($title, $body);
