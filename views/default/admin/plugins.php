<?php
/**
 * Elgg administration plugin screen
 *
 * Shows a list of plugins that can be sorted and filtered.
 *
 * @uses $vars['list_only']
 */

elgg_require_js('admin/plugins');

$list_only = (bool) elgg_extract('list_only', $vars, false);

// @todo this should occur in the controller code
_elgg_generate_plugin_entities();

$installed_plugins = elgg_get_plugins('any');

// needed for expected plugin view rendering, there are usecases where this is not set so forcing it here
elgg_push_context('admin');

$plugins_list = elgg_view_entity_list($installed_plugins, [
	'limit' => 0,
	'full_view' => true,
	'list_type_toggle' => false,
	'pagination' => false,
	'display_reordering' => true,
]);

$plugins_list .= "<div id='elgg-plugin-list-cover'></div>";

elgg_pop_context();

if ($list_only) {
	echo $plugins_list;
	return;
}

echo elgg_view('admin/plugins/categories', ['plugins' => $installed_plugins]);

elgg_register_menu_item('title', [
	'name' => 'activate-all',
	'href' => 'action/admin/plugins/activate_all',
	'text' => elgg_echo('admin:plugins:activate_all'),
	'link_class' => 'elgg-button elgg-button-submit elgg-plugins-toggle',
	'data-desired-state' => 'active',
	'is_action' => true,
]);
elgg_register_menu_item('title', [
	'name' => 'dactivate-all',
	'href' => 'action/admin/plugins/deactivate_all',
	'text' => elgg_echo('admin:plugins:deactivate_all'),
	'link_class' => 'elgg-button elgg-button-submit elgg-plugins-toggle',
	'data-desired-state' => 'inactive',
	'is_action' => true,
]);


echo elgg_format_element('div', [
	'id' => 'elgg-plugin-list',
], $plugins_list);
