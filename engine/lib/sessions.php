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
			
			if ((isset($_SESSION['guid'])) && ($_SESSION['guid'] > 0))
				return true;
			return false;
			
		}
		
	/**
	 * Log in
	 *
	 * @param string $username
	 * @param string $password
	 * @param true|false $persistent
	 * @return true|false
	 */
		function login($username, $password, $persistent = false) {
            
            global $CONFIG;
            $dbpassword = md5($password);
                        
            if ($user = get_user_by_username($username)) {
                 if ($user->password == $dbpassword) {
                     
                 	 $_SESSION['user'] = $user;
                     $_SESSION['guid'] = $user->getGUID();
                     $_SESSION['id'] = $_SESSION['guid'];
                     $_SESSION['username'] = $user->username;
                     $_SESSION['name'] = $user->name;
                     
                     $code = (md5($user->name . $user->username . time() . rand()));
                     // update_data("update {$CONFIG->dbprefix}users set code = '".md5($code)."' where id = {$user->id}");
                     $user->code = md5($code);
                     $user->save();
                     
                     //$code = md5($code);    // This is a deliberate re-MD5-ing
                     
                     $_SESSION['code'] = $code;
                     //if (!empty($persistent)) {
                         
                         setcookie("elggperm", $code, (time()+(86400 * 30)),"/");
                         
                     //}
                     // set_login_fields($user->id);

                     
                 }
                 
                 return true;
             } else {
                 return false;
             }
            
        }
        
	/**
	 * Log the current user out
	 *
	 * @return true|false
	 */
		function logout() {
            global $CONFIG;
            
            if (isset($_SESSION['user'])) {
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
	 * 1. $_SESSION['id'] - if not present, we're logged out, and this is set to -1
	 * 2. The cookie 'elggperm' - if present, checks it for an authentication token, validates it, and potentially logs the user in 
	 *
	 * @uses $_SESSION
	 * @param unknown_type $event
	 * @param unknown_type $object_type
	 * @param unknown_type $object
	 */
		function session_init($event, $object_type, $object) {
			session_name('Elgg');
	        session_start();
	        
	        if (empty($_SESSION['guid'])) {
	            if (isset($_COOKIE['elggperm'])) {
	                                
	                $code = $_COOKIE['elggperm'];
	                $code = md5($code);
	                if ($user = get_user_by_code($code)) {
                    	$_SESSION['user'] = $user;
                        $_SESSION['id'] = $user->getGUID();
                        $_SESSION['guid'] = $_SESSION['id'];
                        $_SESSION['code'] = $_COOKIE['elggperm'];
	                } else {
	                    $_SESSION['id'] = -1;
	                    $_SESSION['guid'] = -1;
	                }
	            } else {
	                $_SESSION['id'] = -1;
	                $_SESSION['guid'] = -1;
	            }
	        } else {
	            if (!empty($_SESSION['code'])) {
	                $code = md5($_SESSION['code']);
	                if ($user = get_user_by_code($code)) {
	                	$_SESSION['user'] = $user;
	                } else {
	                }
	            } else {
	            	$_SESSION['guid'] = -1;
	                $_SESSION['id'] = -1;
	            }
	        }
	        if ($_SESSION['id'] > 0) {
	            // set_last_action($_SESSION['id']);
	        }
		}

		register_event_handler("init","system","session_init");
	
	//register actions *************************************************************
   
   		register_action("login",true);
    	register_action("logout");


?>