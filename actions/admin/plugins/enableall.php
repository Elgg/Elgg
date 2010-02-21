<?php
/**
 * Enable plugin action.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

// block non-admin users
admin_gatekeeper();

$plugins = get_installed_plugins();

foreach ($plugins as $p => $data) {
	// Enable
	if (enable_plugin($p)) {
		system_message(sprintf(elgg_echo('admin:plugins:enable:yes'), $p));
	} else {
		register_error(sprintf(elgg_echo('admin:plugins:enable:no'), $p));
	}
}

// Regen view cache
elgg_view_regenerate_simplecache();
elgg_filepath_cache_reset();

forward($_SERVER['HTTP_REFERER']);
exit;
