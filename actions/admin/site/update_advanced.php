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

if ('on' === get_input('simplecache_enabled')) {
	elgg_enable_simplecache();
} else {
	elgg_disable_simplecache();
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

$debug = get_input('debug');
if ($debug) {
	elgg_save_config('debug', $debug);
} else {
	elgg_remove_config('debug');
}

// allow new user registration?
$allow_registration = ('on' === get_input('allow_registration', false));
elgg_save_config('allow_registration', $allow_registration);

// setup walled garden
$walled_garden = ('on' === get_input('walled_garden', false));
elgg_save_config('walled_garden', $walled_garden);

$regenerate_site_secret = get_input('regenerate_site_secret', false);
if ($regenerate_site_secret) {
	// if you cancel this even you should present a message to the user
	if (elgg_trigger_before_event('regenerate_site_secret', 'system')) {
		init_site_secret();
		elgg_reset_system_cache();
		elgg_trigger_after_event('regenerate_site_secret', 'system');

		system_message(elgg_echo('admin:site:secret_regenerated'));

		elgg_delete_admin_notice('weak_site_key');
	}
}

if ($site->save()) {
	system_message(elgg_echo("admin:configuration:success"));
} else {
	register_error(elgg_echo("admin:configuration:fail"));
}

forward(REFERER);
