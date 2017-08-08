<?php
/**
 * Deactivate a plugin or plugins.
 *
 * Plugins to be deactivated are passed via $_REQUEST['plugin_guids'] as GUIDs.
 * After deactivating the plugin(s), the views cache and simplecache are invalidated.
 *
 * @uses mixed $_GET['plugin_guids'] The GUIDs of the plugin to deactivate. Can be an array.
 */

$plugin_guids = get_input('plugin_guids');

if (!is_array($plugin_guids)) {
	$plugin_guids = [$plugin_guids];
}

$deactivated_plugins = [];
foreach ($plugin_guids as $guid) {
	$plugin = get_entity($guid);

	if (!($plugin instanceof ElggPlugin)) {
		register_error(elgg_echo('admin:plugins:deactivate:no', [$guid]));
		continue;
	}

	if (!$plugin->deactivate()) {
		$msg = $plugin->getError();
		$string = ($msg) ? 'admin:plugins:deactivate:no_with_msg' : 'admin:plugins:deactivate:no';
		register_error(elgg_echo($string, [$plugin->getDisplayName(), $msg]));
		continue;
	}
	
	$deactivated_plugins[] = $plugin;
}

if (empty($deactivated_plugins)) {
	return elgg_error_response();
}

if (count($deactivated_plugins) == 1) {
	$plugin = $deactivated_plugins[0];
	
	$url = elgg_http_build_url([
		'path' => 'admin/plugins',
		'query' => parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY),
		'fragment' => preg_replace('/[^a-z0-9-]/i', '-', $plugin->getID()),
	]);
		
	return elgg_ok_response('', '', $url);
}

return elgg_ok_response();
