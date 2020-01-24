<?php
/**
 * Show a list of sent friendship request
 *
 * @since 3.2
 */

$content = elgg_list_relationships([
	'relationship_guid' => elgg_get_page_owner_guid(),
	'relationship' => 'friendrequest',
	'type' => 'user',
	'no_results' => elgg_echo('friends:request:sent:none'),
]);

// draw page
echo elgg_view_page(elgg_echo('friends:request:sent'), [
	'content' => $content,
	'filter_id' => 'friends',
	'filter_value' => 'sent',
]);
