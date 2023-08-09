<?php
/**
 * Header to show on top of user listings
 *
 * @uses #vars['filter'] An indication of what the listing is used for (eg all, online, admin, etc.) (default: all)
 */

elgg_require_css('admin/users/header');

elgg_register_menu_item('title', [
	'name' => 'users:add',
	'text' => elgg_echo('admin:users:add'),
	'href' => 'admin/users/add',
	'link_class' => 'elgg-button elgg-button-action',
	'icon' => 'plus',
]);

echo elgg_view('navigation/filter', [
	'filter_id' => 'admin/users',
	'filter_value' => elgg_extract('filter', $vars, 'all'),
]);

$search_class = [
	'mbl',
];
$search_query = get_input('q');
if (!(bool) elgg_extract('show_search_form', $vars, !empty($search_query))) {
	$search_class[] = 'hidden';
}

echo elgg_view_form('admin/users/search', [
	'method' => 'GET',
	'action' => elgg_get_current_url(),
	'disable_security' => true,
	'class' => $search_class,
	'role' => 'search',
	'aria-label' => elgg_echo('aria:label:admin:users:search'),
], [
	'additional_search_fields' => elgg_extract('additional_search_fields', $vars, []),
]);
