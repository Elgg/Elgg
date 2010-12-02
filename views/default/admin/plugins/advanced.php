<?php
/**
 * Elgg administration advanced plugin screen
 *
 * Shows a list of all plugins sorted by load order.
 *
 * @package Elgg
 * @subpackage Core
 */

regenerate_plugin_list();
$installed_plugins = get_installed_plugins();
$plugin_list = array();
$show_category = get_input('category', NULL);

// Get a list of the all categories
// and trim down the plugin list if we're not viewing all categories.
// @todo this could be cached somewhere after have the manifest loaded
$categories = array();

foreach ($installed_plugins as $id => $plugin) {
	$plugin_categories = $plugin['manifest']['category'];

	// handle plugins that don't declare categories
	if ((!$plugin_categories && $show_category) || ($show_category && !in_array($show_category, $plugin_categories))) {
		unset($installed_plugins[$id]);
	}

	foreach ($plugin_categories as $category) {
		if (!array_key_exists($category, $categories)) {
			$categories[$category] = elgg_echo("admin:plugins:label:moreinfo:categories:$category");
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
	'class' => 'action-button'
));

$category_form = elgg_view('input/form', array(
	'body' => $category_pulldown . $category_button
));

// Page Header elements
$title = elgg_view_title(elgg_echo('admin:plugins'));

// @todo Until "en/disable all" means "All plugins on this page" hide when not looking at all.
if (!isset($show_category) || empty($show_category)) {
	$buttons = "<a class='action-button' href=\"{$CONFIG->url}action/admin/plugins/enableall?__elgg_token=$token&amp;__elgg_ts=$ts\">".elgg_echo('enableall')."</a>  <a class='action-button disabled' href=\"{$CONFIG->url}action/admin/plugins/disableall?__elgg_token=$token&amp;__elgg_ts=$ts\">".elgg_echo('disableall')."</a> ";
	$buttons .= "<br /><br />";
} else {
	$buttons = '';
}

$buttons .= $category_form;

// construct page header
?>
<div id="content_header" class="clearfix">
	<div class="content_header_title"><?php echo $title ?></div>
	<div class="content_header_options"><?php echo $buttons ?></div>
</div>
<br />
<?php

$limit = get_input('limit', 10);
$offset = get_input('offset', 0);

$plugin_list = get_plugin_list();
$max = 0;
foreach($plugin_list as $key => $foo) {
	if ($key > $max) $max = $key;
}

// Display list of plugins
$n = 0;
foreach ($installed_plugins as $plugin => $data) {
	echo elgg_view('admin/components/plugin', array(
		'plugin' => $plugin,
		'details' => $data,
		'maxorder' => $max,
		'order' => array_search($plugin, $plugin_list)
	));
	$n++;
}
?>
<script type="text/javascript">
	$(document).ready(function() {
		$('a.manifest_details.link').click(function() {
			elgg_slide_toggle($(this), '.plugin_details', '.manifest_file');
		});
	});
</script>