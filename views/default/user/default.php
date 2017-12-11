<?php
/**
 * Elgg user display
 *
 * @uses $vars['entity'] ElggUser entity
 * @uses $vars['size']   Size of the icon
 * @uses $vars['title']  Optional override for the title
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof ElggUser)) {
	return;
}

$size = elgg_extract('size', $vars, 'small');
$icon = elgg_view_entity_icon($entity, $size, $vars);

if (elgg_get_context() == 'gallery') {
	echo $icon;
	return;
}
	
$title = elgg_extract('title', $vars);
if (!$title) {
	$title = elgg_view('output/url', [
		'href' => $entity->getUrl(),
		'text' => $entity->getDisplayName(),
	]);
}

if ($entity->isBanned()) {
	$params = [
		'entity' => $entity,
		'title' => $title,
		'subtitle' => elgg_echo('banned'),
	];
} else {
	$subtitle = '';
	$location = $entity->location;
	if (is_string($location) && $location !== '') {
		$location = elgg_view_icon('map-marker') . ' ' . $location;
		$subtitle .= elgg_format_element('div', [], $location);
	}
	
	$subtitle .= elgg_format_element('div', [], $entity->briefdescription);
	
	$params = [
		'entity' => $entity,
		'title' => $title,
		'subtitle' => $subtitle,
		'content' => elgg_view('user/status', ['entity' => $entity]),
	];
}

$params = $params + $vars;

$list_body = elgg_view('user/elements/summary', $params);

echo elgg_view_image_block($icon, $list_body);
