<?php
/**
 * Show a list of friendship request pending approval
 *
 * @since 3.2
 */

$user = elgg_get_page_owner_entity();

// build page elements
$title = elgg_echo('friends:request:pending');

$content = elgg_list_relationships([
	'relationship_guid' => $user->guid,
	'relationship' => 'friendrequest',
	'inverse_relationship' => true,
	'type' => 'user',
	'no_results' => elgg_echo('friends:request:pending:none'),
]);

// build page
$body = elgg_view_layout('default', [
	'title' => $title,
	'content' => $content,
	'filter_id' => 'friends',
	'filter_value' => 'pending',
]);

// draw page
echo elgg_view_page($title, $body);
