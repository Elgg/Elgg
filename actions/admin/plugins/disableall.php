<?php
/**
 * Disable plugin action.
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
	// Disable
	if (disable_plugin($p)) {
		system_message(sprintf(elgg_echo('admin:plugins:disable:yes'), $p));
	} else {
		register_error(sprintf(elgg_echo('admin:plugins:disable:no'), $p));
	}
}

elgg_view_regenerate_simplecache();
elgg_filepath_cache_reset();

forward($_SERVER['HTTP_REFERER']);
exit;
