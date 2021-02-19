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

$likes_string = elgg_echo('likes:this');


$params = [
	'title' => $owner_link . ' ' . $likes_string,
	'content' => false,
];
$params = $params + $vars;
echo elgg_view('annotation/elements/summary', $params);
