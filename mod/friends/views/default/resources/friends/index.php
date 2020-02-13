<?php
/**
 * Elgg friends page
 */

use Elgg\Exceptions\Http\EntityNotFoundException;

$owner = elgg_get_page_owner_entity();
if (!$owner instanceof ElggUser) {
	throw new EntityNotFoundException;
}

$title = elgg_echo('friends:owned', [$owner->getDisplayName()]);

$content = elgg_list_entities([
	'relationship' => 'friend',
	'relationship_guid' => $owner->guid,
	'inverse_relationship' => false,
	'type' => 'user',
	'order_by_metadata' => [
		[
			'name' => 'name',
			'direction' => 'ASC',
		],
	],
	'full_view' => false,
	'no_results' => elgg_echo('friends:none'),
]);

echo elgg_view_page($title, [
	'content' => $content,
	'filter_id' => 'friends',
	'filter_value' => 'friends',
]);
