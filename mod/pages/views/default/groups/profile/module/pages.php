<?php
/**
 * Group pages
 */

$group = elgg_extract('entity', $vars);
if (!$group instanceof ElggGroup) {
	return;
}

// need to draw our own content because we only want top pages
elgg_push_context('widgets');

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'page',
	'container_guid' => $group->guid,
	'metadata_name_value_pairs' => [
		'parent_guid' => 0,
	],
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
	'no_results' => elgg_echo('pages:none'),
]);

elgg_pop_context();

$params = [
	'entity_type' => 'object',
	'entity_subtype' => 'page',
	'content' => $content,
];
$params = $params + $vars;

echo elgg_view('groups/profile/module', $params);
