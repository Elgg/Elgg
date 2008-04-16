<?php

	/**
	 * Elgg users
	 * Functions to manage multiple or single users in an Elgg install
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * ElggUser
	 * 
	 * Representation of a "user" in the system.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 */
	class ElggUser extends ElggEntity
	{
		/**
		 * Initialise the attributes array. 
		 * This is vital to distinguish between metadata and base parameters.
		 * 
		 * Place your base parameters here.
		 */
		protected function initialise_attributes()
		{
			parent::initialise_attributes();
			
			$this->attributes['type'] = "user";
			$this->attributes['name'] = "";
			$this->attributes['username'] = "";
			$this->attributes['password'] = "";
			$this->attributes['email'] = "";
			$this->attributes['language'] = "";
			$this->attributes['code'] = "";
			
		}
				
		/**
		 * Construct a new user entity, optionally from a given id value.
		 *
		 * @param mixed $guid If an int, load that GUID. 
		 * 	If a db row then will attempt to load the rest of the data.
		 * @throws Exception if there was a problem creating the user. 
		 */
		function __construct($guid = null) 
		{			
			$this->initialise_attributes();
			
			if (!empty($guid))
			{
				// Is $guid is a DB row - either a entity row, or a user table row.
				if ($guid instanceof stdClass) {					
					// Load the rest
					if (!$this->load($guid->guid))
						throw new IOException("Failed to load new ElggUser from GUID:$guid->guid"); 
				}
				
				// See if this is a username
				else if (is_string($guid))
				{					
					$guid = get_user_by_username($guid);
					foreach ($guid->attributes as $key => $value)
					 	$this->attributes[$key] = $value;
					 	
				}
				
				// Is $guid is an ElggUser? Use a copy constructor
				else if ($guid instanceof ElggUser)
				{					
					 foreach ($guid->attributes as $key => $value)
					 	$this->attributes[$key] = $value;
				}
				
				// Is this is an ElggEntity but not an ElggUser = ERROR!
				else if ($guid instanceof ElggEntity)
					throw new InvalidParameterException("Passing a non-ElggUser to an ElggUser constructor!");
										
				// We assume if we have got this far, $guid is an int
				else if (is_int($guid)) {					
					if (!$this->load($guid)) throw new IOException("Could not create a new ElggUser user from GUID:$guid");
				}
			}
		}
		
		/**
		 * Class member get overloading
		 *
		 * @param string $name
		 * @return mixed
		 */
		function __get($name) { return $this->get($name); }
		
		/**
		 * Class member set overloading
		 *
		 * @param string $name
		 * @param mixed $value
		 * @return mixed
		 */
		function __set($name, $value) { return $this->set($name, $value); }
		
		/**
		 * Override the load function.
		 * This function will ensure that all data is loaded (were possible), so
		 * if only part of the ElggUser is loaded, it'll load the rest.
		 * 
		 * @param int $guid
		 * @return true|false 
		 */
		protected function load($guid)
		{			
			// Test to see if we have the generic stuff
			if (!parent::load($guid)) 
				return false;

			// Check the type
			if ($this->attributes['type']!='user')
				throw new InvalidClassException("GUID:$guid is not a valid ElggUser");
				
			// Load missing data
			$row = get_user_entity_as_row($guid);
						
			// Now put these into the attributes array as core values
			$objarray = (array) $row;
			foreach($objarray as $key => $value) 
				$this->attributes[$key] = $value;
			
			return true;
		}
		
		/**
		 * Saves this user to the database.
		 * @return true|false
		 */
		public function save()
		{
			// Save generic stuff
			if (!parent::save())
				return false;
			
			// Now save specific stuff
			return create_user_entity($this->get('guid'), $this->get('name'), $this->get('username'), $this->get('password'), $this->get('email'), $this->get('language'), $this->get('code'));
		}
		
		/**
		 * Delete this user.
		 * @return true|false
		 */
		public function delete() 
		{ 
			if (!parent::delete())
				return false;
				
			return delete_user_entity($this->get('guid'));
		}
				
		/**
		 * Get sites that this user is a member of
		 *
		 * @param string $subtype Optionally, the subtype of result we want to limit to
		 * @param int $limit The number of results to return
		 * @param int $offset Any indexing offset
		 */
		function getSites($subtype="", $limit = 10, $offset = 0) {
			return get_site_users($this->getGUID(), $subtype, $limit, $offset);
		}
		
		/**
		 * Add this user to a particular site
		 *
		 * @param int $site_guid The guid of the site to add it to
		 * @return true|false
		 */
		function addToSite($site_guid) {
			return add_site_user($this->getGUID(), $site_guid); 
		}
		
		/**
		 * Remove this user from a particular site
		 *
		 * @param int $site_guid The guid of the site to remove it from
		 * @return true|false
		 */
		function removeFromSite($site_guid) {
			return remove_site_user($this->getGUID(), $site_guid);
		}
		
		/**
		 * Adds a user to this user's friends list
		 *
		 * @param int $friend_guid The GUID of the user to add
		 * @return true|false Depending on success
		 */
		function addFriend($friend_guid) { return user_add_friend($this->getGUID(), $friend_guid); }
		
		/**
		 * Removes a user from this user's friends list
		 *
		 * @param int $friend_guid The GUID of the user to remove
		 * @return true|false Depending on success
		 */
		function removeFriend($friend_guid) { return user_remove_friend($this->getGUID(), $friend_guid); }
		
		/**
		 * Determines whether or not this user is a friend of the currently logged in user
		 *
		 * @return true|false
		 */
		function isFriend() { return user_is_friend($this->getGUID(), $_SESSION['id']); }
		
		/**
		 * Determines whether this user is friends with another user
		 *
		 * @param int $user_guid The GUID of the user to check is on this user's friends list
		 * @return true|false
		 */
		function isFriendsWith($user_guid) { return user_is_friend($this->getGUID(), $user_guid); }
		
		/**
		 * Determines whether or not this user is on another user's friends list
		 *
		 * @param int $user_guid The GUID of the user to check against
		 * @return true|false
		 */
		function isFriendOf($user_guid) { return user_is_friend($user_guid, $this->getGUID()); }

		/**
		 * Retrieves a list of this user's friends
		 *
		 * @param string $subtype Optionally, the subtype of user to filter to (leave blank for all)
		 * @param int $limit The number of users to retrieve
		 * @param int $offset Indexing offset, if any
		 * @return array|false Array of ElggUsers, or false, depending on success
		 */
		function getFriends($subtype = "", $limit = 10, $offset = 0) { return get_user_friends($this->getGUID(), $subtype, $limit, $offset); }
		
		/**
		 * Get an array of ElggObjects owned by this user.
		 *
		 * @param string $subtype The subtype of the objects, if any
		 * @param int $limit Number of results to return
		 * @param int $offset Any indexing offset
		 */
		public function getObjects($subtype="", $limit = 10, $offset = 0) { return get_user_objects($this->getGUID(), $subtype, $limit, $offset); }

		/**
		 * Counts the number of ElggObjects owned by this user
		 *
		 * @param string $subtype The subtypes of the objects, if any
		 * @return int The number of ElggObjects
		 */
		public function countObjects($subtype = "") {
			return count_user_objects($this->getGUID(), $subtype);
		}

		/**
		 * Get the collections associated with a user.
		 *
		 * @param string $subtype Optionally, the subtype of result we want to limit to
		 * @param int $limit The number of results to return
		 * @param int $offset Any indexing offset
		 * @return unknown
		 */
		public function getCollections($subtype="", $limit = 10, $offset = 0) { return get_user_collections($this->getGUID(), $subtype, $limit, $offset); }
		
	}

	/**
	 * Return the user specific details of a user by a row.
	 * 
	 * @param int $guid
	 */
	function get_user_entity_as_row($guid)
	{
		global $CONFIG;
		
		$guid = (int)$guid;
		
		return get_data_row("SELECT * from {$CONFIG->dbprefix}users_entity where guid=$guid");
	}
	
	/**
	 * Create or update the extras table for a given user.
	 * Call create_entity first.
	 * 
	 * @param int $guid
	 * @param string $name
	 * @param string $description
	 * @param string $url
	 */
	function create_user_entity($guid, $name, $username, $password, $email, $language, $code)
	{
		global $CONFIG;
		
		$guid = (int)$guid;
		$name = sanitise_string($name);
		$username = sanitise_string($username);
		$password = sanitise_string($password);
		$email = sanitise_string($email);
		$language = sanitise_string($language);
		$code = sanitise_string($code);
		
		$row = get_entity_as_row($guid);
		
		if ($row)
		{
			// Exists and you have access to it
			
			// Delete any existing stuff
			delete_user_entity($guid);

			// Insert it
			$result = insert_data("INSERT into {$CONFIG->dbprefix}users_entity (guid, name, username, password, email, language, code) values ($guid, '$name', '$username', '$password', '$email', '$language', '$code')");
			if ($result!==false) 
				return true;
		}
		
		return false;
	}
	
	/**
	 * Delete a user's extra data.
	 * 
	 * @param int $guid
	 */
	function delete_user_entity($guid)
	{
		global $CONFIG;
		
		$guid = (int)$guid;
		
		$row = get_entity_as_row($guid);
		
		// Check to see if we have access and it exists
		if ($row) 
		{
			// Delete any existing stuff
			return delete_data("DELETE from {$CONFIG->dbprefix}users_entity where guid=$guid");
		}
		
		return false;
	}

	/**
	 * Get the sites this user is part of
	 *
	 * @param int $user_guid The user's GUID
	 * @param int $limit Number of results to return
	 * @param int $offset Any indexing offset
	 * @return false|array On success, an array of ElggSites
	 */
	function get_user_sites($user_guid, $limit = 10, $offset = 0) {
		$user_guid = (int)$user_guid;
		$limit = (int)$limit;
		$offset = (int)$offset;
		
		return get_entities_from_relationship("member_of_site", $user_guid, false, "site", "", 0, "time_created desc", $limit, $offset);
	}
	
	/**
	 * Adds a user to another user's friends list.
	 *
	 * @param int $user_guid The GUID of the friending user
	 * @param int $friend_guid The GUID of the user to friend
	 * @return true|false Depending on success
	 */
	function user_add_friend($user_guid, $friend_guid) {
		$user_guid = (int) $user_guid; 
		$friend_guid = (int) $friend_guid;
		return add_entity_relationship($user_guid, "friend", $friend_guid);
	}
	
	/**
	 * Removes a user from another user's friends list.
	 *
	 * @param int $user_guid The GUID of the friending user
	 * @param int $friend_guid The GUID of the user on the friends list
	 * @return true|false Depending on success
	 */
	function user_remove_friend($user_guid, $friend_guid) {
		$user_guid = (int) $user_guid; 
		$friend_guid = (int) $friend_guid;
		return remove_entity_relationship($user_guid, "friend", $friend_guid);
	}
	
	/**
	 * Determines whether or not a user is another user's friend.
	 *
	 * @param int $user_guid The GUID of the user
	 * @param int $friend_guid The GUID of the friend
	 * @return true|false
	 */
	function user_is_friend($user_guid, $friend_guid) {
		return check_entity_relationship($user_guid, "friend", $friend_guid);
	}

	/**
	 * Obtains a given user's friends
	 *
	 * @param int $user_guid The user's GUID
	 * @param string $subtype The subtype of users, if any
	 * @param int $limit Number of results to return (default 10)
	 * @param int $offset Indexing offset, if any
	 * @return false|array Either an array of ElggUsers or false, depending on success
	 */
	function get_user_friends($user_guid, $subtype = "", $limit = 10, $offset = 0) {
		return get_entities_from_relationship("friend",$user_guid,false,"user",$subtype,0,"time_created desc",$limit,$offset);
	}

	/**
	 * Obtains a list of objects owned by a user
	 *
	 * @param int $user_guid The GUID of the owning user
	 * @param string $subtype Optionally, the subtype of objects
	 * @param int $limit The number of results to return (default 10)
	 * @param int $offset Indexing offset, if any
	 * @return false|array An array of ElggObjects or false, depending on success
	 */
	function get_user_objects($user_guid, $subtype = "", $limit = 10, $offset = 0) {
		$ntt = get_entities('object',$subtype, $user_guid, "time_created desc", $limit, $offset);
		return $ntt;
	}
	
	/**
	 * Counts the objects (optionally of a particular subtype) owned by a user
	 *
	 * @param int $user_guid The GUID of the owning user
	 * @param string $subtype Optionally, the subtype of objects
	 * @return int The number of objects the user owns (of this subtype)
	 */
	function count_user_objects($user_guid, $subtype = "") {
		$total = get_entities('object', $subtype, $user_guid, "time_created desc", null, null, true);
		return $total;
	}
	
