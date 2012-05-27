<?php
/**
 *  Copyright (C) 2012 Quanbit Software S.A.
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 */

/**
 * Plugin initialization:
 *  - Create default settings if absent
 *  - Register actions
 * 
 * @author andres
 */

elgg_register_event_handler('init', 'system', 'phpunit_tests_init');

function phpunit_tests_init() 
{
	setDefaultPluginSettings();
	registerPluginActions();
}

/**
 * Check if a given plugin setting is present. If not, assign a default value.
 * 
 * @param string $name - The name of the setting
 * @param string $defaultValue - The default value
 */
function setPluginSettingIfAbsent($name, $defaultValue)
{
	$value = elgg_get_plugin_setting($name, 'phpunit_tests');
	if ($value == null)
	{
		elgg_set_plugin_setting($name, $defaultValue, 'phpunit_tests');
	}
}

/**
 * Initialize all plugin settings to their default values if absent.  
 */
function setDefaultPluginSettings()
{
	global $CONFIG;
	$values = array (
						'site:dbuser' => $CONFIG->dbuser, 
						'site:dbpass' => $CONFIG->dbpass,
						'site:dbname' => 'test_' . $CONFIG->dbname,
						'site:dbhost' => $CONFIG->dbhost,
						'site:dbprefix' => $CONFIG->dbprefix,
						'site:sitename' => $CONFIG->sitename,
						'site:siteemail' => $CONFIG->siteemail,
						'site:dataroot' => sys_get_temp_dir() . '/elgg_test_data/',
						'site:wwwroot' => $CONFIG->wwwroot,

						//Admin account
						'admin:displayname' => 'Test Admin',
						'admin:email' => 'admin@localhost',
						'admin:username' => 'elgg_test_admin_username',
						'admin:password' => 'elgg_test_admin_password',
	
						//User account
						'user:displayname' => 'Test User',
						'user:email' => 'user@localhost',
						'user:username' => 'elgg_test_user_username',
						'user:password' => 'elgg_test_user_password'
					);
	
	foreach ($values as $key => $value)
	{
		setPluginSettingIfAbsent($key, $value);
	}	
}

/**
 * Register the Save settings action
 */
function registerPluginActions()
{
	$action_base = elgg_get_plugins_path() . 'phpunit_tests/actions/phpunit_tests';
	elgg_register_action("phpunit_tests/settings/save", "$action_base/save_settings.php");
}