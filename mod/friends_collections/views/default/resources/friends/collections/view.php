<?php
/**
 * View a collection
 */

use Elgg\Exceptions\Http\EntityNotFoundException;

$collection_id = (int) elgg_extract('collection_id', $vars);

$collection = elgg_get_access_collection($collection_id);
if (!$collection instanceof \ElggAccessCollection || !$collection->canEdit()) {
	// We don't want to leak friendship/collection information
	throw new \Elgg\Exceptions\Http\ForbiddenException();
}

$user = $collection->getOwnerEntity();
if (!$user instanceof \ElggUser) {
	throw new EntityNotFoundException();
}

elgg_set_page_owner_guid($user->guid);

elgg_push_breadcrumb(elgg_echo('friends:collections'), elgg_generate_url('collection:access_collection:friends:owner', ['username' => $user->username]));

echo elgg_view_page($collection->getDisplayName(), [
	'content' => elgg_view('collections/collection', [
		'full_view' => true,
		'item' => $collection,
	]),
	'filter_id' => 'friends_collections/view',
]);
