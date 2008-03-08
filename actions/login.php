<?php

    /**
	 * Elgg login action page
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */
	 
    // Get username and password
        require_once('../engine/start.php');
        global $CONFIG;
    
        $username = get_input('username');
        $password = get_input("password");
        $persistent = get_input("persistent");
        
        if (empty($passthru)) {
            $passthru = $CONFIG->url;
        }
        
    // If all is present and correct, try to log in            
        if (!empty($username) && !empty($password)) {
            $result = login($username, $password, $persistent);
        }
        
    // Set the system_message as appropriate
        
        if ($result) {
            system_message("You have been logged in.");
        } else {
            system_message("We couldn't log you in. Make sure your details are correct and please try again.");
        }
        
        header("Location: {$passthru}");
        exit;       
      
?>