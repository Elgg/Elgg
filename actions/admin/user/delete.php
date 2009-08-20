<?php
	/**
	 * Elgg delete user
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Curverider Ltd
	 * @link http://elgg.org/
	 */

	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");

	// block non-admin users
	admin_gatekeeper();
	action_gatekeeper();
	
	// Get the user 
	$guid = get_input('guid');
	$obj = get_entity($guid);
	
	if ( ($obj instanceof ElggUser) && ($obj->canEdit()))
	{
		if ($obj->delete())
			system_message(elgg_echo('admin:user:delete:yes'));
		else
			register_error(elgg_echo('admin:user:delete:no'));
	}
	else
		register_error(elgg_echo('admin:user:delete:no'));
		
	forward($_SERVER['HTTP_REFERER']);
	exit;
?>