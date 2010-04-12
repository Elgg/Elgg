<?php
/**
 * Elgg administration plugin system index
 * This is a special page that permits the configuration of plugins in a standard way.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
admin_gatekeeper();
regenerate_plugin_list();

$show_category = get_input('category', NULL);

// Get a list of the all categories
// and trim down the plugin list if we're not viewing all categories.
// @todo this could be cached somewhere after have the manifest loaded
$categories = array();
$installed_plugins = get_installed_plugins();

foreach ($installed_plugins as $i => $plugin) {
	$plugin_categories = $plugin['manifest']['category'];

	// handle plugins that don't declare categories
	if ((!$plugin_categories && $show_category) || ($show_category && !in_array($show_category, $plugin_categories))) {
		unset($installed_plugins[$i]);
	}

	foreach ($plugin_categories as $category) {
		if (!array_key_exists($category, $categories)) {
			$categories[$category] = elgg_echo("admin:plugins:label:moreinfo:categories:$category");
		}
	}
}

// Display main admin menu
$vars = array(
	'installed_plugins' => $installed_plugins,
	'categories' => $categories,
	'show_category' => $show_category
);

$main_box = elgg_view("admin/plugins", $vars);
$content = elgg_view_layout("one_column_with_sidebar", $main_box);

page_draw(elgg_echo('admin:plugins'), $content);
