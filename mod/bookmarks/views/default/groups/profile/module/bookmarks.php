<?php
/**
 * List most recent bookmarks on group profile page
 */

$params = [
	'entity_type' => 'object',
	'entity_subtype' => 'bookmarks',
	'no_results' => elgg_echo('bookmarks:none'),
];
$params = $params + $vars;

echo elgg_view('groups/profile/module', $params);
