<?php
/**
 * Relationship summary
 * Passing an 'icon' with the variables will wrap the listing in an image block. In that case,
 * variables not listed in @uses (e.g. image_alt) will be passed to the image block.
 *
 * @uses $vars['relationship']     ElggRelationship
 * @uses $vars['title']            Title link (optional) false = no title, '' = default
 * @uses $vars['metadata']         HTML for relationship menu and metadata (optional)
 * @uses $vars['subtitle']         HTML for the subtitle (optional)
 * @uses $vars['content']          HTML for the relationship content (optional)
 * @uses $vars['icon']             Object icon. If set, the listing will be wrapped with an image block
 * @uses $vars['class']            Class selector for the image block
 * @uses $vars['image_block_vars'] Attributes for the image block wrapper
 */

$relationship = elgg_extract('relationship', $vars);
if (!$relationship instanceof ElggRelationship) {
	return;
}

$entity_one = get_entity($relationship->guid_one);
$entity_two = get_entity($relationship->guid_two);
if (empty($entity_one) || empty($entity_two)) {
	return;
}

// build image block content
$summary = '';
$summary .= elgg_view('relationship/elements/summary/metadata', $vars);
$summary .= elgg_view('relationship/elements/summary/title', $vars);
$summary .= elgg_view('relationship/elements/summary/subtitle', $vars);
$summary .= elgg_view('relationship/elements/summary/content', $vars);

// image block image
$icon = elgg_view('relationship/elements/summary/icon', $vars);

// image block params
$params = (array) elgg_extract('image_block_vars', $vars, []);
$class = elgg_extract_class($params);
$class = elgg_extract_class($vars, $class);
$params['class'] = $class;
$params['data-id'] = $relationship->id;

echo elgg_view_image_block($icon, $summary, $params);
