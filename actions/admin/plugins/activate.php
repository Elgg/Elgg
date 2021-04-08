<?php
/**
 * Activate a plugin or plugins.
 *
 * Plugins to be activated are passed via $_REQUEST['plugin_guids'] as GUIDs.
 * After activating the plugin(s), the views cache and simplecache are invalidated.
 *
 * @uses mixed $_GET['plugin_guids'] The GUIDs of the plugin to activate. Can be an array.
 */

$plugin_guids = get_input('plugin_guids');

if (!is_array($plugin_guids)) {
	$plugin_guids = [$plugin_guids];
}

$activated_plugins = [];
foreach ($plugin_guids as $guid) {
	$plugin = get_entity($guid);

	if (!($plugin instanceof ElggPlugin)) {
		register_error(elgg_echo('admin:plugins:activate:no', [$guid]));
		continue;
	}

	try {
		if (!$plugin->activate()) {
			register_error(elgg_echo('admin:plugins:activate:no', [$plugin->getDisplayName()]));
			continue;
		}
	} catch (\Elgg\Exceptions\PluginException $e) {
		register_error(elgg_echo('admin:plugins:activate:no_with_msg', [$plugin->getDisplayName(), $e->getMessage()]));
		continue;
	}
	
	$ids = [
		'cannot_start' . $plugin->getID(),
		'invalid_and_deactivated_' . $plugin->getID()
	];

	foreach ($ids as $id) {
		elgg_delete_admin_notice($id);
	}
	
	$activated_plugins[] = $plugin;
}

if (empty($activated_plugins)) {
	return elgg_error_response();
}

if (count($activated_plugins) === 1) {
	$plugin = $activated_plugins[0];
	
	$url = elgg_http_build_url([
		'path' => 'admin/plugins',
		'query' => parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY),
		'fragment' => preg_replace('/[^a-z0-9-]/i', '-', $plugin->getID()),
	]);
		
	return elgg_ok_response('', '', $url);
}

return elgg_ok_response();
