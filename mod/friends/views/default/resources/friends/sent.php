<?php
/**
 * Show a list of sent friendship request
 *
 * @since 3.2
 */

$user = elgg_get_page_owner_entity();

// build page elements
$title = elgg_echo('friends:request:sent');

$content = elgg_list_relationships([
	'relationship_guid' => $user->guid,
	'relationship' => 'friendrequest',
	'type' => 'user',
	'no_results' => elgg_echo('friends:request:sent:none'),
]);

// build page
$body = elgg_view_layout('default', [
	'title' => $title,
	'content' => $content,
	'filter_id' => 'friends',
	'filter_value' => 'sent',
]);

// draw page
echo elgg_view_page($title, $body);
