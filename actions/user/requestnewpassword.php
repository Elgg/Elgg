<?php
	/**
	 * Action to request a new password.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	global $CONFIG;
	
	action_gatekeeper();
	
	$username = get_input('username');
	
	$user = get_user_by_username($username);
	if ($user)
	{
		if (send_new_password_request($user->guid))
			system_message(elgg_echo('user:password:resetreq:success'));
		else
			register_error(elgg_echo('user:password:resetreq:fail')); 
	}
	else
		register_error(sprintf(elgg_echo('user:username:notfound'), $username));
	
	forward($_SERVER['HTTP_REFERER']);
	exit;
?>