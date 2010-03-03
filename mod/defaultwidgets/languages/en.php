<?php

$english = array (

	/**
	 * Nice name for the entity (shown in admin panel)
	 */
	'item:object:moddefaultwidgets' => 'DefaultWidgets settings',

	/**
	 * Menu items
	 */
	'defaultwidgets:menu:profile' => 'Default profile widgets',
    'defaultwidgets:menu:dashboard' => 'Default dashboard widgets',

    'defaultwidgets:admin:error' => 'Error: You are not logged in as an administrator',
	'defaultwidgets:admin:notfound' => 'Error: Page not found',
	'defaultwidgets:admin:loginfailure' => 'Warning: You are not currently logged is as an administrator',

	'defaultwidgets:update:success' => 'Your widget settings have been saved',
	'defaultwidgets:update:failed' => 'Error: settings have not been saved',
	'defaultwidgets:update:noparams' => 'Error: incorrect form parameters',

	'defaultwidgets:profile:title' => 'Set default widgets for new user profile pages',
	'defaultwidgets:dashboard:title' => 'Set default widgets for new user dashboard pages',
);

add_translation ( "en", $english );
