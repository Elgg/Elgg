<?php
/**
 * Edit an existing collection
 */

$collection_id = elgg_extract('collection_id', $vars);
$collection = get_access_collection($collection_id);

if (!$collection || !$collection->canEdit()) {
	throw new \Elgg\EntityPermissionsException();
}

$user = $collection->getOwnerEntity();
if (!$user instanceof ElggUser) {
	throw new \Elgg\EntityNotFoundException();
}

elgg_set_page_owner_guid($user->guid);

$title = elgg_echo('friends:collections:edit');

elgg_push_breadcrumb($user->getDisplayName(), $user->getURL());
elgg_push_breadcrumb(elgg_echo('friends'), "friends/{$user->username}");
elgg_push_breadcrumb(elgg_echo('friends:collections'), "friends/collections/owner/{$user->username}");
elgg_push_breadcrumb($collection->name, $collection->getURL());

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
	'content' => $content,
	'show_owner_block_menu' => false,
]);

echo elgg_view_page($title, $body);
