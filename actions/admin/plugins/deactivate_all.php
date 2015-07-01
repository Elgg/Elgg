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

if (empty($guids)) {
	$plugins = elgg_get_plugins('active');
} else {
	$plugins = elgg_get_entities([
		'type' => 'object',
		'subtype' => 'plugin',
		'guids' => explode(',', $guids),
		'limit' => false
	]);
}

if (empty($plugins)) {
	forward(REFERER);
}

foreach ($plugins as $plugin) {
	if (!$plugin->isActive()) {
		continue;
	}
	
	if (!$plugin->deactivate()) {
		$msg = $plugin->getError();
		$string = ($msg) ? 'admin:plugins:deactivate:no_with_msg' : 'admin:plugins:deactivate:no';
		register_error(elgg_echo($string, array($plugin->getFriendlyName(), $plugin->getError())));
	}
}

// don't regenerate the simplecache because the plugin won't be
// loaded until next run.  Just invalidate and let it regnerate as needed
elgg_flush_caches();

forward(REFERER);
