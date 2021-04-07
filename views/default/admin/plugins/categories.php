<?php

$plugins = elgg_extract('plugins', $vars);
if (empty($plugins)) {
	return;
}

// Get a list of the all categories
// and trim down the plugin list if we're not viewing all categories.
$categories = [];

foreach ($plugins as $plugin) {
	if (!$plugin->isValid()) {
		if ($plugin->isActive()) {
			if (elgg_get_config('auto_disable_plugins', true)) {
				try {
					// force disable and warn
					$plugin->deactivate();
					
					elgg_add_admin_notice('invalid_and_deactivated_' . $plugin->getID(), elgg_echo('ElggPlugin:InvalidAndDeactivated', [$plugin->getID()]));
				} catch (\Elgg\Exceptions\PluginException $e) {
					// do nothing
				}
			}
		}
		
		continue;
	}

	$plugin_categories = $plugin->getCategories();
	foreach ($plugin_categories as $category => $category_title) {
		if (!array_key_exists($category, $categories)) {
			$categories[$category] = $category_title;
		}
	}
}

asort($categories);

$common_categories = [
	'all' => elgg_echo('admin:plugins:category:all'),
	'active' => elgg_echo('admin:plugins:category:active'),
	'inactive' => elgg_echo('admin:plugins:category:inactive'),
];

$categories = array_merge($common_categories, $categories);

echo elgg_view('admin/plugins/filter', [
	'category' => "all",
	'category_options' => $categories,
	'active_filter' => elgg_extract('active_filter', $vars),
]);
