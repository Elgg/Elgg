<?php
/**
 * Elgg administration plugin screen
 *
 * Shows a list of plugins that can be sorted and filtered.
 *
 * @uses $vars['list_only']
 */

elgg_import_esm('admin/plugins');

$active_filter = elgg_strtolower(get_input('filter', 'all'));

_elgg_services()->plugins->generateEntities();

$installed_plugins = elgg_get_plugins('all');

// needed for expected plugin view rendering, there are usecases where this is not set so forcing it here
elgg_push_context('admin');

$plugins_list = elgg_view_entity_list($installed_plugins, [
	'limit' => false,
	'full_view' => true,
	'pagination' => false,
	'display_reordering' => true,
	'active_filter' => $active_filter,
]);

$plugins_list .= elgg_format_element('div', ['id' => 'elgg-plugin-list-cover']);

elgg_pop_context();

if ((bool) elgg_extract('list_only', $vars, false)) {
	echo $plugins_list;
	return;
}

elgg_register_menu_item('title', [
	'name' => 'activate-all',
	'href' => elgg_generate_action_url('admin/plugins/activate_all'),
	'text' => elgg_echo('admin:plugins:activate_all'),
	'link_class' => 'elgg-button elgg-button-submit elgg-plugins-toggle',
	'data-desired-state' => 'active',
]);

elgg_register_menu_item('title', [
	'name' => 'deactivate-all',
	'href' => elgg_generate_action_url('admin/plugins/deactivate_all'),
	'text' => elgg_echo('admin:plugins:deactivate_all'),
	'link_class' => 'elgg-button elgg-button-submit elgg-plugins-toggle',
	'data-desired-state' => 'inactive',
]);

$categories = [];

foreach ($installed_plugins as $plugin) {
	if (!$plugin->isValid()) {
		if ($plugin->isActive()) {
			if (elgg_get_config('auto_disable_plugins')) {
				try {
					// force disable and warn
					$plugin->deactivate();
					
					elgg_register_error_message(elgg_echo('ElggPlugin:InvalidAndDeactivated', [$plugin->getID()]));
				} catch (\Elgg\Exceptions\PluginException $e) {
					// do nothing
				}
			}
		}
		
		continue;
	}
	
	$categories = array_merge($categories, $plugin->getCategories());
}

echo elgg_view('admin/plugins/categories', [
	'categories' => $categories,
	'active_filter' => $active_filter,
]);

echo elgg_format_element('div', ['id' => 'elgg-plugin-list'], $plugins_list);
