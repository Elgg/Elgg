<?php
/**
 * Group blog module
 */

$params = [
	'entity_type' => 'object',
	'entity_subtype' => 'blog',
	'no_results' => elgg_echo('blog:none'),
];
$params = $params + $vars;

echo elgg_view('groups/profile/module', $params);
