<?php
/**
 * View a collection
 */

use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Exceptions\Http\EntityPermissionsException;

$collection_id = (int) elgg_extract('collection_id', $vars);

$collection = elgg_get_access_collection($collection_id);
if (!$collection instanceof \ElggAccessCollection || !$collection->canEdit()) {
	// We don't want to leak friendship/collection information
	// @todo replace this with a ForbiddenException in 7.0
	throw new EntityPermissionsException();
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
