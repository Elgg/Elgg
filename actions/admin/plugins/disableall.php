<?php
/**
 * Disable all installed plugins.
 *
 * All plugins in the mod/ directory are disabled and the views cache and simplecache
 * are reset.
 *
 * @package Elgg.Core
 * @subpackage Administration.Site
 */

admin_gatekeeper();

$plugins = get_installed_plugins();

foreach ($plugins as $p => $data) {
	if (disable_plugin($p)) {
		elgg_delete_admin_notice('first_installation_plugin_reminder');
		system_message(sprintf(elgg_echo('admin:plugins:disable:yes'), $p));
	} else {
		register_error(sprintf(elgg_echo('admin:plugins:disable:no'), $p));
	}
}

// don't regenerate the simplecache because the plugin won't be
// loaded until next run.  Just invalidate and let it regnerate as needed
elgg_invalidate_simplecache();
elgg_filepath_cache_reset();

forward(REFERER);
