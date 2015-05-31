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
		$ids = array(
			'cannot_start' . $plugin->getID(),
			'invalid_and_deactivated_' . $plugin->getID()
		);

		foreach ($ids as $id) {
			elgg_delete_admin_notice($id);
		}

	} else {
		$msg = $plugin->getError();
		$string = ($msg) ? 'admin:plugins:activate:no_with_msg' : 'admin:plugins:activate:no';
		register_error(elgg_echo($string, array($plugin->getFriendlyName(), $plugin->getError())));
	}
}

// don't regenerate the simplecache because the plugin won't be
// loaded until next run.  Just invalidate and let it regenerate as needed
elgg_flush_caches();

if (count($activated_guids) === 1) {
	$url = 'admin/plugins';
	$query = (string)parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY);
	if ($query) {
		$url .= "?$query";
	}
	$plugin = get_entity($plugin_guids[0]);
	$id = $css_id = preg_replace('/[^a-z0-9-]/i', '-', $plugin->getID());
	forward("$url#$id");
} else {
	// forward to top of page with a failure so remove any #foo
	$url = $_SERVER['HTTP_REFERER'];
	if (strpos($url, '#')) {
		$url = substr(0, strpos($url, '#'));
	}
	forward($url);
}