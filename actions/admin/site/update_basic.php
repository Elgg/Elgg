<?php
/**
 * Elgg update site action
 *
 * This is an update version of the sitesettings/install action
 * which is used by the admin panel to modify basic settings.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

global $CONFIG;

// block non-admin users
admin_gatekeeper();

if (get_input('settings') == 'go') {
	if (datalist_get('default_site')) {
		$site = get_entity(datalist_get('default_site'));
		if (!($site instanceof ElggSite)) {
			throw new InstallationException(elgg_echo('InvalidParameterException:NonElggSite'));
		}

		$site->description = get_input('sitedescription');
		$site->name = get_input('sitename');
		$site->email = get_input('siteemail');
		$site->url = get_input('wwwroot');

		datalist_set('path',sanitise_filepath(get_input('path')));
		datalist_set('dataroot',sanitise_filepath(get_input('dataroot')));
		if (get_input('simplecache_enabled')) {
			elgg_view_enable_simplecache();
		} else {
			elgg_view_disable_simplecache();
		}
		if (get_input('viewpath_cache_enabled')) {
			elgg_enable_filepath_cache();
		} else {
			elgg_disable_filepath_cache();
		}

		set_config('language', get_input('language'), $site->getGUID());

		set_config('default_access', get_input('default_access'), $site->getGUID());

		if (get_input('allow_user_default_access')) {
			set_config('allow_user_default_access', 1, $site->getGUID());
		} else {
			set_config('allow_user_default_access', 0, $site->getGUID());
		}

		set_config('view', get_input('view'), $site->getGUID());

		$debug = get_input('debug');
		if ($debug) {
			set_config('debug', $debug, $site->getGUID());
		} else {
			unset_config('debug', $site->getGUID());
		}

		$https_login = get_input('https_login');
		if ($https_login) {
			set_config('https_login', 1, $site->getGUID());
		} else {
			unset_config('https_login', $site->getGUID());
		}

		$usage = get_input('usage');
		if ($usage) {
			unset_config('ping_home', $site->getGUID());
		} else {
			set_config('ping_home', 'disabled', $site->getGUID());
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

		forward($_SERVER['HTTP_REFERER']);
		exit;
	}
}
