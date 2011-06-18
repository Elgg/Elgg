<?php
/**
 * Activates all specified installed and inactive plugins.
 *
 * All specified plugins in the mod/ directory are that aren't active are activated and the views
 * cache and simplecache are invalidated.
 *
 * @package Elgg.Core
 * @subpackage Administration.Plugins
 */

$guids = get_input('guids');
$guids = explode(',', $guids);

foreach ($guids as $guid) {
	$plugin = get_entity($guid);
	if (!$plugin->isActive()) {
		if ($plugin->activate()) {
			//system_message(elgg_echo('admin:plugins:activate:yes', array($plugin->getManifest()->getName())));
		} else {
			register_error(elgg_echo('admin:plugins:activate:no', array($plugin->getManifest()->getName())));
		}
	}
}

// don't regenerate the simplecache because the plugin won't be
// loaded until next run.  Just invalidate and let it regnerate as needed
elgg_invalidate_simplecache();
elgg_filepath_cache_reset();

forward(REFERER);