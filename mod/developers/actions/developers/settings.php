<?php
/**
 * Save the developer settings
 */

if (!elgg()->config->hasInitialValue('simplecache_enabled')) {
	if (get_input('simple_cache')) {
		elgg_enable_simplecache();
	} else {
		elgg_disable_simplecache();
	}
}

if (get_input('system_cache')) {
	elgg_enable_system_cache();
} else {
	elgg_disable_system_cache();
}

if (!elgg()->config->hasInitialValue('debug')) {
	$debug = get_input('debug_level');
	if ($debug) {
		elgg_save_config('debug', $debug);
	} else {
		elgg_remove_config('debug');
	}
}

$simple_settings = [
	'display_errors',
	'screen_log',
	'show_strings',
	'wrap_views',
	'log_events',
	'show_gear',
	'show_modules',
	'block_email',
	'forward_email',
	'enable_error_log',
];

$plugin = elgg_get_plugin_from_id('developers');
foreach ($simple_settings as $setting) {
	$plugin->setSetting($setting, get_input($setting));
}

elgg_invalidate_caches();

return elgg_ok_response('', elgg_echo('developers:settings:success'));
