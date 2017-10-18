<?php

/**
 * List members of a collection
 *
 * @uses $vars['collection'] Access collection
 */
$collection = elgg_extract('collection', $vars);
if (!$collection instanceof ElggAccessCollection) {
	return;
}

$offset_key = "col_{$collection->id}";
$offset = get_input($offset_key, 0);
$limit = max(20, elgg_get_config('default_limit'));

$members = $collection->getMembers([
	'limit' => $limit,
	'offset' => $offset,
]);

if ($members) {
	foreach ($members as $member) {
		// We set volatile data, so that we can use the default user listing
		// and modify the entity menu
		$member->setVolatileData('friends:collection', $collection);
	}
}

$count = $collection->getMembers([
	'count' => true,
]);

echo elgg_view_entity_list($members, [
	'count' => $count,
	'limit' => $limit,
	'offset' => $offset,
	'offset_key' => $offset_key,
	'collection' => $collection,
	'no_results' => elgg_echo('friends:collection:members:no_results'),
]);
