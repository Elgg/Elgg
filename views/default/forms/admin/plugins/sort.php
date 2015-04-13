<?php
/**
 * Sort plugins form body
 *
 * @uses $vars['sort']
 * @uses $vars['sort_options']
 * @uses $vars['category']
 */

echo '<div class="mtm">';
echo '<label for="admin-plugins-sort" class="hidden">' . elgg_echo('sort') . '</label>';
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
