<?php
/**
 * Activate a plugin or plugins.
 *
 * Plugins to be activated are passed via $_REQUEST['plugin_guids'] as GUIDs.
 * After activating the plugin(s), the views cache and simplecache are invalidated.
 *
 * @uses mixed $_GET['plugin_guids'] The GUIDs of the plugin to activate. Can be an array.
 *
 * @package Elgg.Core
 * @subpackage Administration.Plugins
 */

$plugin_guids = get_input('plugin_guids');

if (!is_array($plugin_guids)) {
	$plugin_guids = array($plugin_guids);
}

$activated_guids = array();
foreach ($plugin_guids as $guid) {
	$plugin = get_entity($guid);

	if (!($plugin instanceof ElggPlugin)) {
		register_error(elgg_echo('admin:plugins:activate:no', array($guid)));
		continue;
	}

	if ($plugin->activate()) {
		$activated_guids[] = $guid;
	} else {
		register_error(elgg_echo('admin:plugins:activate:no', array($plugin->getManifest()->getName())));
	}
}

// don't regenerate the simplecache because the plugin won't be
// loaded until next run.  Just invalidate and let it regenerate as needed
elgg_invalidate_simplecache();
elgg_filepath_cache_reset();

if (count($activated_guids) === 1) {
	$url = 'admin/plugins';
	$query = (string)parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY);
	if ($query) {
		$url .= "?$query";
	}
	forward($url . '#elgg-plugin-' . $plugin_guids[0]);
} else {
	forward(REFERER);
}