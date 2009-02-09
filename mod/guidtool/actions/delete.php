<?php
	global $CONFIG;
	
	admin_gatekeeper();
	action_gatekeeper();
	
	$guid = (int)get_input('guid');
	$entity = get_entity($guid);
	
	if ($entity)
	{
		if ($entity->delete())
			system_message(sprintf(elgg_echo('guidtool:deleted'), $guid));
		else
			register_error(sprintf(elgg_echo('guidtool:notdeleted'), $guid));
	}
	else
		register_error(sprintf(elgg_echo('guidtool:notdeleted'), $guid));
		
	forward($_SERVER['HTTP_REFERER']);
?>