<?php
/**
 * Elgg friends page
 *
 * @package Elgg.Core
 * @subpackage Social.Friends
 */

$owner = elgg_get_page_owner_entity();
if (!$owner instanceof ElggUser) {
	throw new \Elgg\EntityNotFoundException;
}

$title = elgg_echo("friends:owned", [$owner->getDisplayName()]);

$dbprefix = elgg_get_config('dbprefix');
$options = [
	'relationship' => 'friend',
	'relationship_guid' => $owner->getGUID(),
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
];
$content = elgg_list_entities($options);

$params = [
	'content' => $content,
	'title' => $title,
];
$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
