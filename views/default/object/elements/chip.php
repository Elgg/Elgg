<?php
/**
 * Object chip
 * Renders a simple image block with an icon and title
 *
 * @uses $vars['entity']    ElggEntity
 * @uses $vars['title']     Title link (optional) false = no title, '' = default
 * @uses $vars['icon']      Object icon. If set, the listing will be wrapped with an image block
 * @uses $vars['class']     Class selector for the image block
 * @uses $vars['image_block_vars'] Attributes for the image block wrapper
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$title = elgg_extract('title', $vars, '');
if ($title === '') {
	$title = elgg_view('object/elements/summary/title', $vars);
}

$icon = elgg_extract('icon', $vars);
if (!isset($icon)) {
	$icon = elgg_view_entity_icon($entity, 'small');
}

$params = (array) elgg_extract('image_block_vars', $vars, []);
$class = elgg_extract_class($params, 'elgg-chip');
$class = elgg_extract_class($vars, $class);
$params['class'] = $class;
$params['data-guid'] = $entity->guid;

echo elgg_view_image_block($icon, $title, $params);
