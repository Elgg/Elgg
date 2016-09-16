<?php
/**
 * Elgg user display
 *
 * @uses $vars['entity'] ElggUser entity
 * @uses $vars['size']   Size of the icon
 * @uses $vars['title']  Optional override for the title
 */

$entity = $vars['entity'];
$size = elgg_extract('size', $vars, 'tiny');

$icon = elgg_view_entity_icon($entity, $size, $vars);

$title = elgg_extract('title', $vars);
if (!$title) {
	$link_params = array(
		'href' => $entity->getUrl(),
		'text' => $entity->name,
	);
	$title = elgg_view('output/url', $link_params);
}

$params = array(
	'entity' => $entity,
	'title' => $title,
);
$list_body = elgg_view('user/elements/summary', $params);

echo elgg_view_image_block($icon, $list_body, $vars);
