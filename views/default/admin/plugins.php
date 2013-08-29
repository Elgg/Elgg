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
$show_category = get_input('category', 'all');
$sort = get_input('sort', 'priority');

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
		case 'nonbundled':
			if (in_array('bundled', $plugin_categories)) {
				unset($installed_plugins[$id]);
			}
			break;
		default:
			if (!in_array($show_category, $plugin_categories)) {
				unset($installed_plugins[$id]);
			}
			break;
	}

	if (isset($plugin_categories)) {
		foreach ($plugin_categories as $category) {
			if (!array_key_exists($category, $categories)) {
				$categories[$category] = ElggPluginManifest::getFriendlyCategory($category);
			}
		}
	}
}

$guids = array();
foreach ($installed_plugins as $plugin) {
	$guids[] = $plugin->getGUID();
}

// sort plugins
switch ($sort) {
	case 'date':
		$plugin_list = array();
		foreach ($installed_plugins as $plugin) {
			$create_date = $plugin->getTimeCreated();
			while (isset($plugin_list[$create_date])) {
				$create_date++;
			}
			$plugin_list[$create_date] = $plugin;
		}
		krsort($plugin_list);
		break;
	case 'alpha':
		$plugin_list = array();
		foreach ($installed_plugins as $plugin) {
			$plugin_list[$plugin->getFriendlyName()] = $plugin;
		}
		ksort($plugin_list);
		break;
	case 'priority':
	default:
		$plugin_list = $installed_plugins;
		break;
}



asort($categories);

// we want bundled/nonbundled pulled to be at the top of the list
unset($categories['bundled']);
unset($categories['nonbundled']);

$common_categories = array(
	'all' => elgg_echo('admin:plugins:category:all'),
	'active' => elgg_echo('admin:plugins:category:active'),
	'inactive' => elgg_echo('admin:plugins:category:inactive'),
	'bundled' => elgg_echo('admin:plugins:category:bundled'),
	'nonbundled' => elgg_echo('admin:plugins:category:nonbundled'),
);

$categories = array_merge($common_categories, $categories);
// security - only want a defined option
if (!array_key_exists($show_category, $categories)) {
	$show_category = reset($categories);
}

$category_form = elgg_view_form('admin/plugins/filter', array(
	'action' => 'admin/plugins',
	'method' => 'get',
	'disable_security' => true,
), array(
	'category' => $show_category,
	'category_options' => $categories,
	'sort' => $sort,
));


$sort_options = array(
	'priority' => elgg_echo('sort:priority'),
	'alpha' => elgg_echo('sort:alpha'),
	'date' => elgg_echo('sort:newest'),
);
// security - only want a defined option
if (!array_key_exists($sort, $sort_options)) {
	$sort = reset($sort_options);
}

$sort_form = elgg_view_form('admin/plugins/sort', array(
	'action' => 'admin/plugins',
	'method' => 'get',
	'disable_security' => true,
), array(
	'sort' => $sort,
	'sort_options' => $sort_options,
	'category' => $show_category,
));

$buttons = "<div class=\"clearfix mbm\">";
$buttons .= elgg_view_form('admin/plugins/change_state', array(
	'action' => 'action/admin/plugins/activate_all',
	'class' => 'float',
), array(
	'guids' => $guids,
	'action' => 'activate',
));
$buttons .= elgg_view_form('admin/plugins/change_state', array(
	'action' => 'action/admin/plugins/deactivate_all',
	'class' => 'float',
), array(
	'guids' => $guids,
	'action' => 'deactivate',
));
$buttons .= "</div>";

$buttons .= $category_form . $sort_form;

// construct page header
?>
<div id="content_header" class="mbm clearfix">
	<div class="content-header-options"><?php echo $buttons ?></div>
</div>

<div id="elgg-plugin-list">
<?php

$options = array(
	'limit' => 0,
	'full_view' => true,
	'list_type_toggle' => false,
	'pagination' => false,
);
if ($show_category == 'all' && $sort == 'priority') {
	$options['display_reordering'] = true;
}
echo elgg_view_entity_list($plugin_list, $options);

?>
</div>