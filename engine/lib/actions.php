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
            
	        $query = parse_url($_SERVER['REQUEST_URI']);
			if (isset($query['query'])) {
				$query = $query['query'];
				$query = rawurldecode($query);
				$query = explode('&',$query);
				if (sizeof($query) > 0) {
					foreach($query as $queryelement) {
						$vals = explode('=',$queryelement);
						if (sizeof($vals) > 1) {
							set_input(trim($vals[0]),trim($vals[1]));
						}
					}
				}
			}
            
            $forwarder = str_replace($CONFIG->url, "", $forwarder);
            $forwarder = str_replace("http://", "", $forwarder);
            $forwarder = str_replace("@", "", $forwarder);

            if (substr($forwarder,0,1) == "/") {
                $forwarder = substr($forwarder,1);
            }
            
            if (isset($CONFIG->actions[$action])) {
            	if (
            		(isadminloggedin()) ||
            		(!$CONFIG->actions[$action]['admin'])
            	) {
	                if ($CONFIG->actions[$action]['public'] || $_SESSION['id'] != -1) {
		                if (@include($CONFIG->actions[$action]['file'])) {
		                } else {
		                    register_error(sprintf(elgg_echo('actionundefined'),$action));
		                }
	                } else {
	                    register_error(elgg_echo('actionloggedout'));
	                }
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
	 * @param boolean $admin_only Whether this action is only available to admin users.
	 */
        
        function register_action($action, $public = false, $filename = "", $admin_only = false) {
            global $CONFIG;            
            
            if (!isset($CONFIG->actions)) {
                $CONFIG->actions = array();
            }
            
            if (empty($filename)) {
            	$path = ""; 
            	if (isset($CONFIG->path)) $path = $CONFIG->path;
            	
                $filename = $path . "actions/" . $action . ".php";
            }
            
            $CONFIG->actions[$action] = array('file' => $filename, 'public' => $public, 'admin' => $admin_only);
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

       	/**
       	 * Action gatekeeper.
       	 * This function verifies form input for security features (like a generated token), and forwards
       	 * the page if they are invalid.
       	 * 
       	 * Place at the head of actions.
       	 */
        function action_gatekeeper()
        {
        	$token = get_input('__elgg_token');
        	$action = get_input('__elgg_action');
        	$ts = get_input('__elgg_ts');
        	$session_id = session_id();
        	
        	if (($token) && ($action) && ($ts) && ($session_id))
        	{
	        	// generate token, check with input and forward if invalid
	        	$generated_token = generate_action_token($action, $ts);
	        	
	        	// Validate token
	        	if (strcmp($token, $generated_token)==0)
	        	{
        	
	        		// TODO: Validate time to ensure its not crazy
	        		
	        		
	        		return true;
	        	}
	        	else
	        		register_error(elgg_echo('actiongatekeeper:tokeninvalid'));
        	}
        	else
        		register_error(elgg_echo('actiongatekeeper:missingfields'));
        		
        	forward();
        	exit;
        }
        
        /**
         * Generate a token for the current user suitable for being placed in a hidden field in action forms.
         * 
         * @param string $action The action being called
         * @param int $timestamp Unix timestamp
         */
        function generate_action_token($action, $timestamp)
        {
        	// Get input values
        	$site_secret = get_site_secret();
        	
        	// Current session id
        	$session_id = session_id();
        	
        	if (($site_secret) && ($session_id))
        		return md5($site_secret.$action.$timestamp.$session_id);
        	
        	return false;
        }
       
        /**
         * Initialise the site secret.
         *
         */
        function init_site_secret()
        {
        	$secret = md5(rand().microtime());
        	if (datalist_set('__site_secret__', $secret))
        		return $secret;
        		
        	return false;
        }
        
        /**
         * Retrieve the site secret.
         *
         */
        function get_site_secret()
        {
        	$secret = datalist_get('__site_secret__');
        	if (!$secret) $secret = init_site_secret();
        	
        	return $secret;
        }
        
    // Register some actions ***************************************************
    
        register_elgg_event_handler("init","system","actions_init");

?>