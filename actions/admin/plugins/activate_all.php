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

$plugins = array();
foreach ($guids as $guid) {
	$plugin = get_entity($guid);
	$plugins[$plugin->getId()] = $plugin;
}

do {
	$success = false;
	foreach ($plugins as $key => $plugin) {
		if ($plugin->isActive()) {
			unset($plugins[$key]);
		} else {
			if ($plugin->activate()) {
				$success = true;
				unset($plugins[$key]);
			}
		}
	}
	if (!$success) {
		//no updates in this pass, break the loop
		break;
	}
} while (count($plugins) > 0);

if (count($plugins) > 0) {
	$names = implode(', ', array_keys($plugins));
	register_error(elgg_echo('admin:plugins:activate_all:no_with_msg', array($names)));
}

// don't regenerate the simplecache because the plugin won't be
// loaded until next run.  Just invalidate and let it regnerate as needed
elgg_invalidate_simplecache();
elgg_reset_system_cache();

forward(REFERER);