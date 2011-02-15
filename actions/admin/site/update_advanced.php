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

if (datalist_get('default_site')) {
	$site = get_entity(datalist_get('default_site'));
	if (!($site instanceof ElggSite)) {
		throw new InstallationException(elgg_echo('InvalidParameterException:NonElggSite'));
	}

	$site->url = get_input('wwwroot');

	datalist_set('path', sanitise_filepath(get_input('path')));
	datalist_set('dataroot', sanitise_filepath(get_input('dataroot')));

	if (get_input('simplecache_enabled')) {
		elgg_enable_simplecache();
	} else {
		elgg_disable_simplecache();
	}

	if (get_input('viewpath_cache_enabled')) {
		elgg_enable_filepath_cache();
	} else {
		elgg_disable_filepath_cache();
	}

	set_config('default_access', get_input('default_access', ACCESS_PRIVATE), $site->getGUID());

	$user_default_access = (get_input('allow_user_default_access')) ? 1 : 0;
	set_config('allow_user_default_access', $user_default_access, $site->getGUID());

	set_config('view', get_input('view'), $site->getGUID());

	$debug = get_input('debug');
	if ($debug) {
		set_config('debug', $debug, $site->getGUID());
	} else {
		unset_config('debug', $site->getGUID());
	}

	// allow new user registration?
	if (get_input('allow_registration', FALSE)) {
		set_config('allow_registration', TRUE, $site->getGUID());
	} else {
		set_config('allow_registration', FALSE, $site->getGUID());
	}

	// setup walled garden
	if (get_input('walled_garden', FALSE)) {
		set_config('walled_garden', TRUE, $site->getGUID());
	} else {
		set_config('walled_garden', FALSE, $site->getGUID());
	}

	$https_login = get_input('https_login');
	if ($https_login) {
		set_config('https_login', 1, $site->getGUID());
	} else {
		unset_config('https_login', $site->getGUID());
	}

	$api = get_input('api');
	if ($api) {
		unset_config('disable_api', $site->getGUID());
	} else {
		set_config('disable_api', 'disabled', $site->getGUID());
	}

	if ($site->save()) {
		system_message(elgg_echo("admin:configuration:success"));
	} else {
		register_error(elgg_echo("admin:configuration:fail"));
	}

	forward(REFERER);
}