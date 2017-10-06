<?php
/**
 * Page icon
 *
 * Uses a separate icon view due to dependency on annotation
 *
 * @package ElggPages
 *
 * @uses $vars['entity']
 * @uses $vars['annotation']
 */

/* @var $annotation ElggAnnotation */
$annotation = elgg_extract('annotation', $vars);
$entity = $annotation->getEntity();

// Get size
$size = elgg_extract('size', $vars);
$allowed_sizes = elgg_get_icon_sizes($entity->getType(), $entity->getSubtype());
if (!in_array($size, $allowed_sizes)) {
	$size = "medium";
}

$img = elgg_view('output/img', [
	'alt' => $entity->getDisplayName(),
	'scr' => $entity->getIconURL([
		'size' => $size,
	]),
]);

echo elgg_view('output/url', [
	'text' => $img,
	'href' => $annotation->getURL(),
]);
