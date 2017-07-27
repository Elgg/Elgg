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

$size = elgg_extract('size', $vars, 'tiny');
$icon = elgg_view_entity_icon($entity, $size, $vars);

if (elgg_get_context() == 'gallery') {
	echo $icon;
	return;
}
	
$title = elgg_extract('title', $vars);
if (!$title) {
	$link_params = [
		'href' => $entity->getUrl(),
		'text' => $entity->name,
	];

	$title = elgg_view('output/url', $link_params);
}

$metadata = '';
if (!elgg_in_context('owner_block') && !elgg_in_context('widgets') && !elgg_in_context('user_hover')) {
	$metadata = elgg_view_menu('entity', [
		'entity' => $entity,
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	]);
}

if ($entity->isBanned()) {
	$banned = elgg_echo('banned');
	$params = [
		'entity' => $entity,
		'title' => $title,
		'metadata' => $metadata,
	];
} else {
	$params = [
		'entity' => $entity,
		'title' => $title,
		'metadata' => $metadata,
		'subtitle' => $entity->briefdescription,
		'content' => elgg_view('user/status', ['entity' => $entity]),
	];
}

$list_body = elgg_view('user/elements/summary', $params);

echo elgg_view_image_block($icon, $list_body, $vars);
