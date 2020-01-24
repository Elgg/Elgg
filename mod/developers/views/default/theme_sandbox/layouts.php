<?php
/**
 * Layouts
 */

elgg_push_breadcrumb('Breadcrumb 1', '#');
elgg_push_breadcrumb('Breadcrumb 2', '#');
elgg_push_breadcrumb('Breadcrumb 3');

elgg_register_menu_item('title', [
	'name' => 'button1',
	'href' => '#',
	'text' => 'Button 1',
	'link_class' => 'elgg-button elgg-button-action',
]);

elgg_register_menu_item('title', [
	'name' => 'button2',
	'href' => '#',
	'text' => 'Button 2',
	'link_class' => 'elgg-button elgg-button-action',
]);

echo elgg_view_module('info', "One Column", elgg_view('theme_sandbox/layouts/one_column'));

echo elgg_view_module('info', "One Sidebar", elgg_view('theme_sandbox/layouts/one_sidebar'));

echo elgg_view_module('info', "Two Sidebar", elgg_view('theme_sandbox/layouts/two_sidebar'));
