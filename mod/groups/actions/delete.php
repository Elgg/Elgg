<?php
	global $CONFIG;
		
	$guid = (int)get_input('group_guid');
	$entity = get_entity($guid);
	
	if (($entity) && ($entity instanceof ElggGroup))
	{
		if ($entity->delete())
			system_message(elgg_echo('group:deleted'));
		else
			register_error(elgg_echo('group:notdeleted'));
	}
	else
		register_error(elgg_echo('group:notdeleted'));
		
	$url_name = $_SESSION['user']->username;
	forward("{$vars['url']}pg/groups/member/{$url_name}");
?>