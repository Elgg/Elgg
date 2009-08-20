<?php
	/**
	 * Elgg plugin settings save action.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Curverider Ltd
	 * @link http://elgg.org/
	 */

	$params = get_input('params');
	$plugin = get_input('plugin');

	gatekeeper();
	action_gatekeeper();
	
	$result = false;
	
	foreach ($params as $k => $v)
	{
		// Save
		$result = set_plugin_setting($k, $v, $plugin);
		
		// Error?
		if (!$result)
		{
			register_error(sprintf(elgg_echo('plugins:settings:save:fail'), $plugin));
			
			forward($_SERVER['HTTP_REFERER']);
			
			exit;
		}
	}

	// An event to tell any interested plugins of the change is settings
	//trigger_elgg_event('plugin_settings_save', $plugin, find_plugin_settings($plugin)); // replaced by plugin:setting event
	
	system_message(sprintf(elgg_echo('plugins:settings:save:ok'), $plugin));
	forward($_SERVER['HTTP_REFERER']);
?>