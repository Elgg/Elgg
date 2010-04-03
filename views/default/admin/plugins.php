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

// Page Header elements
$title = elgg_view_title(elgg_echo('admin:plugins'));
$buttons = "<a class='action_button' href=\"{$CONFIG->url}action/admin/plugins/enableall?__elgg_token=$token&amp;__elgg_ts=$ts\">".elgg_echo('enableall')."</a>";
$buttons .= "<a class='action_button disabled' href=\"{$CONFIG->url}action/admin/plugins/disableall?__elgg_token=$token&amp;__elgg_ts=$ts\">".elgg_echo('disableall')."</a> ";

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