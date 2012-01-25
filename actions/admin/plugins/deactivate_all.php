<?php
/**
 * Disable all specified installed plugins.
 *
 * Specified plugins in the mod/ directory are disabled and the views cache and simplecache
 * are reset.
 *
 * @package Elgg.Core
 * @subpackage Administration.Plugins
 */

$guids = get_input('guids');
$guids = explode(',', $guids);

foreach ($guids as $guid) {
	$plugin = get_entity($guid);
	if ($plugin->isActive()) {
		if ($plugin->deactivate()) {
			//system_message(elgg_echo('admin:plugins:activate:yes', array($plugin->getManifest()->getName())));
		} else {
			$msg = $plugin->getError();
			$string = ($msg) ? 'admin:plugins:deactivate:no_with_msg' : 'admin:plugins:deactivate:no';
			register_error(elgg_echo($string, array($plugin->getFriendlyName(), $plugin->getError())));
		}
	}
}

// don't regenerate the simplecache because the plugin won't be
// loaded until next run.  Just invalidate and let it regnerate as needed
elgg_invalidate_simplecache();
elgg_reset_system_cache();

forward(REFERER);
