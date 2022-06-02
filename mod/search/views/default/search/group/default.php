<?php
/**
 * Default view for a group returned in a search
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggGroup) {
	return;
}

$params = [
	'byline' => false,
	'time' => false,
];
$params = $params + $vars;

echo elgg_view('search/entity/default', $params);
