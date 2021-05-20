<?php
/**
 * Edit an existing collection
 */

use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Exceptions\Http\EntityPermissionsException;

$collection_id = elgg_extract('collection_id', $vars);
$collection = get_access_collection($collection_id);

if (!$collection || !$collection->canEdit()) {
	throw new EntityPermissionsException();
}

$user = $collection->getOwnerEntity();
if (!$user instanceof ElggUser) {
	throw new EntityNotFoundException();
}

elgg_set_page_owner_guid($user->guid);

elgg_push_breadcrumb($user->getDisplayName(), $user->getURL());
elgg_push_breadcrumb(elgg_echo('friends'), elgg_generate_url('collection:friends:owner', ['username' => $user->username]));
elgg_push_breadcrumb(elgg_echo('friends:collections'), elgg_generate_url('collection:access_collection:friends:owner', ['username' => $user->username]));
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

echo elgg_view_page(elgg_echo('friends:collections:edit'), [
	'content' => elgg_view_form($form_name, [], $form_vars),
	'show_owner_block_menu' => false,
	'filter_id' => 'friends_collections/edit',
]);
