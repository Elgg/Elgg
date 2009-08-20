<?php
	/**
	 * Make another user an admin.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Curverider Ltd
	 * @link http://elgg.org/
	 */

	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
	global $CONFIG;
	
	// block non-admin users
	admin_gatekeeper();
	action_gatekeeper();
	
	// Get the user 
	$guid = get_input('guid');
	$obj = get_entity($guid);
	
	if ( ($obj instanceof ElggUser) && ($obj->canEdit()))
	{
		$obj->admin = 'yes';
		if ($obj->admin)
			system_message(elgg_echo('admin:user:makeadmin:yes'));
		else
			register_error(elgg_echo('admin:user:makeadmin:no'));
	}
	else
		register_error(elgg_echo('admin:user:makeadmin:no'));
	
	forward($_SERVER['HTTP_REFERER']);

?>