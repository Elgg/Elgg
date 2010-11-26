<?php
/**
 * Disable plugin action.
 *
 * @package Elgg
 * @subpackage Core
 */

// block non-admin users
admin_gatekeeper();

$plugins = get_installed_plugins();

foreach ($plugins as $p => $data) {
	// Disable
	if (disable_plugin($p)) {
		system_message(sprintf(elgg_echo('admin:plugins:disable:yes'), $p));
	} else {
		register_error(sprintf(elgg_echo('admin:plugins:disable:no'), $p));
	}
}

// don't regenerate the simplecache because the plugin won't be
// loaded until next run.  Just invalidate and let it regnerate as needed
elgg_invalidate_simplecache();
elgg_filepath_cache_reset();

forward($_SERVER['HTTP_REFERER']);
exit;
