<?php

	/**
	 * Elgg registration action
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

	 * @link http://elgg.org/
	 */

	require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
	global $CONFIG;
	
	action_gatekeeper();

	// Get variables
		$username = get_input('username');
		$password = get_input('password');
		$password2 = get_input('password2');
		$email = get_input('email');
		$name = get_input('name');
		$friend_guid = (int) get_input('friend_guid',0);
		$invitecode = get_input('invitecode');
		
		$admin = get_input('admin');
		if (is_array($admin)) $admin = $admin[0];
		
		
		if (!$CONFIG->disable_registration)
		{
	// For now, just try and register the user
	
			try {
				if (
					(
						(trim($password)!="") &&
						(strcmp($password, $password2)==0) 
					) &&
					($guid = register_user($username, $password, $name, $email, false, $friend_guid, $invitecode))
				) {
					
					$new_user = get_entity($guid);
					if (($guid) && ($admin))
					{
						admin_gatekeeper(); // Only admins can make someone an admin
						$new_user->admin = 'yes';
					}
					
					// Send user validation request on register only
					global $registering_admin;
					if (!$registering_admin)
						request_user_validation($guid);
					
					if (!$new_user->admin)
						$new_user->disable('new_user', false);	// Now disable if not an admin
						// Don't do a recursive disable.  Any entities owned by the user at this point
						// are products of plugins that
					
					system_message(sprintf(elgg_echo("registerok"),$CONFIG->sitename));
					
					forward(); // Forward on success, assume everything else is an error...
				} else {
					register_error(elgg_echo("registerbad"));
				}
			} catch (RegistrationException $r) {
				register_error($r->getMessage());
			}
		}
		else
			register_error(elgg_echo('registerdisabled'));
			
		$qs = explode('?',$_SERVER['HTTP_REFERER']);
		$qs = $qs[0];
		$qs .= "?u=" . urlencode($username) . "&e=" . urlencode($email) . "&n=" . urlencode($name) . "&friend_guid=" . $friend_guid;
		
		forward($qs);

?>