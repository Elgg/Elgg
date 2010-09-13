<?php
/**
 * Bulk enable and disable for plugins appearing in the "simple" interface.
 *
 * Plugins marked as using the "simple" interface can be enabled and disabled
 * en masse by passing the enabled plugins as an array of their plugin ids
 * (directory names) through $_REQUEST['enabled_plugins'].  All "simple" plugins
 * not in this array will be disabled.
 *
 * Simplecache and views cache are reset.
 *
 * @uses array $_REQUEST['enabled_plugins'] An array of plugin ids (directory names) to enable.
 *
 * @since 1.8
 * @package Elgg.Core
 * @subpackage Administration.Site
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

// need to reset caches for new view locations and cached view output.
elgg_view_regenerate_simplecache();
elgg_filepath_cache_reset();

forward($_SERVER['HTTP_REFERER']);