<?php
/**
 * Save the developer settings
 */

$site = elgg_get_site_entity();

if (get_input('simple_cache')) {
	elgg_enable_simplecache();
} else {
	elgg_disable_simplecache();
}

if (get_input('system_cache')) {
	elgg_enable_system_cache();
} else {
	elgg_disable_system_cache();
}

$debug = get_input('debug_level');
if ($debug) {
	set_config('debug', $debug, $site->getGUID());
} else {
	unset_config('debug', $site->getGUID());
}

$simple_settings = array('display_errors', 'screen_log', 'show_strings',
	'wrap_views', 'log_events',);
foreach ($simple_settings as $setting) {
	elgg_set_plugin_setting($setting, get_input($setting), 'developers');
}

system_message(elgg_echo('developers:settings:success'));

forward(REFERER);
