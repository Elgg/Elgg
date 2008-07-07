<?php
	/**
	 * Make another user an admin.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
	global $CONFIG;
	
	// block non-admin users
	admin_gatekeeper();
	
	// Get the user 
	$guid = get_input('guid');
	$obj = get_entity($guid);
	
	if ( ($obj instanceof ElggUser) && ($obj->canEdit()))
	{
		$result = $obj->admin = 'yes';
		if ($result)
			system_message(elgg_echo('admin:user:makeadmin:yes'));
		else
			system_message(elgg_echo('admin:user:makeadmin:no'));
	}
	else
		system_message(elgg_echo('admin:user:makeadmin:no'));
	
	forward($_SERVER['HTTP_REFERER']);

?>