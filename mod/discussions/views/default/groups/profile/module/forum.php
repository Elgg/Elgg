<?php
/**
 * Latest forum posts
 */

$params = [
	'entity_type' => 'object',
	'entity_subtype' => 'discussion',
	'no_results' => elgg_echo('discussion:none'),
];
$params = $params + $vars;

echo elgg_view('groups/profile/module', $params);
