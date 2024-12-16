<?php
/**
 * Edit an existing collection
 */

use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Exceptions\Http\EntityPermissionsException;

$collection_id = (int) elgg_extract('collection_id', $vars);
$collection = elgg_get_access_collection($collection_id);

if (!$collection || !$collection->canEdit()) {
	// @todo replace this with a ForbiddenException in 7.0
	throw new EntityPermissionsException();
}

$user = $collection->getOwnerEntity();
if (!$user instanceof \ElggUser) {
	throw new EntityNotFoundException();
}

elgg_set_page_owner_guid($user->guid);

elgg_push_breadcrumb(elgg_echo('friends:collections'), elgg_generate_url('collection:access_collection:friends:owner', ['username' => $user->username]));
elgg_push_breadcrumb($collection->getDisplayName(), $collection->getURL());

echo elgg_view_page(elgg_echo('friends:collections:edit'), [
	'content' => elgg_view_form('friends/collections/edit', ['sticky_enabled' => true], [
		'collection_id' => $collection->id,
		'collection_name' => $collection->name,
	]),
	'filter_id' => 'friends_collections/edit',
]);
