<?php

	/**
	 * Elgg session management
	 * Functions to manage logins
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Returns whether or not the user is currently logged in
	 *
	 * @uses $_SESSION
	 * @return true|false
	 */
		function isloggedin() {
			
			if (!is_installed()) return false;
			if ((isset($_SESSION['guid'])) && ($_SESSION['guid'] > 0))
				return true;
			return false;
			
		}

	/**
	 * Returns whether or not the user is currently logged in and that they are an admin user.
	 *
	 * @uses $_SESSION
	 * @uses isloggedin()
	 * @return true|false
	 */
		function isadminloggedin()
		{
			if ((isloggedin()) && (($_SESSION['user']->admin || $_SESSION['user']->siteadmin)))
				return true;
				
			return false;
		}
		
	/**
	 * Perform standard authentication with a given username and password.
	 * Returns an ElggUser object for use with login.
	 *
	 * @see login
	 * @param string $username The username, optionally (for standard logins)
	 * @param string $password The password, optionally (for standard logins)
	 * @return ElggUser|false The authenticated user object, or false on failure.
	 */
		
		function authenticate($username, $password) {
            
			if (pam_authenticate(array('username' => $username, 'password' => $password)))
				return get_user_by_username($username);
            
            return false;
			
		}
		
		/**
		 * Hook into the PAM system which accepts a username and password and attempts to authenticate
		 * it against a known user.
		 *
		 * @param array $credentials Associated array of credentials passed to pam_authenticate. This function expects
		 * 		'username' and 'password' (cleartext).
		 */
		function pam_auth_userpass($credentials = NULL)
		{
			if (is_array($credentials) && ($credentials['username']) && ($credentials['password']))
			{
				//$dbpassword = md5($credentials['password']);
            
	            if ($user = get_user_by_username($credentials['username'])) {
	                 if ($user->password == generate_user_password($user, $credentials['password'])) {
	                 	return true;
	                 }
	            }
			}
			
			return false;
		}
		
	/**
	 * Logs in a specified ElggUser. For standard registration, use in conjunction
	 * with authenticate.
	 * 
	 * @see authenticate
	 * @param ElggUser $user A valid Elgg user object
	 * @param boolean $persistent Should this be a persistent login?
	 * @return true|false Whether login was successful
	 */
		function login(ElggUser $user, $persistent = false) {
            
            global $CONFIG;
                 
            $_SESSION['user'] = $user;
            $_SESSION['guid'] = $user->getGUID();
            $_SESSION['id'] = $_SESSION['guid'];
            $_SESSION['username'] = $user->username;
            $_SESSION['name'] = $user->name;
                     
            $code = (md5($user->name . $user->username . time() . rand()));

            $user->code = md5($code);
            
            $_SESSION['code'] = $code;
            
            if (($persistent))
				setcookie("elggperm", $code, (time()+(86400 * 30)),"/");
            
            if (!$user->save() || !trigger_elgg_event('login','user',$user)) {
            	unset($_SESSION['username']);
	            unset($_SESSION['name']);
	            unset($_SESSION['code']);
	            unset($_SESSION['guid']);
	            unset($_SESSION['id']);
	            unset($_SESSION['user']);
	            setcookie("elggperm", "", (time()-(86400 * 30)),"/");
            	return false;
            }

			return true;
				
		}
        
	/**
	 * Log the current user out
	 *
	 * @return true|false
	 */
		function logout() {
            global $CONFIG;

            if (isset($_SESSION['user'])) {
            	if (!trigger_elgg_event('logout','user',$_SESSION['user'])) return false;
            	$_SESSION['user']->code = "";
            	$_SESSION['user']->save();
            }
            unset($_SESSION['username']);
            unset($_SESSION['name']);
            unset($_SESSION['code']);
            unset($_SESSION['guid']);
            unset($_SESSION['id']);
            unset($_SESSION['user']);
            
            setcookie("elggperm", "", (time()-(86400 * 30)),"/");
            
            return true;
        }
		
	/**
	 * Initialises the system session and potentially logs the user in
	 * 
	 * This function looks for:
	 * 
	 * 1. $_SESSION['id'] - if not present, we're logged out, and this is set to 0
	 * 2. The cookie 'elggperm' - if present, checks it for an authentication token, validates it, and potentially logs the user in 
	 *
	 * @uses $_SESSION
	 * @param unknown_type $event
	 * @param unknown_type $object_type
	 * @param unknown_type $object
	 */
		function session_init($event, $object_type, $object) {
			
			if (!is_db_installed()) return false;
			
			session_name('Elgg');
	        session_start();
	        
	        if (empty($_SESSION['guid'])) {
	            if (isset($_COOKIE['elggperm'])) {            
	                $code = $_COOKIE['elggperm'];
	                $code = md5($code);
	                $_SESSION['guid'] = 0;
	                $_SESSION['id'] = 0;
	                if ($user = get_user_by_code($code)) {
                    	$_SESSION['user'] = $user;
                        $_SESSION['id'] = $user->getGUID();
                        $_SESSION['guid'] = $_SESSION['id'];
                        $_SESSION['code'] = $_COOKIE['elggperm'];
	                }
	            } else {
	                $_SESSION['id'] = 0;
	                $_SESSION['guid'] = 0;
	            }
	        } else {
	            if (!empty($_SESSION['code'])) {
	                $code = md5($_SESSION['code']);
	                if ($user = get_user_by_code($code)) {
	                	$_SESSION['user'] = $user;
	                } else {
	                	unset($_SESSION['user']);
	                	$_SESSION['guid'] = 0;
	                	$_SESSION['id'] = 0;
	                }
	            } else {
	            	$_SESSION['guid'] = 0;
	                $_SESSION['id'] = 0;
	            }
	        }
	        if ($_SESSION['id'] > 0) {
	            // set_last_action($_SESSION['id']);
	        }
	        
	        register_action("login",true);
    		register_action("logout");
    		
    		// Register a default PAM handler
    		register_pam_handler('pam_auth_userpass');
    		
    		return true;
	        
		}

	/**
	 * Used at the top of a page to mark it as logged in users only.
	 *
	 */
		function gatekeeper() {
			if (!isloggedin()) forward();
		}
		
		/**
		 * Used at the top of a page to mark it as logged in admin or siteadmin only.
		 *
		 */
		function admin_gatekeeper()
		{
			gatekeeper();
			if (!$_SESSION['user']->admin && !$_SESSION['user']->siteadmin) forward();
		}
		
		register_elgg_event_handler("boot","system","session_init",1);


?>