<?php
/**
 * Displays page menu
 *
 * @uses $vars['show_page_menu'] (bool) Show the page menu
 * @uses $vars['page_menu_params'] (array) An array of params to pass to page menu
 */

if (!elgg_extract('show_page_menu', $vars, true)) {
	return;
}

$custom_params = (array) elgg_extract('page_menu_params', $vars, []);
$default_params = [
	'sort_by' => 'name',
	'prepare_vertical' => true,
];

$params = array_merge($default_params, $custom_params);

$page_menu = elgg_view_menu('page', $params);
if (empty($page_menu)) {
	return;
}

echo elgg_view_module('aside', '', $page_menu);
