<?php
/**
 * Category filter for plugins
 *
 * @uses $vars['category']
 * @uses $vars['category_options']
 * @uses $vvars['sort']
 */

echo '<div>';
$attr = [
	'for' => 'admin-plugins-category', 
	'hidden' => true
];
echo elgg_format_element('label', $attr, elgg_echo('filter'));
echo elgg_view('input/select', array(
	'id' => 'admin-plugins-category',
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
echo '</div>';
