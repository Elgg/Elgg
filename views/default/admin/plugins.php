<?php
/**
 * Elgg administration plugin main screen
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

global $CONFIG;

$ts = time();
$token = generate_action_token($ts);
$categories = array_merge(array('' => elgg_echo('admin:plugins:categories:all')), $vars['categories']);

$category_pulldown = elgg_view('input/pulldown', array(
	'internalname' => 'category',
	'options_values' => $categories,
	'value' => $vars['show_category']
));

$category_button = elgg_view('input/button', array(
	'value' => elgg_echo('filter'),
	'class' => 'action_button'
));

$category_form = elgg_view('input/form', array(
	'body' => $category_pulldown . $category_button
));

// Page Header elements
$title = elgg_view_title(elgg_echo('admin:plugins'));

// @todo Until "en/disable all" means "All plugins on this page" hide when not looking at all.
if (!isset($vars['show_category']) || empty($vars['show_category'])) {
	$buttons = "<a class='action_button' href=\"{$CONFIG->url}action/admin/plugins/enableall?__elgg_token=$token&amp;__elgg_ts=$ts\">".elgg_echo('enableall')."</a>  <a class='action_button disabled' href=\"{$CONFIG->url}action/admin/plugins/disableall?__elgg_token=$token&amp;__elgg_ts=$ts\">".elgg_echo('disableall')."</a> ";
	$buttons .= "<br /><br />";
} else {
	$buttons = '';
}

$buttons .= $category_form;

// construct page header
?>
<div id="content_header" class="clearfloat">
	<div class="content_header_title"><?php echo $title ?></div>
	<div class="content_header_options"><?php echo $buttons ?></div>
</div>
<?php
echo elgg_view('output/longtext', array('value' => elgg_echo("admin:plugins:description")));

$limit = get_input('limit', 10);
$offset = get_input('offset', 0);

// Get the installed plugins
$installed_plugins = $vars['installed_plugins'];
$count = count($installed_plugins);

$plugin_list = get_plugin_list();
$max = 0;
foreach($plugin_list as $key => $foo) {
	if ($key > $max) $max = $key;
}

// Display list of plugins
$n = 0;
foreach ($installed_plugins as $plugin => $data) {
	echo elgg_view("admin/plugins_opt/plugin", array('plugin' => $plugin, 'details' => $data, 'maxorder' => $max, 'order' => array_search($plugin, $plugin_list)));
	$n++;
}

?>

<script type="text/javascript">
$(document).ready(function() {
	$('a.plugin_settings.link').click(function() {
		elgg_slide_toggle($(this), '.plugin_details', '.pluginsettings');
	});
	$('a.manifest_details.link').click(function() {
		elgg_slide_toggle($(this), '.plugin_details', '.manifest_file');
	});
});
</script>
