<?php
/**
 * Show a list of friendship request pending approval
 *
 * @since 3.2
 */

$content = elgg_list_relationships([
	'relationship_guid' => elgg_get_page_owner_guid(),
	'relationship' => 'friendrequest',
	'inverse_relationship' => true,
	'type' => 'user',
	'no_results' => elgg_echo('friends:request:pending:none'),
]);

// draw page
echo elgg_view_page(elgg_echo('friends:request:pending'), [
	'content' => $content,
	'filter_id' => 'friends',
	'filter_value' => 'pending',
]);
