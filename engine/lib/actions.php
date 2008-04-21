<?php

    /**
	 * Elgg actions
	 * Allows system modules to specify actions
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */
     
    // Action setting and run *************************************************
    
   	/**
   	 * Loads an action script, if it exists, then forwards elsewhere
   	 *
   	 * @param string $action The requested action
   	 * @param string $forwarder Optionally, the location to forward to
   	 */
    
        function action($action, $forwarder = "") {
            
            global $CONFIG;
            
            $forwarder = str_replace($CONFIG->url, "", $forwarder);
            $forwarder = str_replace("http://", "", $forwarder);
            $forwarder = str_replace("@", "", $forwarder);

            if (substr($forwarder,0,1) == "/") {
                $forwarder = substr($forwarder,1);
            }
            
            if (isset($CONFIG->actions[$action])) {
                if ($CONFIG->actions[$action]['public'] || $_SESSION['id'] != -1) {
	                if (@include($CONFIG->actions[$action]['file'])) {
	                } else {
	                    register_error(sprintf(elgg_echo('actionundefined'),$action));
	                }
                } else {
                    register_error(elgg_echo('actionloggedout'));
                }
            } else {
            	register_error(sprintf(elgg_echo('actionundefined'),$action));
            }
            forward($CONFIG->url . $forwarder);
            
        }
    
	/**
	 * Registers a particular action in memory
	 *
	 * @param string $action The name of the action (eg "register", "account/settings/save")
	 * @param boolean $public Can this action be accessed by people not logged into the system?
	 * @param string $filename Optionally, the filename where this action is located
	 */
        
        function register_action($action, $public = false, $filename = "") {
            global $CONFIG;            

            if (!isset($CONFIG->actions)) {
                $CONFIG->actions = array();
            }
            
            if (empty($filename)) {
            	$path = ""; 
            	if (isset($CONFIG->path)) $path = $CONFIG->path;
            	
                $filename = $path . "actions/" . $action . ".php";
            }

            $CONFIG->actions[$action] = array('file' => $filename, 'public' => $public);
            return true;
        }

	/**
	 * Actions to perform on initialisation
	 *
	 * @param string $event Events API required parameters
	 * @param string $object_type Events API required parameters
	 * @param string $object Events API required parameters
	 */
        
        function actions_init($event, $object_type, $object) {
        	register_action("error");
        	return true;
        }
        
    // Register some actions ***************************************************
    
        register_event_handler("init","system","actions_init");

?>