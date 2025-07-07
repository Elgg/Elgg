<?php
/**
 * Group blog module
 */

$params = [
	'entity_type' => 'object',
	'entity_subtype' => 'blog',
];
$params = $params + $vars;

echo elgg_view('groups/profile/module', $params);
