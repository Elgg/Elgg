<?php

/**
 * Renders page menu
 *
 * @uses $vars['page_menu']        Overwrite the default page menu
 * @uses $vars['page_menu_params'] Provide additional vars for default page menu
 */
$page_menu = elgg_extract('page_menu', $vars);
if (!isset($page_menu)) {
	$page_menu_params = (array) elgg_extract('page_menu_params', $vars, []);
	$page_menu = elgg_view_menu('page', $page_menu_params + [
		'sort_by' => 'name',
		'class' => 'list-group list-group-flush flex-column',
		'item_class' => 'list-group-item',
	]);
}

if ($page_menu) {
	echo elgg_view('page/components/module', [
		'body' => $page_menu,
		'class' => 'elgg-page-menu-block',
	]);
}