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
echo elgg_view('output/longtext', array(
	'value' => elgg_echo("usersettings:plugins:description"),
	'class' => 'user-settings mtn mbm',
));

$limit = get_input('limit', 10);
$offset = get_input('offset', 0);

// Get the installed plugins
$installed_plugins = $vars['installed_plugins'];
$count = count($installed_plugins);


// Display all plugins' usersettings forms
foreach ($installed_plugins as $plugin) {
	$plugin_id = $plugin->getID();
	if ($plugin->isActive()) {
		if (elgg_view_exists("usersettings/$plugin_id/edit") 
			|| elgg_view_exists("plugins/$plugin_id/usersettings")) {
	
			$title = $plugin->getManifest()->getName();
			$body = elgg_view_form('plugins/usersettings/save', array(), array('entity' => $plugin));
			echo elgg_view_module('info', $title, $body);
		}
	}
}