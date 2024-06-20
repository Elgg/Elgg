<?php
/**
 * Ultimate fallback view for an entity in the trash
 *
 * @uses $vars['entity']           The entity to show
 * @uses $vars['title']            Title link (optional) false = no title, '' = default
 * @uses $vars['metadata']         HTML for entity menu and metadata (optional)
 * @uses $vars['subtitle']         HTML for the subtitle (optional)
 * @uses $vars['class']            Class selector for the image block
 * @uses $vars['image_block_vars'] Attributes for the image block wrapper*/

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

// build image block content
$summary = '';
$summary .= elgg_view('trash/elements/metadata', $vars);
$summary .= elgg_view('trash/elements/title', $vars);
$summary .= elgg_view('trash/elements/subtitle', $vars);
$summary .= elgg_view('trash/elements/content', $vars);

// image block params
$params = (array) elgg_extract('image_block_vars', $vars, []);
$class = elgg_extract_class($params);
$class = elgg_extract_class($vars, $class);
$params['class'] = $class;
$params['data-guid'] = $entity->guid;

echo elgg_view_image_block('', $summary, $params);
