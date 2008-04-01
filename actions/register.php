<?php

	/**
	 * Elgg registration action
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	// Get variables
		$username = get_input('username');
		$password = get_input('password');
		$password2 = get_input('password2');
		$email = get_input('email');
		$name = get_input('name');
		
	// For now, just try and register the user
		if (register_user($username, $password, $name, $email)) {
			system_message(sprintf(elgg_echo("registerok"),$CONFIG->sitename));
		} else {
			system_message(elgg_echo("registerbad"));
		}

?>