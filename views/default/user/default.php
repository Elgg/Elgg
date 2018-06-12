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
	'icon_entity' => $entity,
	'icon_size' => $size,
	'tags' => false,
];

if ($entity->isBanned()) {
	$params['subtitle'] = elgg_echo('banned');
} else {
	$subtitle = '';
	$location = $entity->location;
	if (is_string($location) && $location !== '') {
		$location = elgg_view_icon('map-marker') . ' ' . $location;
		$subtitle .= elgg_format_element('div', [], $location);
	}
	
	$subtitle .= elgg_format_element('div', [], $entity->briefdescription);
	
	$params['subtitle'] = $subtitle;
	if (elgg_view_exists('user/status')) {
		$params['content'] = elgg_view('user/status', ['entity' => $entity]);
	}
}

$params = $params + $vars;

echo elgg_view('user/elements/summary', $params);
