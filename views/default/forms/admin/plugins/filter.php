<?php
/**
 * Category filter for plugins
 *
 * @uses $vars['category']
 * @uses $vars['category_options']
 * @uses $vvars['sort']
 */

echo '<div>';
echo '<label for="admin-plugins-category" class="hidden">' . elgg_echo('filter') . '</label>';
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