/**
	 * Obtains a list of objects owned by a user's friends
	 *
	 * @param int $user_guid The GUID of the user to get the friends of
	 * @param string $subtype Optionally, the subtype of objects
	 * @param int $limit The number of results to return (default 10)
	 * @param int $offset Indexing offset, if any
	 * @return false|array An array of ElggObjects or false, depending on success
	 */
	function get_user_friends_objects($user_guid, $subtype = "", $limit = 10, $offset = 0) {
		if ($friends = get_user_friends($user_guid, $subtype, 999999, 0)) {
			$friendguids = array();
			foreach($friends as $friend) {
				$friendguids[] = $friend->getGUID();
			}
			return get_entities('object',$subtype,$friendguids, "time_created desc", $limit, $offset);
		}
		return false;
	}
	
	/**
	 * Counts the number of objects owned by a user's friends
	 *
	 * @param int $user_guid The GUID of the user to get the friends of
	 * @param string $subtype Optionally, the subtype of objects
	 * @return int The number of objects
	 */
	function count_user_friends_objects($user_guid, $subtype = "") {
		if ($friends = get_user_friends($user_guid, $subtype, 999999, 0)) {
			$friendguids = array();
			foreach($friends as $friend) {
				$friendguids[] = $friend->getGUID();
			}
			return get_entities('object',$subtype,$friendguids, "time_created desc", $limit, $offset, true);
		}
		return 0;
	}
	
	/**
	 * Get a user object from a GUID.
	 * 
	 * This function returns an ElggUser from a given GUID.
	 * @param int $guid The GUID
	 * @return ElggUser|false 
	 * @throws InvalidParameterException if $GUID exists but is not an ElggUser.
	 */
	function get_user($guid)
	{
		if (!empty($guid)) // Fixes "Exception thrown without stack frame" when db_select fails
			$result = get_entity($guid);
		
		if ((!empty($result)) && (!($result instanceof ElggUser)))
			throw new InvalidParameterException("GUID:$guid is not an ElggUser");
			
		if (!empty($result))
			return $result;
		
		return false;	
	}
	
	/**
	 * Get user by username
	 *
	 * @param string $username The user's username
	 * @return ElggUser|false Depending on success
	 */
	function get_user_by_username($username)
	{
		global $CONFIG;
		
		$username = sanitise_string($username);
		$row = get_data_row("SELECT * from {$CONFIG->dbprefix}users_entity where username='$username'");
		if ($row)
			return new ElggUser($row); 
		
		return false;
	}
	
	/**
	 * Get user by session code
	 *
	 * @param string $code The session code
	 * @return ElggUser|false Depending on success
	 */
	function get_user_by_code($code)
	{
		global $CONFIG;
		
		$code = sanitise_string($code);
		$row = get_data_row("SELECT * from {$CONFIG->dbprefix}users_entity where code='$code'");
	
		if ($row)
			return new ElggUser($row); 
		
		return false;
	}
	
	/**
	 * Registers a user, returning false if the username already exists
	 *
	 * @param string $username The username of the new user
	 * @param string $password The password
	 * @param string $name The user's display name
	 * @param string $email Their email address
	 * @return int|false The new user's GUID; false on failure
	 */
	function register_user($username, $password, $name, $email) {
		
		// Load the configuration
			global $CONFIG;
			
		// A little sanity checking
			if (empty($username)
				|| empty($password)
				|| empty($name)
				|| empty($email)) {
					return false;
				}	
			
		// Check to see if $username exists already
			if ($user = get_user_by_username($username)) {
				return false;
			}
			
		// Otherwise ...
			$user = new ElggUser();
			$user->username = $username;
			$user->password = md5($password);
			$user->email = $email;
			$user->name = $name;
			$user->access_id = 2;
			$user->save();
			
			return $user->getGUID();
			
		
	}
	
	//register actions *************************************************************
   
   		register_action("register",true);
	
?>