<?php
/**
 * Elgg user display
 *
 * @uses $vars['entity'] ElggUser entity
 * @uses $vars['size']   Size of the icon
 * @uses $vars['title']  Optional override for the title
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$size = elgg_extract('size', $vars, 'small');

$icon = elgg_view_entity_icon($entity, $size, $vars);

$title = elgg_extract('title', $vars);
if (!$title) {
	$title = elgg_view('output/url', [
		'href' => $entity->getUrl(),
		'text' => $entity->getDisplayName(),
	]);
}

$params = [
	'entity' => $entity,
	'title' => $title,
];
$list_body = elgg_view('user/elements/summary', $params);

echo elgg_view_image_block($icon, $list_body, $vars);
