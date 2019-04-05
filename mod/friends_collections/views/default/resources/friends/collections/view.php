<?php
/**
 * View a collection
 */

$collection_id = elgg_extract('collection_id', $vars);
$collection = get_access_collection($collection_id);

if (!$collection || !$collection->canEdit()) {
	// We don't want to leak friendship/collection information
	throw new \Elgg\EntityPermissionsException();
}

$user = $collection->getOwnerEntity();
if (!$user instanceof ElggUser) {
	throw new \Elgg\EntityNotFoundException();
}

elgg_set_page_owner_guid($user->guid);

$title = $collection->getDisplayName();

elgg_push_breadcrumb($user->getDisplayName(), $user->getURL());
elgg_push_breadcrumb(elgg_echo('friends'), "friends/{$user->username}");
elgg_push_breadcrumb(elgg_echo('friends:collections'), "friends/collections/owner/{$user->username}");

$content = elgg_view('collections/collection', [
	'full_view' => true,
	'item' => $collection,
]);

$body = elgg_view_layout('one_sidebar', [
	'title' => $title,
	'content' => $content,
	'show_owner_block_menu' => false,
]);

echo elgg_view_page($title, $body);
