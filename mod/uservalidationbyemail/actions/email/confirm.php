<?php
	/**
	 * Action which confirms an email when it is registered or changed, based on a code.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author 
	 * @link http://elgg.org/
	 */

	global $CONFIG;

	// Get user id
	$access_status = access_get_show_hidden_status();
	access_show_hidden_entities(true);
	
	$user_guid = (int)get_input('u');
	$user = get_entity($user_guid);
	
	// And the code
	$code = sanitise_string(get_input('c'));
	
	if ( ($code) && ($user) )
	{
		if (uservalidationbyemail_validate_email($user_guid, $code)) {
			system_message(elgg_echo('email:confirm:success'));
		
			$user = get_entity($user_guid);
			$user->enable();
			
			notify_user($user_guid, $CONFIG->site->guid, sprintf(elgg_echo('email:validate:success:subject'), $user->username), sprintf(elgg_echo('email:validate:success:body'), $user->name), NULL, 'email');
			
		} else
			register_error(elgg_echo('email:confirm:fail'));
	}
	else
		register_error(elgg_echo('email:confirm:fail'));
		
	access_show_hidden_entities($access_status);
	
	forward();
	exit;

?>