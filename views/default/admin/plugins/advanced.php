<?php
/**
 * Elgg administration advanced plugin screen
 *
 * Shows a list of all plugins sorted by load order.
 *
 * @package Elgg.Core
 * @subpackage Admin.Plugins
 */

elgg_generate_plugin_entities();
$installed_plugins = elgg_get_plugins('any');
$show_category = get_input('category', 'all');

// Get a list of the all categories
// and trim down the plugin list if we're not viewing all categories.
// @todo this could be cached somewhere after have the manifest loaded
$categories = array();

foreach ($installed_plugins as $id => $plugin) {
	if (!$plugin->isValid()) {
		continue;
	}

	$plugin_categories = $plugin->getManifest()->getCategories();

	// handle plugins that don't declare categories
	// unset them here because this is the list we foreach
	switch ($show_category) {
		case 'all':
			break;
		case 'active':
			if (!$plugin->isActive()) {
				unset($installed_plugins[$id]);
			}
			break;
		case 'inactive':
			if ($plugin->isActive()) {
				unset($installed_plugins[$id]);
			}
			break;
		default:
			if (!in_array($show_category, $plugin_categories)) {
				unset($installed_plugins[$id]);
			}
			break;
	}
	//if ($show_category && !in_array($show_category, $plugin_categories)) {
	//	unset($installed_plugins[$id]);
	//}

	if (isset($plugin_categories)) {
		foreach ($plugin_categories as $category) {
			if (!array_key_exists($category, $categories)) {
				$categories[$category] = elgg_echo("admin:plugins:category:$category");
			}
		}
	}
}

asort($categories);

$common_categories = array(
	'all' => elgg_echo('admin:plugins:category:all'),
	'active' => elgg_echo('admin:plugins:category:active'),
	'inactive' => elgg_echo('admin:plugins:category:inactive'),
);

$categories = array_merge($common_categories, $categories);

$category_dropdown = elgg_view('input/dropdown', array(
	'name' => 'category',
	'options_values' => $categories,
	'value' => $show_category
));

$category_button = elgg_view('input/submit', array(
	'value' => elgg_echo('filter'),
	'class' => 'elgg-button elgg-button-action'
));

$category_form = elgg_view('input/form', array(
	'body' => $category_dropdown . $category_button,
	'method' => 'get',
	'action' => 'admin/plugins/advanced',
	'disable_security' => true,
));

// @todo Until "en/deactivate all" means "All plugins on this page" hide when not looking at all.
if ($show_category == 'all') {
	$activate_url = "action/admin/plugins/activate_all";
	$activate_url = elgg_add_action_tokens_to_url($activate_url);
	$deactivate_url = "action/admin/plugins/deactivate_all";
	$deactivate_url = elgg_add_action_tokens_to_url($deactivate_url);

	$buttons = "<div class=\"mbl\">";
	$buttons .= "<a class='elgg-button elgg-button-action' href=\"$activate_url\">" . elgg_echo('admin:plugins:activate_all') . '</a> ';
	$buttons .=	"<a class='elgg-button elgg-button-cancel' href=\"$deactivate_url\">" . elgg_echo('admin:plugins:deactivate_all') . '</a> ';
	$buttons .= "</div>";
} else {
	$buttons = '';
}

$buttons .= $category_form;

// construct page header
?>
<div id="content_header" class="mbm clearfix">
	<div class="content-header-options"><?php echo $buttons ?></div>
</div>

<div id="elgg-plugin-list">
<?php

echo elgg_view_entity_list($installed_plugins, 0, 0, 0, true, false, false); 

?>
</div>