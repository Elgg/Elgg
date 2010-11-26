<?php
/**
 * Enable plugin action.
 *
 * @package Elgg
 * @subpackage Core
 */

// block non-admin users
admin_gatekeeper();

// Get the plugin
$plugin = get_input('plugin');
if (!is_array($plugin)) {
	$plugin = array($plugin);
}

foreach ($plugin as $p) {
	// Disable
	if (enable_plugin($p)) {
		system_message(sprintf(elgg_echo('admin:plugins:enable:yes'), $p));
	} else {
		register_error(sprintf(elgg_echo('admin:plugins:enable:no'), $p));
	}
}

// don't regenerate the simplecache because the plugin won't be
// loaded until next run.  Just invalidate and let it regnerate as needed
elgg_invalidate_simplecache();
elgg_filepath_cache_reset();

forward($_SERVER['HTTP_REFERER']);
exit;
