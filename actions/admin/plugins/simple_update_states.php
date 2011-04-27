<?php
/**
 * Bulk activate/deactivate for plugins appearing in the "simple" interface.
 *
 * Plugins marked as using the "simple" interface can be activated/deactivated
 * en masse by passing the plugins to activate as an array of their plugin guids
 * in $_REQUEST['enabled_plugins'].  All "simple" plugins not in this array will be
 * deactivated.
 *
 * Simplecache and views cache are reset.
 *
 * @uses array $_REQUEST['activated_plugin_guids'] Array of plugin guids to activate.
 *
 * @since 1.8
 * @package Elgg.Core
 * @subpackage Administration.Plugins
 */

$active_plugin_guids = get_input('active_plugin_guids', array());
$installed_plugins = elgg_get_plugins('any');
$success = TRUE;

foreach ($installed_plugins as $plugin) {
	// this is only for simple plugins.
	if ($plugin->getManifest()->getAdminInterface() != 'simple') {
		continue;
	}

	// only effect changes to plugins not already in that state.
	if ($plugin->isActive() && !in_array($plugin->guid, $active_plugin_guids)) {
		$success = $success && $plugin->deactivate();
	} elseif (!$plugin->isActive()  && in_array($plugin->guid, $active_plugin_guids)) {
		$success = $success && $plugin->activate();
	}
}

if ($success) {
	//system_message(elgg_echo('admin:plugins:simple_simple_success'));
} else {
	register_error(elgg_echo('admin:plugins:simple_simple_fail'));
}

// don't regenerate the simplecache because the plugin won't be
// loaded until next run.  Just invalidate and let it regnerate as needed
elgg_invalidate_simplecache();
elgg_filepath_cache_reset();

forward(REFERER);