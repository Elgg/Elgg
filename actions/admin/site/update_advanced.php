<?php
/**
 * Updates the advanced settings for the primary site object.
 *
 * Options are saved among metadata on the site object, entries
 * in the datalist table, and entries in the config table.
 *
 * @package Elgg.Core
 * @subpackage Administration.Site
 */

if ($site = elgg_get_site_entity()) {
	if (!($site instanceof ElggSite)) {
		throw new InstallationException("Passing a non-ElggSite to an ElggSite constructor!");
	}

	$site->url = rtrim(get_input('wwwroot', '', false), '/') . '/';

	datalist_set('path', sanitise_filepath(get_input('path', '', false)));
	$dataroot = sanitise_filepath(get_input('dataroot', '', false));

	// check for relative paths
	if (stripos(PHP_OS, 'win') === 0) {
		if (strpos($dataroot, ':') !== 1) {
			$msg = elgg_echo('admin:configuration:dataroot:relative_path', array($dataroot));
			register_error($msg);
			forward(REFERER);
		}
	} else {
		if (strpos($dataroot, '/') !== 0) {
			$msg = elgg_echo('admin:configuration:dataroot:relative_path', array($dataroot));
			register_error($msg);
			forward(REFERER);
		}
	}

	datalist_set('dataroot', $dataroot);

	if ('on' === get_input('simplecache_enabled')) {
		elgg_enable_simplecache();
	} else {
		elgg_disable_simplecache();
	}

	set_config('simplecache_minify_js', 'on' === get_input('simplecache_minify_js'), $site->getGUID());
	set_config('simplecache_minify_css', 'on' === get_input('simplecache_minify_css'), $site->getGUID());

	if ('on' === get_input('system_cache_enabled')) {
		elgg_enable_system_cache();
	} else {
		elgg_disable_system_cache();
	}

	set_config('default_access', get_input('default_access', ACCESS_PRIVATE), $site->getGUID());

	$user_default_access = ('on' === get_input('allow_user_default_access'));
	set_config('allow_user_default_access', $user_default_access, $site->getGUID());

	$debug = get_input('debug');
	if ($debug) {
		set_config('debug', $debug, $site->getGUID());
	} else {
		unset_config('debug', $site->getGUID());
	}

	// allow new user registration?
	$allow_registration = ('on' === get_input('allow_registration', false));
	set_config('allow_registration', $allow_registration, $site->getGUID());

	// setup walled garden
	$walled_garden = ('on' === get_input('walled_garden', false));
	set_config('walled_garden', $walled_garden, $site->getGUID());

	if ('on' === get_input('https_login')) {
		set_config('https_login', 1, $site->getGUID());
	} else {
		unset_config('https_login', $site->getGUID());
	}

	$regenerate_site_secret = get_input('regenerate_site_secret', false);
	if ($regenerate_site_secret) {
		init_site_secret();
		elgg_reset_system_cache();

		system_message(elgg_echo('admin:site:secret_regenerated'));

		elgg_delete_admin_notice('weak_site_key');
	}

	if ($site->save()) {
		system_message(elgg_echo("admin:configuration:success"));
	} else {
		register_error(elgg_echo("admin:configuration:fail"));
	}

	forward(REFERER);
}