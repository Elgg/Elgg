<?php
/**
 * Group file module
 */

$params = [
	'entity_type' => 'object',
	'entity_subtype' => 'file',
	'no_results' => elgg_echo('file:none'),
];
$params = $params + $vars;

echo elgg_view('groups/profile/module', $params);
