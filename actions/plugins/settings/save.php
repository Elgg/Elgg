<?php
/**
 * Elgg plugin settings save action.
 *
 * @package Elgg
 * @subpackage Core
 */

$params = get_input('params');
$plugin = get_input('plugin');
if (!$plugin_info = load_plugin_manifest($plugin)) {
	register_error(sprintf(elgg_echo('plugins:settings:save:fail'), $plugin));
	forward($_SERVER['HTTP_REFERER']);
}

$plugin_name = $plugin_info['name'];

admin_gatekeeper();

$result = false;

$options = array(
	'plugin' => $plugin,
	'manifest' => $plugin_info,
	'settings' => $params
);

// allow a plugin to override the save action for their settings
if (elgg_action_exist("settings/$plugin/save")) {
	action("settings/$plugin/save");
} else {
	foreach ($params as $k => $v) {
		if (!$result = set_plugin_setting($k, $v, $plugin)) {
			register_error(sprintf(elgg_echo('plugins:settings:save:fail'), $plugin_name));
			forward($_SERVER['HTTP_REFERER']);
			exit;
		}
	}
}

system_message(sprintf(elgg_echo('plugins:settings:save:ok'), $plugin_name));
forward($_SERVER['HTTP_REFERER']);
//
//$trigger = trigger_plugin_hook('plugin:save_settings', $plugin, $options, NULL);
//if ($trigger === NULL) {
//	foreach ($params as $k => $v) {
//		if (!$result = set_plugin_setting($k, $v, $plugin)) {
//			register_error(sprintf(elgg_echo('plugins:settings:save:fail'), $plugin_name));
//			forward($_SERVER['HTTP_REFERER']);
//			exit;
//		}
//	}
//} elseif ($trigger === FALSE) {
//	register_error(sprintf(elgg_echo('plugins:settings:save:fail'), $plugin_name));
//	forward($_SERVER['HTTP_REFERER']);
//}
//
//system_message(sprintf(elgg_echo('plugins:settings:save:ok'), $plugin_name));
//forward($_SERVER['HTTP_REFERER']);