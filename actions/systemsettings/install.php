<?php
/**
 * Elgg install site action
 *
 * Creates a nwe site and sets it as the default
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

define('INSTALLING', TRUE);
elgg_set_viewtype('failsafe'); // Set failsafe again incase we get an exception thrown

if (is_installed()) {
	forward();
}

if (get_input('settings') == 'go') {
	if (!datalist_get('default_site')) {
		// Sanitise
		$path = sanitise_filepath(get_input('path'));
		$dataroot = sanitise_filepath(get_input('dataroot'));
		$url = sanitise_filepath(get_input('wwwroot'));

		// Blank?
		if ($dataroot == "/") {
			throw new InstallationException(elgg_echo('InstallationException:DatarootBlank'));
		}

		// That it's valid
		if (stripos($dataroot, $path)!==false) {
			throw new InstallationException(sprintf(elgg_echo('InstallationException:DatarootUnderPath'), $dataroot));
		}

		// Check data root is writable
		if (!is_writable($dataroot)) {
			throw new InstallationException(sprintf(elgg_echo('InstallationException:DatarootNotWritable'), $dataroot));
		}

		$site = new ElggSite();
		$site->name = get_input('sitename');
		$site->url = $url;
		$site->description = get_input('sitedescription');
		$site->email = get_input('siteemail');
		$site->access_id = ACCESS_PUBLIC;
		$guid = $site->save();

		if (!$guid) {
			throw new InstallationException(sprintf(elgg_echo('InstallationException:CantCreateSite'), get_input('sitename'), get_input('wwwroot')));
		}

		datalist_set('installed',time());
		datalist_set('path', $path);
		datalist_set('dataroot', $dataroot);
		datalist_set('default_site', $site->getGUID());
		datalist_set('version', get_version());

		set_config('view', get_input('view'), $site->getGUID());
		set_config('language', get_input('language'), $site->getGUID());
		set_config('default_access', get_input('default_access'), $site->getGUID());

		$debug = get_input('debug');
		if ($debug) {
			set_config('debug', $debug, $site->getGUID());
		} else {
			unset_config('debug', $site->getGUID());
		}

		$api = get_input('api');
		if ($api) {
			unset_config('disable_api', $site->getGUID());
		} else {
			set_config('disable_api', 'disabled', $site->getGUID());
		}

		$https_login = get_input('https_login');
		if ($https_login) {
			set_config('https_login', 1, $site->getGUID());
		} else {
			unset_config('https_login', $site->getGUID());
		}

		// activate some plugins by default
		if (isset($CONFIG->default_plugins)) {
			if (!is_array($CONFIG->default_plugins)) {
				$plugins = explode(',', $CONFIG->default_plugins);
			} else {
				$CONFIG->default_plugins = $CONFIG->default_plugins;
			}

			foreach ($plugins as $plugin){
				enable_plugin(trim($plugin), $site->getGUID());
			}
		} else {
			enable_plugin('profile', $site->getGUID());
			enable_plugin('logbrowser', $site->getGUID());
			enable_plugin('diagnostics', $site->getGUID());
			enable_plugin('uservalidationbyemail', $site->getGUID());
			enable_plugin('htmlawed', $site->getGUID());
			enable_plugin('search', $site->getGUID());
		}

		// reset the views path in case of installing over an old data dir.
		// @todo should this warn / error first?
		$dataroot = datalist_get('dataroot');
		$cache = new ElggFileCache($dataroot);
		$cache->delete('view_paths');

		system_message(elgg_echo("installation:configuration:success"));

		header("Location: ../../account/register.php");
		exit;
	}
}
