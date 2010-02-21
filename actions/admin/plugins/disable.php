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

// Get the plugin
$plugin = get_input('plugin');
if (!is_array($plugin)) {
	$plugin = array($plugin);
}

foreach ($plugin as $p) {
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
