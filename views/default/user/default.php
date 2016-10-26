<?php

/**
 * Elgg user display
 *
 * @uses $vars['entity'] ElggUser entity
 */
$entity = elgg_extract('entity', $vars);

$status = '';
if ($entity->isBanned()) {
	$status = elgg_echo('banned');
}

$tags = '';
$location = $entity->location;
if (is_string($location) && $location !== '') {
	$tags = elgg_view('output/tags', [
		'icon' => 'map-pin',
		'value' => $location,
	]);
}

$subtitle = '';
if ($entity->briefdescription) {
	$subtitle = elgg_view('output/text', ['value' => $entity->briefdescription]);
}

echo elgg_view('object/default', [
	'by_line' => false,
	'content' => false,
	'subtitle' => $subtitle,
	'body' => false,
	'status' => $status,
	'access' => false,
	'tags' => $tags,
	'entity' => $entity,
]);

