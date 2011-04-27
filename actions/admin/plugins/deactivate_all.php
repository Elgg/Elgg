<?php
/**
 * Disable all installed plugins.
 *
 * All plugins in the mod/ directory are disabled and the views cache and simplecache
 * are reset.
 *
 * @package Elgg.Core
 * @subpackage Administration.Plugins
 */

$plugins = elgg_get_plugins('active');

foreach ($plugins as $plugin) {
	if ($plugin->deactivate()) {
		//system_message(elgg_echo('admin:plugins:deactivate:yes', array($plugin->getManifest()->getName())));
	} else {
		register_error(elgg_echo('admin:plugins:deactivate:no', array($plugin->getManifest()->getName())));
	}
}

// don't regenerate the simplecache because the plugin won't be
// loaded until next run.  Just invalidate and let it regnerate as needed
elgg_invalidate_simplecache();
elgg_filepath_cache_reset();

forward(REFERER);
