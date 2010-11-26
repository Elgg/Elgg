<?php
	
	/**
	 * Join a group action.
	 * 
	 * @package ElggGroups
	 */

	// Load configuration
	global $CONFIG;
	
	admin_gatekeeper();
	
	$group_guid = get_input('group_guid');
	$action = get_input('action_type');
	
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
	
	forward(REFERER);
	
?>