<?php
/**
 * Group file module
 */

$params = [
	'entity_type' => 'object',
	'entity_subtype' => 'file',
];
$params = $params + $vars;

echo elgg_view('groups/profile/module', $params);
