<?php
/**
 * Elgg show the users who liked the object
 *
 * @uses $vars['annotation'] The like annotation
 */

$annotation = elgg_extract('annotation', $vars);
if (!$annotation instanceof ElggAnnotation) {
	return;
}

$owner = $annotation->getOwnerEntity();
if (!$owner instanceof ElggEntity) {
	return;
}

$owner_link = elgg_view_entity_url($owner);

$params = [
	'title' => elgg_echo('likes:this', [$owner_link]),
	'content' => false,
];
$params = $params + $vars;
echo elgg_view('annotation/elements/summary', $params);
