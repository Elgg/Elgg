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

// Description of what's going on
$buttons = " <a class='enableallplugins' href=\"{$CONFIG->url}action/admin/plugins/enableall?__elgg_token=$token&__elgg_ts=$ts\">".elgg_echo('enableall')."</a>  <a class='disableallplugins' href=\"{$CONFIG->url}action/admin/plugins/disableall?__elgg_token=$token&__elgg_ts=$ts\">".elgg_echo('disableall')."</a> ";
echo "<div class=\"contentWrapper\"><span class=\"contentIntro\">" . $buttons . elgg_view('output/longtext', array('value' => elgg_echo("admin:plugins:description"))) . "<div class='clearfloat'></div></span></div>";

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
	//if (($n>=$offset) && ($n < $offset+$limit))
		echo elgg_view("admin/plugins_opt/plugin", array('plugin' => $plugin, 'details' => $data, 'maxorder' => $max, 'order' => array_search($plugin, $plugin_list)));

	$n++;
}

// Diplay nav
/*
if ($count)
{
	echo elgg_view('navigation/pagination',array(
										'baseurl' => $_SERVER['REQUEST_URI'],
										'offset' => $offset,
										'count' => $count,
												));
}
*/