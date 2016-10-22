<?php

/**
 * Object summary
 * Passing an 'icon' with the variables will wrap the listing in an image block. In that case,
 * variables not listed in @uses (e.g. image_alt) will be passed to the image block.
 *
 * @uses $vars['entity']    ElggEntity
 * @uses $vars['title']     Title link (optional) false = no title, '' = default
 * @uses $vars['metadata']  HTML for entity menu and metadata (optional)
 * @uses $vars['show_menu'] Display entity menu
 * @uses $vars['subtitle']  HTML for the subtitle (optional)
 * @uses $vars['status']    HTML for the status bar
 * @uses $vars['access']    HTML for the access level
 * @uses $vars['by_line']   HTML for the byline
 * @uses $vars['responses_link']   HTML for the byline
 * @uses $vars['tags']      HTML for the tags (default is tags on entity, pass false for no tags)
 * @uses $vars['content']   HTML for the entity content (optional)
 * @uses $vars['icon']      Object icon. If set, the listing will be wrapped with an image block
 * @uses $vars['class']     Class selector for the image block
 * @uses $vars['image_block_vars'] Attributes for the image block wrapper
 */
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	throw new RuntimeException("object/elements/summary expects an ElggEntity in \$vars['entity']");
}

$title = elgg_view('object/elements/summary/title', $vars);
$subtitle = elgg_view('object/elements/summary/subtitle', $vars);
$tags = elgg_view('object/elements/summary/tags', $vars);
$metadata = elgg_view('object/elements/summary/metadata', $vars);
$extensions = elgg_view('object/summary/extend', $vars);
$content = elgg_view('object/elements/summary/content', $vars);
$content = elgg_view('object/elements/summary/menu', $vars);

$summary = $menu . $title . $subtitle . $metadata . $tags .$extensions . $content . $menu;

$icon = elgg_extract('icon', $vars);
if (!isset($icon)) {
	$icon_size = elgg_extract('icon_size', $vars, 'small');
	$icon = elgg_view_entity_icon($entity, $icon_size);
}

if (isset($icon)) {
	$params = (array) elgg_extract('image_block_vars', $vars, []);
	$class = elgg_extract_class($params);
	$class = elgg_extract_class($vars, $class);
	$params['class'] = $class;
	$params['data-guid'] = $entity->guid;

	echo elgg_view_image_block($icon, $summary, $params);
} else {
	echo $summary;
}
