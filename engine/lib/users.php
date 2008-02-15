<?php

	/**
	 * Elgg users
	 * User and session management
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	// This is here as a dummy for now
		function get_users($param, $param2) {
			return false;
		}

	/**
	 * Session management
	 */

	/**
	 * Returns whether or not the user is currently logged in
	 *
	 * @uses $_SESSION
	 * @return true|false
	 */
		function isloggedin() {
			
			if ($_SESSION['id'] > 0)
				return true;
			return false;
			
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
	        
	        if (empty($_SESSION['id'])) {
	            if (isset($_COOKIE['elggperm'])) {
	                                
	                $code = $_COOKIE['elggperm'];
	                $code = md5($code);
	                if ($users = get_users(array(
	                                                "code" =>$code
	                                            ), "id, username, name, password")) {
	                    foreach($users as $user) {
	                        $_SESSION['id'] = $user->id;
	                        $_SESSION['username'] = $user->username;
	                        $_SESSION['name'] = $user->name;
	                        $_SESSION['code'] = $_COOKIE['elggperm'];
	                        // set_login_fields($user->id);
	                    }
	                } else {
	                    $_SESSION['id'] = -1;
	                }
	            } else {
	                $_SESSION['id'] = -1;
	            }
	        } else {
	            if (!empty($_SESSION['code'])) {
	                $code = md5($_SESSION['code']);
	                if ($uid = get_users(array(
	                                                "code" =>$code
	                                            ), "id")) {
	                    $id = $uid->id;
	                } else {
	                    
	                }
	            } else {
	                $_SESSION['id'] = -1;
	            }
	        }
	        if ($_SESSION['id'] > 0) {
	            // set_last_action($_SESSION['id']);
	        }
		}

	register_event_handler("init","system","session_init");
		
?>