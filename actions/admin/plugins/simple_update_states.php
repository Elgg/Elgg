<?php
/**
 * Elgg administration simple plugin bulk enable / disable
 *
 * Shows an alphabetical list of "simple" plugins.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

$installed_plugins = get_installed_plugins();
$enabled_plugins = get_input('enabled_plugins', array());

$success = TRUE;

foreach ($installed_plugins as $plugin => $info) {
	// this is only for simple plugins.
	if (!isset($info['manifest']['admin_interface']) || $info['manifest']['admin_interface'] != 'simple') {
		continue;
	}

	$plugin_enabled = is_plugin_enabled($plugin);

	// only effect changes to plugins not already in that state.
	if ($plugin_enabled && !in_array($plugin, $enabled_plugins)) {
		$success = $success && disable_plugin($plugin);
	} elseif (!$plugin_enabled && in_array($plugin, $enabled_plugins)) {
		$success = $success && enable_plugin($plugin);
	}
}

if ($success) {
	elgg_delete_admin_notice('first_installation_plugin_reminder');
	system_message(elgg_echo('admin:plugins:simple_simple_success'));
} else {
	register_error(elgg_echo('admins:plugins:simple_simple_fail'));
}

forward($_SERVER['HTTP_REFERER']);