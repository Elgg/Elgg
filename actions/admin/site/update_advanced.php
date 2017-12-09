<?php
/**
 * Updates the advanced settings for the primary site object.
 *
 * Options are saved among metadata on the site object
 * and entries in the config table.
 *
 * @package Elgg.Core
 * @subpackage Administration.Site
 */

$site = elgg_get_site_entity();
if (!$site) {
	throw new InstallationException("The system is missing an ElggSite entity!");
}
if (!($site instanceof ElggSite)) {
	throw new InstallationException("Passing a non-ElggSite to an ElggSite constructor!");
}

if (!_elgg_config()->hasInitialValue('simplecache_enabled')) {
	if ('on' === get_input('simplecache_enabled')) {
		elgg_enable_simplecache();
	} else {
		elgg_disable_simplecache();
	}
}

if ('on' === get_input('cache_symlink_enabled')) {
	if (!_elgg_symlink_cache()) {
		register_error(elgg_echo('installation:cache_symlink:error'));
	}
}

elgg_save_config('simplecache_minify_js', 'on' === get_input('simplecache_minify_js'));
elgg_save_config('simplecache_minify_css', 'on' === get_input('simplecache_minify_css'));

if ('on' === get_input('system_cache_enabled')) {
	elgg_enable_system_cache();
} else {
	elgg_disable_system_cache();
}

elgg_save_config('default_access', (int) get_input('default_access', ACCESS_PRIVATE));

$user_default_access = ('on' === get_input('allow_user_default_access'));
elgg_save_config('allow_user_default_access', $user_default_access);

if (!_elgg_config()->hasInitialValue('debug')) {
	$debug = get_input('debug');
	if ($debug) {
		elgg_save_config('debug', $debug);
	} else {
		elgg_remove_config('debug');
	}
}

$remove_branding = ('on' === get_input('remove_branding', false));
elgg_save_config('remove_branding', $remove_branding);

$disable_rss = ('on' === get_input('disable_rss', false));
elgg_save_config('disable_rss', $disable_rss);

$friendly_time_number_of_days = get_input('friendly_time_number_of_days', 30);
if ($friendly_time_number_of_days === '') {
	$friendly_time_number_of_days = 30;
}
elgg_save_config('friendly_time_number_of_days', (int) $friendly_time_number_of_days);

if (!$site->save()) {
	return elgg_error_response(elgg_echo('admin:configuration:fail'));
}

return elgg_ok_response('', elgg_echo('admin:configuration:success'));
