<?php
/**
 * Latest forum posts
 */

$params = [
	'entity_type' => 'object',
	'entity_subtype' => 'discussion',
];
$params = $params + $vars;

echo elgg_view('groups/profile/module', $params);
