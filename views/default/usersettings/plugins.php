<?php
/**
 * Elgg plugin specific user settings.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

// Description of what's going on
echo "<div class=\"contentWrapper\">" . elgg_view('output/longtext', array('value' => elgg_echo("usersettings:plugins:description"))) . "</div>";

$limit = get_input('limit', 10);
$offset = get_input('offset', 0);

// Get the installed plugins
$installed_plugins = $vars['installed_plugins'];
$count = count($installed_plugins);

// Display list of plugins
$n = 0;
foreach ($installed_plugins as $plugin => $data) {
	if (is_plugin_enabled($plugin)) {
		echo elgg_view("usersettings/plugins_opt/plugin", array('plugin' => $plugin, 'details' => $data));
	}
}