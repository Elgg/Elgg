<?php
/**
 * Sort plugins form body
 *
 * @uses $vars['sort']
 * @uses $vars['sort_options']
 * @uses $vars['category']
 */

echo '<div class="mtm">';
$attr = [
	'for' => 'admin-plugins-sort', 
	'hidden' => true
];
echo elgg_format_element('label', $attr, elgg_echo('sort'));
echo elgg_view('input/select', array(
	'id' => 'admin-plugins-sort',
	'name' => 'sort',
	'options_values' => $vars['sort_options'],
	'value' => $vars['sort'],
));

echo elgg_view('input/hidden', array(
	'name' => 'category',
	'value' => $vars['category'],
));

echo elgg_view('input/submit', array(
	'value' => elgg_echo('sort'),
	'class' => 'elgg-button elgg-button-action'
));
echo '</div>';
