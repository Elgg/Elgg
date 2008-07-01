<?php

    /**
	 * Elgg login action
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */
	 
    // Get username and password
    
        $username = get_input('username');
        $password = get_input("password");
        $persistent = get_input("persistent");
        
    // If all is present and correct, try to log in  
    	$result = false;          
        if (!empty($username) && !empty($password)) {
        	if ($user = authenticate($username,$password)) {
        		$result = login($user, $persistent);
        	}
        }
        
    // Set the system_message as appropriate
        
        if ($result) {
            system_message(elgg_echo('loginok'));
            forward("pg/dashboard/");
        } else {
            system_message(elgg_echo('loginerror'));
        }
      
?>