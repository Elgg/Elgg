<?php

    /**
	 * Elgg logout action page
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */
     
	//delete this 
	    require_once('../engine/start.php');
	    global $CONFIG;
	    
    // Log out
        $result = logout();
            
    // Set the system_message as appropriate
        
        if ($result) {
            system_message("You have been logged out.");
        } else {
            system_message("We couldn't log you out. We're not sure why, to be honest. Try again?");
        }
        
    //direct to frontpage
        $url = $CONFIG->url;
        header("Location: {$url}");
        exit;  

?>