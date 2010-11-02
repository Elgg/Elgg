<?php
/**
 * Disable a plugin or plugins.
 *
 * Plugins to be disabled are passed via $_REQUEST['plugin'] as plugin ID (directory name).
 * After disabling the plugin(s), the views cache and simplecache are both reset.
 *
 * @uses mixed $_GET['plugin'] The id (directory name) of the plugin to disable. Can be an array.
 *
 * @package Elgg.Core
 * @subpackage Administration.Site
 */

admin_gatekeeper();

$plugin = get_input('plugin');
if (!is_array($plugin)) {
	$plugin = array($plugin);
}

foreach ($plugin as $p) {
	if (disable_plugin($p)) {
		system_message(sprintf(elgg_echo('admin:plugins:disable:yes'), $p));
		elgg_delete_admin_notice('first_installation_plugin_reminder');
	} else {
		register_error(sprintf(elgg_echo('admin:plugins:disable:no'), $p));
	}
}

// don't regenerate the simplecache because the plugin won't be
// loaded until next run.  Just invalidate and let it regnerate as needed
elgg_invalidate_simplecache();
elgg_filepath_cache_reset();

forward(REFERER);
