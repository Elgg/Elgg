<?php
	/**
	 * Elgg ban user
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
		// Now actually disable it
		if ($obj->ban('banned')) {
			system_message(elgg_echo('admin:user:ban:yes'));
		}
		else
			register_error(elgg_echo('admin:user:ban:no'));
	} else {
		$canedit = $obj->canEdit();
		$isinstance = ($obj instanceof ElggUser);
		register_error(elgg_echo('admin:user:ban:no'));
	}
		
	forward('pg/admin/user/');
	exit;
?>