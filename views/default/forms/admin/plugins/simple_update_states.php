<?php 
/**
 * Elgg administration simple plugin screen
 *
 * Shows an alphabetical list of "simple" plugins.
 *
 * @package Elgg
 * @subpackage Core
 */

elgg_generate_plugin_entities();
$installed_plugins = elgg_get_plugins('any');
$plugin_list = array();

foreach ($installed_plugins as $plugin) {
	if (!$plugin->isValid()) {
		continue;
	}
	$interface = $plugin->getManifest()->getAdminInterface();
	if ($interface == 'simple') {
		$plugin_list[$plugin->getManifest()->getName()] = $plugin;
	}
}

ksort($plugin_list);

echo elgg_view_entity_list($plugin_list, 0, 0, 0, false, false, false);
echo elgg_view('input/submit', array('value' => elgg_echo('save')));
