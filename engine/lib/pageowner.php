<?php

    /**
	 * Elgg page owner library
	 * Contains functions for managing page ownership
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

    /**
     * Gets the page owner for the current page.
     * @uses $CONFIG
     * @return int|false The current page owner guid (0 if none).
     */

        function page_owner() {

            global $CONFIG;
            
            $setpageowner = set_page_owner();
            if ($setpageowner !== false) {
            	return $setpageowner;
            }
            
	        if ($username = get_input("username")) {
	        	if (substr_count($username,'group:')) {
	            	preg_match('/group\:([0-9]+)/i',$username,$matches);
	            	$guid = $matches[1];
	            	if ($entity = get_entity($guid)) {
	            		return $entity->getGUID();
	            	}
	            }
	            if ($user = get_user_by_username($username)) {
	            	return $user->getGUID();
	            }
	        }
	        if ($owner = get_input("owner_guid")) {
	            if ($user = get_entity($owner)) {
	            	return $user->getGUID();
	            }
	        }
            if (!empty($CONFIG->page_owner_handlers) && is_array($CONFIG->page_owner_handlers)) {
                foreach($CONFIG->page_owner_handlers as $handler) {
                    if ($guid = $handler()) {
                        return $guid;
                    }
                }
            }
            return 0;
            
        }
        
	/**
     * Gets the page owner for the current page.
     * @uses $CONFIG
     * @return ElggUser|false The current page owner (false if none).
     */
		function page_owner_entity() {

            global $CONFIG;
	        $page_owner = page_owner();
	        if ($page_owner > 0)
	        	return get_entity($page_owner);
	        	
			return false;
            //return new ElggDummy();
        }
        
    /**
     * Adds a page owner handler - a function that will
     * return the page owner if required
     * (Such functions are required to return false if they don't know)
     * @uses $CONFIG
     * @param string $functionname The name of the function to call
     * @return mixed The guid of the owner or false
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
        
	/**
	 * Allows a page to manually set a page owner
	 *
	 * @param int $entitytoset The GUID of the page owner
	 * @return int|false Either the page owner we've just set, or false if unset
	 */
        function set_page_owner($entitytoset = -1) {
        	
        	static $entity;
        	
        	if (!isset($entity)) $entity = false;
        	
        	if ($entitytoset > -1) {
        		$entity = $entitytoset;
        	}
        	
        	return $entity;
        	
        }
        
	/**
	 * Sets the functional context of a page
	 *
	 * @param string $context The context of the page
	 * @return string|false Either the context string, or false on failure
	 */
        function set_context($context) {
        	
        	global $CONFIG;
        	if (!empty($context)) {
        		$context = trim($context);
        		$context = strtolower($context);
        		$CONFIG->context = $context;
        		return $context;
        	} else {
        		return false;
        	}
        		
        }
        
	/**
	 * Returns the functional context of a page
	 *
	 * @return string The context, or 'main' if no context has been provided
	 */
        function get_context() {
        	
        	global $CONFIG;
        	if (isset($CONFIG->context) && !empty($CONFIG->context)) {
        		return $CONFIG->context;
        	}
        	if ($context = get_plugin_name(true)) {
        		return $context;
        	}
        	return "main";
        	
        }
        
?>