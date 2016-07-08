<?php

/**
 * Object full view header
 *
 * @uses $vars['icon']        HTML for the content icon
 * @uses $vars['summary']     HTML for the content summary
 * @uses $vars['header_params'] Vars to pass to image block/header wrapper
 */

$icon = elgg_extract('icon', $vars);
$summary = elgg_extract('summary', $vars);

$header_params = (array) elgg_extract('header_params', $vars, []);
$class = (array) elgg_extract('class', $header_params, []);
$class[] = 'elgg-listing-full-header';
$header_params['class'] = implode(' ', $class);

echo elgg_view_image_block($icon, $summary, $header_params);