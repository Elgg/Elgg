<?php
	/**
	 * Action to reset a password and send success email.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	global $CONFIG;
	
	$user_guid = get_input('u');
	$code = get_input('c');
	
	if (execute_new_password_request($user_guid, $code))
		system_message(elgg_echo('user:password:success'));
	else
		register_error(elgg_echo('user:password:fail'));
		
	forward();
	exit;
	
?>