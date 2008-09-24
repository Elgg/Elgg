<?php

	global $CONFIG;
	
	admin_gatekeeper();
	action_gatekeeper();
	
	$key = (int)get_input('keyid');
	
	$obj = get_entity($key);
	
	if (($obj) && ($obj instanceof ElggObject) && ($obj->subtype == get_subtype_id('object', 'api_key')))
	{
		if ($obj->delete())
			system_message(elgg_echo('apiadmin:keyrevoked'));
		else
			register_error(elgg_echo('apiadmin:keynotrevoked'));
	}
	else
		register_error(elgg_echo('apiadmin:keynotrevoked'));
		
	forward($_SERVER['HTTP_REFERER']);
?>