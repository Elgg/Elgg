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
$show_category = get_input('category', null);

// Get a list of the all categories
// and trim down the plugin list if we're not viewing all categories.
// @todo this could be cached somewhere after have the manifest loaded
$categories = array();

foreach ($installed_plugins as $plugin) {
	$plugin_categories = $plugin->manifest->getCategories();

	// handle plugins that don't declare categories
	// unset them here because this is the list we foreach
	if ($show_category && !in_array($show_category, $plugin_categories)) {
		unset($installed_plugins[$id]);
	}

	if (isset($plugin_categories)) {
		foreach ($plugin_categories as $category) {
			if (!array_key_exists($category, $categories)) {
				$categories[$category] = elgg_echo("admin:plugins:label:moreinfo:categories:$category");
			}
		}
	}
}

$ts = time();
$token = generate_action_token($ts);
$categories = array_merge(array('' => elgg_echo('admin:plugins:categories:all')), $categories);

$category_pulldown = elgg_view('input/pulldown', array(
	'internalname' => 'category',
	'options_values' => $categories,
	'value' => $show_category
));

$category_button = elgg_view('input/submit', array(
	'value' => elgg_echo('filter'),
	'class' => 'elgg-action-button'
));

$category_form = elgg_view('input/form', array(
	'body' => $category_pulldown . $category_button
));

// Page Header elements
$title = elgg_view_title(elgg_echo('admin:plugins'));

// @todo Until "en/deactivate all" means "All plugins on this page" hide when not looking at all.
if (!isset($show_category) || empty($show_category)) {
	$activate_url = "{$CONFIG->url}action/admin/plugins/activate_all?__elgg_token=$token&amp;__elgg_ts=$ts";
	$deactivate_url = "{$CONFIG->url}action/admin/plugins/deactivate_all?__elgg_token=$token&amp;__elgg_ts=$ts";

	$buttons = "<a class='elgg-action-button' href=\"$activate_url\">" . elgg_echo('admin:plugins:activate_all') . '</a> ';
	$buttons .=	"<a class='elgg-action-button disabled' href=\"$deactivate_url\">" . elgg_echo('admin:plugins:deactivate_all') . '</a> ';
	$buttons .= "<br /><br />";
} else {
	$buttons = '';
}

$buttons .= $category_form;

// construct page header
?>
<div id="content_header" class="clearfix">
	<div class="content-header-title"><?php echo $title ?></div>
	<div class="content-header-options"><?php echo $buttons ?></div>
</div>
<br />
<?php

// Display list of plugins
foreach ($installed_plugins as $plugin) {
	echo elgg_view('admin/components/plugin', array(
		'plugin' => $plugin,
		'max_priority' => $max_priority
	));
}
?>
<script type="text/javascript">
	$(document).ready(function() {
		$('a.manifest_details.link').click(function() {
			elgg_slide_toggle($(this), '.plugin_details', '.manifest_file');
		});
	});
</script>