<?php

$plugins = elgg_extract('plugins', $vars);
if (empty($plugins)) {
	return;
}

// Get a list of the all categories
// and trim down the plugin list if we're not viewing all categories.
// @todo this could be cached somewhere after have the manifest loaded
$categories = [];

foreach ($plugins as $id => $plugin) {
	if (!$plugin->isValid()) {
		if ($plugin->isActive()) {
			// @todo this needs to go somewhere else
			$disable_plugins = elgg_get_config('auto_disable_plugins');
			if ($disable_plugins === null) {
				$disable_plugins = true;
			}
			if ($disable_plugins) {
				// force disable and warn
				elgg_add_admin_notice('invalid_and_deactivated_' . $plugin->getID(),
						elgg_echo('ElggPlugin:InvalidAndDeactivated', [$plugin->getId()]));
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

echo elgg_view("admin/plugins/filter", [
	'category' => "all",
	'category_options' => $categories
]);
