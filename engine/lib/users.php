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

	/**
	 * @class ElggUser
	 * This class represents an Elgg user.
	 */
		class ElggUser {
			
		/**
		 * This contains the user's main properties (id, etc)
		 * @var array
		 */
			private $attributes;
		
		/**
		 * Construct a new user object, optionally from a given id value.
		 *
		 * @param mixed $id
		 */
			function __construct($id = null) 
			{
				$this->attributes = array();
				
				if (!empty($id)) {
					
					$user = null;
					
					if (is_int($id))
						$user = get_user($id); // This is an integer ID
					else if ($id instanceof stdClass)
						$user = $id;	// This is a db row, so serialise directly
					else
						throw new InvalidParameterException("Unrecognised or unsupported type passed to ElggUser constructor.");
					
					if ($user) {
						$objarray = (array) $user;
						foreach($objarray as $key => $value) {
							$this->attributes[$key] = $value;
							error_log("$key => $value");
						}
					}
					else
						throw new IOException("Could not create ElggUser object");
				}
			}
			
			function __get($name) {
				if (isset($this->attributes[$name])) {
					return $this->attributes[$name];
				}
				return null;
			}
			
			function __set($name, $value) {
				$this->attributes[$name] = $value;
				return true;
			}
			
		/**
		 * Saves or updates this user
		 *
		 * @return true|false
		 */
			function save() {
				if (!empty($this->id)) {
					update_user($this->id, $this->name, $this->username, $this->password, $this->email, $this->language);									
				} else if ($id = create_user($this->name, $this->username, $this->password, $this->email, $this->language)) {
					$this->id = $id;
					return true;
				}
			}
			
		/**
		 * Deletes this user
		 *
		 * @return true|false
		 */
			function delete() {
				if (!empty($this->id)) {
					return delete_user($this->id);
				}
				return false;
			}
			
		/**
		 * Get this user's owned objects
		 *
		 * @param string $type The type of object
		 * @param int $limit The number of objects
		 * @param int $offset Indexing offset
		 * @param int $site_id The site ID
		 * @return array List of objects
		 */
			function getObjects($type = "", $limit = 20, $offset = 0, $site_id = 0) { 
				return get_objects($this->id, $type, "", "", $limit, $offset, $site_id);
			}

		/**
		 * Get this user's owned objects by metadata value
		 *
		 * @param string $type The type of object
		 * @param string $metadata_type The name of the metadata type
		 * @param string $metadata_value The value of the metadata
		 * @param int $limit The number of objects to return
		 * @param int $offset Indexing offset
		 * @param int $site_id The site
		 * @return array List of objects
		 */
			function getObjectsByMetadata($type = "", $metadata_type, $metadata_value, $limit = 20, $offset = 0, $site_id = 0) {
				return get_objects($this->id, $type, $metadata_type, $metadata_value, $limit, $offset, $site_id);
			}
			
		/**
		 * Gets this user's sites
		 *
		 * @return array List of ElggSites
		 */
			function getSites() { return get_user_sites($this->id); }
			
		/**
		 * Adds this user to the currently logged in user's friends list
		 *
		 * @return true|false
		 */
			function addAsFriend() {
				global $CONFIG;
				if (isloggedin()) {
					return make_friend($_SESSION['id'],$this-id,$CONFIG->site_id);
				}
				return false;
			}
			
		/**
		 * Add a user to this user's friends list
		 *
		 * @param int $friend_id The user ID to add
		 * @param int $site_id The site ID to add
		 * @return true|false On success
		 */
			function addFriend($friend_id, $site_id = 0) {
				global $CONFIG;
				if ($site_id == 0) $site_id = $CONFIG->site_id; 
				return make_friend($this->id, $friend_id, $site_id); 
			}
			
		/**
		 * Removes this user from the currently logged in user's friends list
		 *
		 * @return true|false
		 */
			function removeAsFriend() {
				global $CONFIG;
				return remove_friend($_SESSION['id'], $this->id, $CONFIG->site_id);
			}
			
		/**
		 * Remove a user from this user's friends list
		 *
		 * @param int $friend_id The user ID to remove
		 * @param int $site_id The site ID to remove
		 * @return true|false On success
		 */
			function removeFriend($friend_id, $site_id = 0) { 
				global $CONFIG;
				if ($site_id == 0) $site_id = $CONFIG->site_id;
				return remove_friend($this->id, $friend_id, $site_id);
			}
			
		/**
		 * Get a list of this user's friends
		 *
		 * @param int $site_id The site ID
		 * @return array List of ElggUsers
		 */
			function getFriends($site_id = 0) { 
				global $CONFIG;
				if ($site_id == 0) $site_id = $CONFIG->site_id;
				return get_friends($this->id, $site_id);
			}
			
		/**
		 * Get a list of users who have marked this user as a friend
		 *
		 * @param int $site_id The site ID
		 * @return array List of ElggUsers
		 */
			function getFriendsOf($site_id = 0) { 
				global $CONFIG;
				if ($site_id == 0) $site_id = $CONFIG->site_id;
				return get_friends_reverse($this->id, $site_id);
			}
			
		/**
		 * Determines whether or not this user is a friend of the currently logged in user
		 *
		 * @return true|false
		 */
			function isFriend() {
				global $CONFIG;
				return is_friend($_SESSION['id'],$this->id, $CONFIG->site_id);
			}
			
		/**
		 * Determines whether this user is friends with a particular user 
		 *
		 * @param int $user_id The ID of the user to check friendship with
		 * @param int $site_id The site ID
		 * @return true|false
		 */
			function isFriendsWith($user_id, $site_id = 0) { 
				global $CONFIG;
				if ($site_id == 0) $site_id = $CONFIG->site_id;
				return is_friend($this->id, $user_id, $site_id);
			}
			
		/**
		 * Determines whether this user has been made a friend of a particular user 
		 *
		 * @param int $user_id The ID of the user to check friendship of
		 * @param int $site_id The site ID
		 * @return true|false
		 */
			function isFriendOf($user_id, $site_id = 0) { 
				global $CONFIG;
				if ($site_id == 0) $site_id = $CONFIG->site_id;
				return is_friend($user_id, $this->id, $site_id);
			}
			
			
			
		}

	/**
	 * Gets a user with a particular ID, if they exist
	 *
	 * @param int $id The user ID
	 * @return ElggUser or false
	 */
		function get_user($id) {
			
			global $CONFIG;
			$id = (int) $id;
			return row_to_elgguser(get_data_row("select * from {$CONFIG->dbprefix}users where id = {$id}"));
			
		}
		
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $criteria
	 * @param unknown_type $fields
	 * @return unknown
	 */
		function get_users($criteria, $fields = "*") {
            
            global $CONFIG;
            $where = "";
            
            if (!empty($criteria) && is_array($criteria)) {
            
                foreach($criteria as $name => $value) {
                    if (!empty($where)) {
                        $where .= " and ";
                    }
                    $where .= " {$name} = '" . sanitise_string($value) . "'";
                }
            
            } else if (!empty($criteria) && !is_array($criteria)) {
                
                $where = $criteria;
                
            }
            
            if (!empty($where)) {
                $where = "where (" . $where . ")";
            }

            return get_data("select {$fields}, 'users' as type from {$CONFIG->dbprefix}users {$where}");
            
        }
		
	/**
	 * Update a user row
	 *
	 * @param int $id The user ID
	 * @param string $name The user's name
	 * @param string $username The user's username
	 * @param string $password The user's password
	 * @param string $email The user's email address
	 * @param string $language The user's language
	 * @return true|false
	 */
		function update_user($id, $name, $username, $password, $email, $language) {
			
			global $CONFIG;
			$id = (int) $id;
			$name = sanitise_string($name);
			$username = sanitise_string($username);
			$password = sanitise_string($password);
			$email = sanitise_string($email);
			$language = sanitise_string($language);
			
			foreach(array('name','username','password','email','language') as $param) {
				if ($$param != null) {
					$params[] = "{$param} = '{$$param}'";
				}
			}
			
			return update_data("update {$CONFIG->dbprefix}users set " . implode(",",$params) . " where id = {$id}");
			
		}
		
	/**
	 * Create a new user
	 *
	 * @param string $name The user's name
	 * @param string $username The user's username
	 * @param string $password The user's password
	 * @param string $email The user's email address
	 * @param string $language The user's language
	 * @return int The user's ID
	 */
		function create_user($name, $username, $password, $email, $language) {
			
			global $CONFIG;
			$name = sanitise_string($name);
			$username = sanitise_string($username);
			$password = sanitise_string($password);
			$email = sanitise_string($email);
			$language = sanitise_string($language);
			$created = time(); 
			foreach(array('name','username','password','email','language','created') as $param) {
				if ($$param != null) {
					$params[] = "{$param} = '{$$param}'";
				}
			}
			return insert_data("insert into {$CONFIG->dbprefix}users set " . implode(",",$params));
			
		}
		
	/**
	 * Deletes a given user, completely
	 *
	 * @param int $id The user ID
	 * @return true|false
	 */
		function delete_user($id) {
			
			global $CONFIG;
			$id = (int) $id;
			if ($user = get_user($id)) {
				
				if (!(trigger_event("delete","user",$user)))
					return false;
				
				// Get user sites
				$sites = $user->getSites();
				
				if (!empty($sites))
					foreach($sites as $site) {
						// Remove friends
						if ($friends = $user->getFriends($site->id)) {
							foreach($friends as $friend) {
								$user->removeFriend($friend->id,$site->id);															
							}
						}
						// Remove friends of
						if ($friendsof = $user->getFriendsOf($site->id)) {
							foreach($friendsof as $friend) {
								$friend->removeFriend($user->id,$site->id);
							}
						}
						// Remove objects
						if ($objects = $user->getObjects("",999999999, 0,$site->id)) {
							foreach($objects as $object) {
								$object->delete();
							}
						}
					}
			}
			
			return true;
			
		}
		
	/**
	 * Convert a database row to a new ElggUser
	 *
	 * @param stdClass $row
	 * @return stdClass or ElggUser
	 */
		function row_to_elgguser($row) {
			if (!($row instanceof stdClass))
				return $row;
			return new ElggSite($row);
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