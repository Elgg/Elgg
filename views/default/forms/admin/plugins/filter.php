<?php
/**
 * Category filter for plugins
 *
 * @uses $vars['category']
 * @uses $vars['category_options']
 * @uses $vvars['sort']
 */

echo elgg_view('input/dropdown', array(
	'name' => 'category',
	'options_values' => $vars['category_options'],
	'value' => $vars['category'],
));

echo elgg_view('input/hidden', array(
	'name' => 'sort',
	'value' => $vars['sort'],
));

echo elgg_view('input/submit', array(
	'value' => elgg_echo('filter'),
	'class' => 'elgg-button elgg-button-action',
));
