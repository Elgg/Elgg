<?php
/**
 * Elgg administration plugin screen
 *
 * Shows a list of plugins that can be sorted and filtered.
 *
 * @uses $vars['list_only']
 */

$list_only = (bool)elgg_extract('list_only', $vars);

// @todo this should occur in the controller code
_elgg_generate_plugin_entities();

$installed_plugins = elgg_get_plugins('any');

// Get a list of the all categories
// and trim down the plugin list if we're not viewing all categories.
// @todo this could be cached somewhere after have the manifest loaded
$categories = array();

foreach ($installed_plugins as $id => $plugin) {
	if (!$plugin->isValid()) {
		if ($plugin->isActive()) {
			$disable_plugins = elgg_get_config('auto_disable_plugins');
			if ($disable_plugins === null) {
				$disable_plugins = true;
			}
			if ($disable_plugins) {
				// force disable and warn
				elgg_add_admin_notice('invalid_and_deactivated_' . $plugin->getID(),
						elgg_echo('ElggPlugin:InvalidAndDeactivated', array($plugin->getId())));
				$plugin->deactivate();
			}
		}
		continue;
	}

	$plugin_categories = $plugin->getManifest()->getCategories();

	if (isset($plugin_categories)) {
		foreach ($plugin_categories as $category) {
			if (!array_key_exists($category, $categories)) {
				$categories[$category] = ElggPluginManifest::getFriendlyCategory($category);
			}
		}
	}
}

$list_options = [
	'limit' => 0,
	'full_view' => true,
	'list_type_toggle' => false,
	'pagination' => false,
	'display_reordering' => true,
];

$add_context = !elgg_in_context('admin');
if ($add_context) {
	// needed for expected plugin view rendering
	elgg_push_context('admin');
}
$plugins_list = elgg_view_entity_list($installed_plugins, $list_options);
$plugins_list .= "<div id='elgg-plugin-list-cover'></div>";
if ($add_context) {
	elgg_pop_context();
}

if ($list_only) {
	echo $plugins_list;
	return;
}

asort($categories);

// we want bundled/nonbundled pulled to be at the top of the list
unset($categories['bundled']);
unset($categories['nonbundled']);

$common_categories = [
	'all' => elgg_echo('admin:plugins:category:all'),
	'active' => elgg_echo('admin:plugins:category:active'),
	'inactive' => elgg_echo('admin:plugins:category:inactive'),
	'bundled' => elgg_echo('admin:plugins:category:bundled'),
	'nonbundled' => elgg_echo('admin:plugins:category:nonbundled'),
];

$categories = array_merge($common_categories, $categories);

$category_form = elgg_view("admin/plugins/filter", [
	'category' => "all",
	'category_options' => $categories
]);

elgg_register_menu_item('title', [
	'name' => 'activate-all',
	'href' => 'action/admin/plugins/activate_all',
	'text' => elgg_echo('admin:plugins:activate_all'),
	'link_class' => 'elgg-button elgg-button-submit elgg-plugins-toggle',
	'data-desired-state' => 'active',
]);
elgg_register_menu_item('title', [
	'name' => 'dactivate-all',
	'href' => 'action/admin/plugins/deactivate_all',
	'text' => elgg_echo('admin:plugins:deactivate_all'),
	'link_class' => 'elgg-button elgg-button-submit elgg-plugins-toggle',
	'data-desired-state' => 'inactive',
]);

echo $category_form;
echo elgg_format_element(
	'div',
	['id' => 'elgg-plugin-list'],
	$plugins_list
);
