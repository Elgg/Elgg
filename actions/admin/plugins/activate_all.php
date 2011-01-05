<?php
/**
 * Activates all installed and inactive plugins.
 *
 * All plugins in the mod/ directory are that aren't active are activated and the views
 * cache and simplecache are invalidated.
 *
 * @package Elgg.Core
 * @subpackage Administration.Plugins
 */

$plugins = elgg_get_plugins('inactive');

foreach ($plugins as $plugin) {
	if ($plugin->activate()) {
		//system_message(elgg_echo('admin:plugins:activate:yes', array($plugin->manifest->getName())));
	} else {
		register_error(elgg_echo('admin:plugins:activate:no', array($plugin->manifest->getName())));
	}
}

elgg_delete_admin_notice('first_installation_plugin_reminder');

// don't regenerate the simplecache because the plugin won't be
// loaded until next run.  Just invalidate and let it regnerate as needed
elgg_invalidate_simplecache();
elgg_filepath_cache_reset();

forward(REFERER);