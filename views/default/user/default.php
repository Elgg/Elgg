<?php
/**
 * Elgg user display
 *
 * @uses $vars['entity'] ElggUser entity
 * @uses $vars['size']   Size of the icon
 * @uses $vars['title']  Optional override for the title
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggUser) {
	return;
}

$size = elgg_extract('size', $vars, 'small');

if (elgg_get_context() == 'gallery') {
	echo elgg_view_entity_icon($entity, $size, $vars);
	return;
}
	
$title = elgg_extract('title', $vars) ?: elgg_view_entity_url($entity);

$params = [
	'entity' => $entity,
	'title' => $title,
	'icon_entity' => $entity,
	'icon_size' => $size,
	'tags' => false,
];
$params = $params + $vars;

echo elgg_view('user/elements/summary', $params);
