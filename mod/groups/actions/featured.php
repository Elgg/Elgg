<?php
	
	/**
	 * Join a group action.
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	// Load configuration
	global $CONFIG;
	
	admin_gatekeeper();
	
	$group_guid = get_input('group_guid');
	$action = get_input('action');
	
	$group = get_entity($group_guid);
	
	if($group){
		
		//get the action, is it to feature or unfeature
		if($action == "feature"){
		
			$group->featured_group = "yes";
			system_message(elgg_echo('groups:featuredon'));
			
		}
		
		if($action == "unfeature"){
			
			$group->featured_group = "no";
			system_message(elgg_echo('groups:unfeatured'));
			
		}
		
	}
	
	forward("pg/groups/world/");
	
?>