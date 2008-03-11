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
			
		/**
		 * Set the meta data.
		 *
		 * @param string $name
		 * @param string $value
		 * @param int $access_id
		 * @param string $vartype
		 */
			function setMetadata($name, $value, $access_id = 0, $vartype = "") { return set_user_metadata($name, $value, $access_id, $this->id, $vartype); }
		
		/**
		 * Get the metadata for a user.
		 *
		 * @param string $name
		 */
			function getMetadata($name) { return get_user_metadata($name, $this->id); }
		
		/**
		 * Clear the metadata for a given user.
		 *
		 * @param string $name
		 */
			function clearMetadata($name = "") { return remove_user_metadata($this->id, $name); }
			
		/**
		 * Adds an annotation to a user. By default, the type is detected automatically; however, 
		 * it can also be set. Note that by default, annotations are private.
		 * 
		 * @param string $name
		 * @param string $value
		 * @param int $access_id
		 * @param int $owner_id
		 * @param string $vartype
		 */
			function annotate($name, $value, $access_id = 0, $owner_id = 0, $vartype = "") { return add_site_annotation($name, $value, $access_id, $owner_id, $this->id, $vartype); }
		
		/**
		 * Get the annotations for a user.
		 *
		 * @param string $name
		 * @param int $limit
		 * @param int $offset
		 */
			function getAnnotations($name, $limit = 50, $offset = 0) { return get_site_annotations($name, $this->id, $limit, $offset); }
		
		/**
		 * Return the annotations for the user.
		 *
		 * @param string $name The type of annotation.
		 */
			function countAnnotations($name) { return count_user_annotations($name, $this->id); }

		/**
		 * Get the average of an integer type annotation.
		 *
		 * @param string $name
		 */
			function getAnnotationsAvg($name) { return get_user_annotations_avg($name, $this->id); }
		
		/**
		 * Get the sum of integer type annotations of a given type.
		 *
		 * @param string $name
		 */
			function getAnnotationsSum($name) { return get_user_annotations_sum($name, $this->id); }
		
		/**
		 * Get the minimum of integer type annotations of given type.
		 *
		 * @param string $name
		 */
			function getAnnotationsMin($name) { return get_user_annotations_min($name, $this->id); }
		
		/**
		 * Get the maximum of integer type annotations of a given type.
		 *
		 * @param string $name
		 */
			function getAnnotationsMax($name) { return get_user_annotations_max($name, $this->id); }
		
		/**
		 * Remove all annotations or all annotations of a given user.
		 *
		 * @param string $name
		 */
			function removeAnnotations($name = "") { return remove_user_annotations($this->id, $name); }
		
			
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
	 * Gets a user with a particular username, if they exist
	 *
	 * @param string $username The user's username
	 * @return ElggUser or false
	 */
		function get_user_from_username($username) {
			global $CONFIG;
			$username = (int) sanitise_string($username);
			return row_to_elgguser(get_data_row("select * from {$CONFIG->dbprefix}users where username = '{$username}'"));
			
		}
		
	/**
     * Get a particular piece of user info
     * @param string $fieldname The name of the field we want to get
     * @param int $user_id The id of the user we're checking for
     * @return The value we seek, or false if the user doesn't exist
     */
	    function user_info($fieldname, $user_id) {
	        
	        // Name table
	        static $id_to_name_table;
	
	        // Returns field from a given ID
	
	        $user_id = (int) $user_id;
	        
	        if (!empty($user_id)) {
	            if (!isset($id_to_name_table[$user_id][$fieldname])) {
	                $id_to_name_table[$user_id] = (array) get_data_row("select * from users where id = {$user_id}"); // get_record('users','ident',$user_id);
	            }
	            if (isset($id_to_name_table[$user_id][$fieldname])) {
	                return $id_to_name_table[$user_id][$fieldname];
	            }
	        }
	        
	        // If we've got here, the user didn't exist in the database
	        return false;
	        
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

            return get_data("select {$fields}, 'users' as type from {$CONFIG->dbprefix}users {$where}","row_to_elgguser");
            
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
	 * Set the site metadata.
	 *
	 * @param string $name
	 * @param string $value
	 * @param int $access_id
	 * @param int $user_id
	 * @param string $vartype
	 */
	function set_user_metadata($name, $value, $access_id, $user_id, $vartype = "")
	{
		$name = sanitise_string($name);
		$value = sanitise_string($value);
		$access_id = (int)$access_id;
		$user_id = (int)$user_id;
		$vartype = sanitise_string($vartype);
		$owner_id = $_SESSION['id'];
		
		$id = create_metadata($user_id, 'user', $name, $value, $vartype, $owner_id, $access_id);
		return $id;
	}
	
	/**
	 * Get user metadata.
	 *
	 * @param string $name
	 * @param int $user_id
	 */
	function get_user_metadata($name, $user_id)
	{
		$name = sanitise_string($name);
		$user_id = (int)$user_id;
		
		return get_metadatas($user_id, 'user');
	}
	
	/**
	 * Remove user metadata
	 *
	 * @param int $user_id
	 * @param string $name
	 */
	function remove_user_metadata($user_id, $name)
	{
		$result = get_metadatas($user_id, 'user', $name);
		
		if ($result)
		{
			foreach ($result as $r)
				delete_metadata($r->id);
		}
		
		return false;
	}
	
	/**
	 * Adds an annotation to a user. By default, the type is detected automatically; 
	 * however, it can also be set. Note that by default, annotations are private.
	 * 
	 * @param string $name
	 * @param string $value
	 * @param int $access_id
	 * @param int $owner_id
	 * @param int $user_id
	 * @param string $vartype
	 */
	function add_user_annotation($name, $value, $access_id, $owner_id, $user_id, $vartype)
	{
		$name = sanitise_string($name);
		$value = sanitise_string($value);
		$access_id = (int)$access_id;
		$owner_id = (int)$owner_id; if ($owner_id==0) $owner_id = $_SESSION['id'];
		$user_id = (int)$user_id;
		$vartype = sanitise_string($vartype);
		
		$id = create_annotation($user_id, 'user', $name, $value, $vartype, $owner_id, $access_id);
		
		return $id;
	}
	
	/**
	 * Get the annotations for a user.
	 *
	 * @param string $name
	 * @param int $user_id
	 * @param int $limit
	 * @param int $offset
	 */
	function get_user_annotations($name, $user_id, $limit, $offset)
	{
		$name = sanitise_string($name);
		$user_id = (int)$user_id;
		$limit = (int)$limit;
		$offset = (int)$offset;
		$owner_id = (int)$owner_id; if ($owner_id==0) $owner_id = $_SESSION['id']; // Consider adding the option to change in param?
		
		return get_annotations($user_id, 'site', "","", $owner_id, "created desc", $limit, $offset);
	}
	
	/**
	 * Count the annotations for a user of a given type.
	 *
	 * @param string $name
	 * @param int $user_id
	 */
	function count_user_annotations($name, $user_id) { return count_annotations($user_id, 'user', $name); }
	
	/**
	 * Get the average of an integer type annotation.
	 *
	 * @param string $name
	 * @param int $user_id
	 */
	function get_user_annotations_avg($name, $user_id) { return get_annotations_avg($object_id, 'user', $name); }
	
	/**
	 * Get the sum of integer type annotations of a given type.
	 *
	 * @param string $name
	 * @param int $user_id
	 */
	function get_user_annotations_sum($name, $user_id) { return get_annotations_sum($object_id, 'user', $name); }
	
	/**
	 * Get the min of integer type annotations of a given type.
	 *
	 * @param string $name
	 * @param int $user_id
	 */
	function get_user_annotations_min($name, $user_id) { return get_annotations_min($object_id, 'user', $name); }
	
	/**
	 * Get the max of integer type annotations of a given type.
	 *
	 * @param string $name
	 * @param int $user_id
	 */
	function get_user_annotations_max($name, $user_id) { return get_annotations_max($object_id, 'user', $name); }
	
	/**
	 * Remove all user annotations, or user annotations of a given type.
	 *
	 * @param int $user_id
	 * @param string $name
	 */
	function remove_user_annotations($user_id, $name)
	{
		$annotations = get_annotations($user_id, 'site', $name);
		
		if($annotations)
		{
			foreach ($annotations as $a)
			{
				delete_annotation($a->id);
			}
			
			return true;
		}
		
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
                        
            if ($users = get_users(array("username" => $username,
                                         "password" => $dbpassword))) {
                 foreach($users as $user) {
                     
                     $_SESSION['id'] = $user->id;
                     $_SESSION['username'] = $user->username;
                     $_SESSION['name'] = $user->name;
                     
                     $code = (md5($user->name . $user->username . time() . rand()));
                     update_data("update {$CONFIG->dbprefix}users set code = '".md5($code)."' where id = {$user->id}");
                     
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
            
            unset($_SESSION['username']);
            unset($_SESSION['name']);
            unset($_SESSION['code']);
            update_data("update {$CONFIG->dbprefix}users set code = '' where id = {$_SESSION['id']}");
            $_SESSION['id'] = -1;
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
	        
	        if (empty($_SESSION['id'])) {
	            if (isset($_COOKIE['elggperm'])) {
	                                
	                $code = $_COOKIE['elggperm'];
	                $code = md5($code);
	                if ($users = get_users(array(
	                                                "code" =>$code
	                                            ), "id, username, name, password")) {
	                    foreach($users as $user) {
	                    	$_SESSION['user'] = get_user($user->id);
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
	
	//register actions *************************************************************
   
   		register_action("login",true);
    	register_action("logout");
		
?>