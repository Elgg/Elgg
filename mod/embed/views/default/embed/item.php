<?php
/**
 * Embeddable content list item view
 *
 * @uses $vars['entity'] ElggEntity object
 */

$entity = elgg_extract('entity', $vars);

$title = $entity->title;
if (!$title) {
	$title = $entity->name;
}

// different entity types have different title attribute names.
$title = isset($entity->name) ? $entity->name : $entity->title;
// don't let it be too long
$title = elgg_get_excerpt($title);

$subtitle = elgg_view('object/elements/imprint', [
	'entity' => $entity,
	'show_links' => false,
]);

$image = elgg_view_entity_icon($entity, 'small', ['link_class' => 'embed-insert']);

$params = [
	'title' => $title,
	'entity' => $entity,
	'subtitle' => $subtitle,
	'tags' => false,
	'icon' => $image,
];
echo elgg_view('object/elements/summary', $params);
