<?php

    /**
	 * Elgg page owner library
	 * Contains functions for managing page ownership
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

    /**
     * Gets the page owner for the current page.
     * @uses $CONFIG
     * @return int|false The current page owner guid (false if none).
     */

        function page_owner() {

            global $CONFIG;
	        if ($username = get_input("username")) {
	            $user = get_user_by_username($username);
	            return $user->getGUID();
	        }
	        if ($owner = get_input("owner_id")) {
	            $user = get_user($owner);
	            return $user->getGUID();
	        }
            if (!empty($CONFIG->page_owner_handlers) && is_array($CONFIG->page_owner_handlers)) {
                foreach($CONFIG->page_owner_handlers as $handler) {
                    if ($guid = $handler()) {
                        return $guid;
                    }
                }
            }
            return false;
            
        }
        
    /**
     * Adds a page owner handler - a function that will
     * return the page owner if required
     * (Such functions are required to return false if they don't know)
     * @uses $CONFIG
     * @param string $functionname The name of the function to call
     */

        function add_page_owner_handler($functionname) {
            
            global $CONFIG;
            if (empty($CONFIG->page_owner_handlers)) {
                $CONFIG->page_owner_handlers = array();
            }
            if (is_callable($functionname)) {
                $CONFIG->page_owner_handlers[] = $functionname;
            }
            
        }
        
?>