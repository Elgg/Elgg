<?php
/**
 * Elgg friends of page
 */

// needed for correct registration of menu items
elgg_set_context('friends');

$owner = elgg_get_page_owner_entity();
if (!$owner instanceof ElggUser) {
	throw new \Elgg\EntityNotFoundException;
}

$title = elgg_echo("friends:of:owned", [$owner->getDisplayName()]);

$content = elgg_list_entities([
	'relationship' => 'friend',
	'relationship_guid' => $owner->getGUID(),
	'inverse_relationship' => true,
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
]);
