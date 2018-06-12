<?php
/**
 * Object full view header
 *
 * @uses $vars['icon']          HTML for the content icon
 * @uses $vars['summary']       HTML for the content summary
 * @uses $vars['header_params'] Vars to pass to image block/header wrapper
 * @uses $vars['show_summary']  (bool) render the object/elements/summary view
 */

$show_summary = (bool) elgg_extract('show_summary', $vars, false);
if ($show_summary) {
	$image_block_vars = (array) elgg_extract('image_block_vars', $vars, []);
	$image_block_vars['class'] = elgg_extract_class($image_block_vars, ['elgg-listing-full-header']);
	
	$vars['image_block_vars'] = $image_block_vars;
	$vars['title'] = elgg_extract('title', $vars, false);
	
	echo elgg_view('object/elements/summary', $vars);
	return;
}

$icon = elgg_extract('icon', $vars);
$summary = elgg_extract('summary', $vars);
if (!$icon && !$summary) {
	return;
}

$header_params = (array) elgg_extract('header_params', $vars, []);
$header_params['class'] = elgg_extract_class($header_params, 'elgg-listing-full-header');

echo elgg_view_image_block($icon, $summary, $header_params);
