<?php
	/**
	 * Elgg profile plugin edit default profile action removal
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */
		
	global $CONFIG;
	
	admin_gatekeeper();
	
	$id = (int)get_input('id');
	
	if ( ($id) && (set_plugin_setting("admin_defined_profile_$id", '', 'profile')) && 
			(set_plugin_setting("admin_defined_profile_type_$id", '', 'profile')))
			system_message(elgg_echo('profile:editdefault:delete:success'));
		else
			register_error(elgg_echo('profile:editdefault:delete:fail'));
	
	forward($_SERVER['HTTP_REFERER']);
?>
