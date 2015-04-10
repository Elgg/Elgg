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

$annotation = elgg_extract('annotation', $vars);
if (!$annotation instanceof \ElggAnnotation) {
	return;
}

$entity = $annotation->getEntity();
if (!$entity) {
	return;
}

$vars['entity'] = $entity;
$vars['href'] = $annotation->getURL();

echo elgg_view('icon/default', $vars);
