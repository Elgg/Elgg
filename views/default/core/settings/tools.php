<?php
/**
 * Elgg plugin specific user settings.
 *
 * @package Elgg
 * @subpackage Core
 */

// Description of what's going on
echo "<div class='user-settings margin-top'>".elgg_view('output/longtext', array('value' => elgg_echo("usersettings:plugins:description")))."</div>";

$limit = get_input('limit', 10);
$offset = get_input('offset', 0);

// Get the installed plugins
$installed_plugins = $vars['installed_plugins'];
$count = count($installed_plugins);

// Display list of plugins
$n = 0;
foreach ($installed_plugins as $plugin => $data) {
	if (elgg_is_active_plugin($plugin)) {
		echo elgg_view("core/settings/tools/plugin", array('plugin' => $plugin, 'details' => $data));
	}
}