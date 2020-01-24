<?php
/**
 * Annotation summary
 * Passing an 'icon' with the variables will wrap the listing in an image block. In that case,
 * variables not listed in @uses (e.g. image_alt) will be passed to the image block.
 *
 * @uses $vars['annotation']       ElggAnnotation
 * @uses $vars['title']            Title link (optional) false = no title, '' = default
 * @uses $vars['metadata']         HTML for annotation menu and metadata (optional)
 * @uses $vars['subtitle']         HTML for the subtitle (optional)
 * @uses $vars['content']          HTML for the annotation content (optional)
 * @uses $vars['icon']             Object icon. If set, the listing will be wrapped with an image block
 * @uses $vars['class']            Class selector for the image block
 * @uses $vars['image_block_vars'] Attributes for the image block wrapper
 */

$annotation = elgg_extract('annotation', $vars);
if (!$annotation instanceof ElggAnnotation) {
	return;
}

$entity = $annotation->getEntity();
if (!$entity instanceof ElggEntity) {
	return;
}

$owner= $annotation->getOwnerEntity();
if (!$owner instanceof ElggEntity) {
	return;
}

// build image block content
$summary = '';
$summary .= elgg_view('annotation/elements/summary/metadata', $vars);
$summary .= elgg_view('annotation/elements/summary/title', $vars);
$summary .= elgg_view('annotation/elements/summary/subtitle', $vars);
$summary .= elgg_view('annotation/elements/summary/content', $vars);

// image block image
$icon = elgg_view('annotation/elements/summary/icon', $vars);

// image block params
$params = (array) elgg_extract('image_block_vars', $vars, []);
$class = elgg_extract_class($params);
$class = elgg_extract_class($vars, $class);
$params['class'] = $class;
$params['data-id'] = $annotation->id;

echo elgg_view_image_block($icon, $summary, $params);
