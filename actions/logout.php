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
	    
    // Log out
        $result = logout();
            
    // Set the system_message as appropriate
        
        if ($result) {
            system_message(elgg_echo('logout'));
        } else {
            system_message(elgg_echo('logouterror'));
        }

?>