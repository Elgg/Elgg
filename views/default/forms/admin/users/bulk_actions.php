<?php
/**
 * Form wrapper for bulk actions on a user listing
 *
 * @uses $vars['filter']    An indication of what the listing is used for (eg all, online, admin, etc.) (default: all)
 * @uses $vars['menu_vars'] An array of additional option to pass to the buttons menu
 * @uses $vars['options']   Additional options for the user selection
 */

elgg_require_css('forms/admin/users/bulk_actions');
elgg_import_esm('forms/admin/users/bulk_actions');

// did we search
$query = get_input('q');
$getter = $query ? 'elgg_search' : 'elgg_get_entities';

// make selection options
$default_options = [
	'type' => 'user',
	'subtype' => null,
	'full_view' => false,
	'list_type' => 'table',
	'limit' => max(25, elgg_get_config('default_limit'), (int) get_input('limit', 0)),
	'columns' => [
		elgg()->table_columns->checkbox(elgg_view('input/checkbox', [
			'name' => 'user_guids',
			'title' => elgg_echo('table_columns:fromView:checkbox'),
		]), [
			'name' => 'user_guids[]',
		]),
		elgg()->table_columns->icon(null, [
			'use_hover' => false,
		]),
		elgg()->table_columns->user(null, [
			'item_view' => 'user/default/admin_column',
		]),
		elgg()->table_columns->email(),
		elgg()->table_columns->time_created(null, [
			'format' => 'friendly',
		]),
		elgg()->table_columns->entity_menu(null, [
			'add_user_hover_admin_section' => true,
			'admin_listing' => elgg_extract('filter', $vars, 'all'),
		]),
	],
	'list_class' => 'elgg-admin-users',
	'query' => $query,
];

// merge additional options
$options = (array) elgg_extract('options', $vars, []);
$options = array_merge($default_options, $options);

$users = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($options, $getter) {
	return elgg_list_entities($options, $getter);
});
if (empty($users)) {
	echo elgg_view('page/components/no_results', [
		'no_results' => elgg_extract('no_results', $vars, true),
	]);
	return;
}

// draw a menu for bulk actions
// the menu items will be shown as submit buttons with a custom formaction
$default_menu_vars = [
	'class' => ['elgg-menu-hz', 'elgg-admin-users-bulkactions-buttons'],
	'item_contents_view' => 'navigation/menu/elements/item/submit',
	'filter_value' => elgg_extract('filter', $vars, 'all'),
	// @see \Elgg\Menus\AdminUsersBulk::registerActions()
	'show_ban' => true,
	'show_unban' => true,
	'show_delete' => true,
	'show_validate' => false,
];
$menu_vars = (array) elgg_extract('menu_vars', $vars, []);
$menu_vars = array_merge($default_menu_vars, $menu_vars);
echo elgg_view_menu('admin:users:bulk', $menu_vars);

echo $users;
