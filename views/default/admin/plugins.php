<?php
/**
 * Elgg administration plugin screen
 *
 * Shows a list of plugins that can be sorted and filtered.
 *
 * @package Elgg.Core
 * @subpackage Admin.Plugins
 */

elgg_load_js('lightbox');
elgg_load_css('lightbox');

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
			// force disable and warn
			elgg_add_admin_notice('invalid_and_deactivated_' . $plugin->getID(),
					elgg_echo('ElggPlugin:InvalidAndDeactivated', array($plugin->getId())));
			$plugin->deactivate();
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

$activate_all = elgg_view('output/url', [
	'href' => 'action/admin/plugins/activate_all',
	'text' => elgg_echo('admin:plugins:activate_all'),
	'class' => 'elgg-button elgg-button-submit elgg-plugins-toggle',
	'data-desired-state' => 'active',
]);
$deactivate_all = elgg_view('output/url', [
	'href' => 'action/admin/plugins/deactivate_all',
	'text' => elgg_echo('admin:plugins:deactivate_all'),
	'class' => 'elgg-button elgg-button-submit elgg-plugins-toggle',
	'data-desired-state' => 'inactive',
]);

$buttons = elgg_format_element('div', ['class' => 'float-alt'], $activate_all . $deactivate_all);

echo elgg_format_element('div', ['class' => 'mbm'], $buttons . $category_form);

$options = array(
	'limit' => 0,
	'full_view' => true,
	'list_type_toggle' => false,
	'pagination' => false,
	'display_reordering' => true
);

echo elgg_format_element('div', ['id' => 'elgg-plugin-list'] , elgg_view_entity_list($installed_plugins, $options));
