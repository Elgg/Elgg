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
foreach ($simple_settings as $setting) {
	elgg_set_plugin_setting($setting, get_input($setting), 'developers');
}

elgg_invalidate_caches();

return elgg_ok_response('', elgg_echo('developers:settings:success'));
