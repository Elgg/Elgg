<?php
	/**
	 * Elgg profile plugin edit default profile action
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */
		
	// Load configuration
	global $CONFIG;
		
	action_gatekeeper();
	admin_gatekeeper();
	
	$n = 0;
	while (get_plugin_setting("admin_defined_profile_$n", 'profile')) {
		set_plugin_setting("admin_defined_profile_$n", '', 'profile');
		set_plugin_setting("admin_defined_profile_type_$n", '', 'profile');
		
		$n++;
	}
	
	system_message(elgg_echo('profile:defaultprofile:reset'));
	
	forward($_SERVER['HTTP_REFERER']);
?>