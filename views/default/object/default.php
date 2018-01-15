<?php
/**
 * ElggObject default view.
 *
 * @warning This view may be used for other ElggEntity objects
 *
 * @package Elgg
 * @subpackage Core
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$icon = elgg_view_entity_icon($entity, 'small');

$title = $entity->title;
if (!$title) {
	$title = elgg_extract('entity', $vars)->name;
}
if (!$title) {
	$title = get_class($entity);
}

$metadata = '';
if ($entity instanceof ElggObject) {
	$metadata = elgg_view('navigation/menu/metadata', $vars);
}

$owner_link = '';
$owner = $entity->getOwnerEntity();
if ($owner) {
	$owner_link = elgg_view('output/url', [
		'href' => $owner->getURL(),
		'text' => $owner->name,
		'is_trusted' => true,
	]);
}

$date = elgg_view_friendly_time($entity->getTimeCreated());

$subtitle = "$owner_link $date";

$params = [
	'entity' => elgg_extract('entity', $vars),
	'title' => $title,
	'metadata' => $metadata,
	'subtitle' => $subtitle,
];
$params = $params + $vars;
$body = elgg_view('object/elements/summary', $params);

echo elgg_view_image_block($icon, $body, $vars);
