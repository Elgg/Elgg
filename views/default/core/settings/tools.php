<?php
/**
 * Elgg plugin specific user settings.
 *
 * @uses array $vars['installed_plugins'] An array of plugins as returned by elgg_get_plugins()
 *
 * @package Elgg.Core
 * @subpackage Plugins.Settings
 */

// Description of what's going on
echo "<div class='user-settings margin-top'>"
	. elgg_view('output/longtext', array('value' => elgg_echo("usersettings:plugins:description")))
	. "</div>";

$limit = get_input('limit', 10);
$offset = get_input('offset', 0);

// Get the installed plugins
$installed_plugins = $vars['installed_plugins'];
$count = count($installed_plugins);

// Display list of plugins
$n = 0;
foreach ($installed_plugins as $plugin) {
	if ($plugin->isActive()) {
		echo elgg_view("core/settings/tools/plugin", array('plugin' => $plugin));
	}
}