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

if (get_input('view_path_cache')) {
	elgg_enable_filepath_cache();
} else {
	elgg_disable_filepath_cache();
}

elgg_set_plugin_setting('display_errors', get_input('display_errors'), 'developers');

$debug = get_input('debug_level');
if ($debug) {
	set_config('debug', $debug, $site->getGUID());
} else {
	unset_config('debug', $site->getGUID());
}

forward(REFERER);
