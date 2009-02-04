<?php
	global $CONFIG;
	
	admin_gatekeeper();
	action_gatekeeper();
	
	$ref = get_input('ref');
	
	if ($ref)
	{
		$keypair = create_api_user($CONFIG->site_id);
		
		if ($keypair)
		{
			
			$newkey = new ElggObject();
			$newkey->subtype = 'api_key';
			$newkey->access_id = ACCESS_PUBLIC;
			$newkey->title = $ref;
			$newkey->public = $keypair->api_key;
			
			if (!$newkey->save())
				register_error(elgg_echo('apiadmin:generationfail'));
			else
				system_message(elgg_echo('apiadmin:generated'));
		}
		else
			register_error(elgg_echo('apiadmin:generationfail'));
	}
	else
		register_error(elgg_echo('apiadmin:noreference'));


	forward($_SERVER['HTTP_REFERER']);
?>