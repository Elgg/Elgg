<?php

	/**
	 * Elgg access permissions
	 * For users, objects, collections and all metadata
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Get the list of access restrictions the given user is allowed to see on this site
	 *
	 * @uses get_access_array
	 * @param int $user_id User ID; defaults to currently logged in user
	 * @param int $site_id Site ID; defaults to current site 
	 * @param boolean $flush If set to true, will refresh the access list from the database
	 * @return string A list of access collections suitable for injection in an SQL call
	 */
		function get_access_list($user_id = 0, $site_id = 0, $flush = false) {
			
			global $CONFIG;
			static $access_list;
			
			if (!isset($access_list))
				$access_list = array();
			
			if ($user_id == 0) $user_id = $_SESSION['id'];
			if (($site_id == 0) && (isset($CONFIG->site_id))) $site_id = $CONFIG->site_id;
			$user_id = (int) $user_id;
			$site_id = (int) $site_id;
			
			if (empty($access_list[$user_id]) || $flush == true) {
				
				$access_list[$user_id] = "(" . implode(",",get_access_array($user_id, $site_id, $flush)) . ")";
				
			}
			
			return $access_list[$user_id];
			
		}
		
	/**
	 * Gets an array of access restrictions the given user is allowed to see on this site
	 *
	 * @param int $user_id User ID; defaults to currently logged in user
	 * @param int $site_id Site ID; defaults to current site 
	 * @param boolean $flush If set to true, will refresh the access list from the database
	 * @return array An array of access collections suitable for injection in an SQL call
	 */
		function get_access_array($user_id = 0, $site_id = 0, $flush = false) {
			
			global $CONFIG;
			static $access_array;
			
			if (!isset($access_array))
				$access_array = array();
			
			if ($user_id == 0) $user_id = $_SESSION['guid'];
			if (($site_id == 0) && (isset($CONFIG->site_id))) $site_id = $CONFIG->site_id;
			$user_id = (int) $user_id;
			$site_id = (int) $site_id;
			
			if (empty($access_array[$user_id]) || $flush == true) {
				
				$query = "select am.access_collection_id from {$CONFIG->dbprefix}access_collection_membership am ";
				$query .= " left join {$CONFIG->dbprefix}access_collections ag on ag.id = am.access_collection_id ";
				$query .= " where am.user_guid = {$user_id} and (ag.site_guid = {$site_id} or ag.site_guid = 0)";
				
				$tmp_access_array = array(2);
				if (isloggedin())
					$tmp_access_array[] = 1;
				
				if ($collections = get_data($query)) {
					foreach($collections as $collection)
						if (!empty($collection->access_collection_id)) $tmp_access_array[] = $collection->access_collection_id;
				}
				
				$query = "select ag.id from {$CONFIG->dbprefix}access_collections ag  ";
				$query .= " where ag.owner_guid = {$user_id} and (ag.site_guid = {$site_id} or ag.site_guid = 0)";
				
				if ($collections = get_data($query)) {
					foreach($collections as $collection)
						if (!empty($collection->id)) $tmp_access_array[] = $collection->id;
				}
				
				$access_array[$user_id] = $tmp_access_array;
				
			} else {
				$tmp_access_array = $access_array[$user_id];
			}
			
			$tmp_access_array = trigger_plugin_hook('access:collections','user',array('user_id' => $user_id, 'site_id' => $site_id),$tmp_access_array);
			
			return $access_array[$user_id];
			
		}
		
		/**
		 * Add access restriction sql code to a given query.
		 * 
		 * Note that if this code is executed in privileged mode it will return blank.
		 * 
		 * TODO: DELETE once Query classes are fully integrated
		 * 
		 * @param string $table_prefix Optional xxx. prefix for the access code.
		 */
		function get_access_sql_suffix($table_prefix = "")
		{
			$sql = "";
			
			if ($table_prefix)
					$table_prefix = sanitise_string($table_prefix) . ".";
			
			//if (!is_privileged())
			//{
				$access = get_access_list();
				
				$owner = $_SESSION['id'];
				if (!$owner) $owner = -1;
				
				$sql = " ({$table_prefix}access_id in {$access} or ({$table_prefix}access_id = 0 and {$table_prefix}owner_guid = $owner))";
			//}
			//else
			//	$sql = " 1 ";
				
			// Only return 'active' objects
			$sql .= " and {$table_prefix}enabled='yes'";
			
			return $sql;
		}
		
		/**
		 * Returns an array of access permissions that the specified user is allowed to save objects with.
		 * Permissions are of the form ('id' => 'Description')
		 *
		 * @param int $user_id The user's GUID.
		 * @param int $site_id The current site.
		 * @param true|false $flush If this is set to true, this will shun any cached version
		 * @return array List of access permissions=
		 */
		function get_write_access_array($user_id = 0, $site_id = 0, $flush = false) {
			
			global $CONFIG;
			static $access_array;
			
			if ($user_id == 0) $user_id = $_SESSION['guid'];
			if (($site_id == 0) && (isset($CONFIG->site_id))) $site_id = $CONFIG->site_id;
			$user_id = (int) $user_id;
			$site_id = (int) $site_id;
			
			if (empty($access_array[$user_id]) || $flush == true) {
				
				$query = "select ag.* from {$CONFIG->dbprefix}access_collections ag ";
				$query .= " where (ag.site_guid = {$site_id} or ag.site_guid = 0)";
				$query .= " and (ag.owner_guid = {$user_id})";
				$query .= " and ag.id > 3";
				
				$tmp_access_array = array(0 => elgg_echo("PRIVATE"), 1 => elgg_echo("LOGGED_IN"), 2 => elgg_echo("PUBLIC"));
				if ($collections = get_data($query)) {
					foreach($collections as $collection)
						$tmp_access_array[$collection->id] = $collection->name;
				}
				
				$access_array[$user_id] = $tmp_access_array;
				
			} else {
				$tmp_access_array = $access_array[$user_id];
			}
			
			$tmp_access_array = trigger_plugin_hook('access:collections:write','user',array('user_id' => $user_id, 'site_id' => $site_id),$tmp_access_array);
			
			return $tmp_access_array;
			
		}

		/**
		 * Creates a new access control collection owned by the specified user.
		 *
		 * @param string $name The name of the collection.
		 * @param int $owner_guid The GUID of the owner (default: currently logged in user).
		 * @param int $site_guid The GUID of the site (default: current site).
		 * @return int|false Depending on success (the collection ID if successful).
		 */
		function create_access_collection($name, $owner_guid = 0, $site_guid = 0) {
			
			$name = trim($name);
			if (empty($name)) return false;
			
			if ($user_id == 0) $user_id = $_SESSION['id'];
			if (($site_id == 0) && (isset($CONFIG->site_id))) $site_id = $CONFIG->site_id;
			$name = sanitise_string($name);
			
			global $CONFIG;
			
			return insert_data("insert into {$CONFIG->dbprefix}access_collections set name = '{$name}', owner_guid = {$owner_guid}, site_guid = {$site_guid}");
			
		}
		
		/**
		 * Deletes a specified access collection
		 *
		 * @param int $collection_id The collection ID
		 * @return true|false Depending on success
		 */
		function delete_access_collection($collection_id) {
			
			$collection_id = (int) $collection_id;
			$collections = get_write_access_array();
			if (array_key_exists($collection_id, $collections)) {
				global $CONFIG;
				delete_data("delete from {$CONFIG->dbprefix}access_collection_membership where access_collection_id = {$collection_id}");
				delete_data("delete from {$CONFIG->dbprefix}access_collections where id = {$collection_id}");
				return true;
			} else {
				return false;
			}
			
		}
		
		/**
		 * Get a specified access collection
		 *
		 * @param int $collection_id The collection ID
		 * @return array|false Depending on success
		 */
		function get_access_collection($collection_id) {
    		
    		$collection_id = (int) $collection_id;
    		global $CONFIG;
    		$get_collection = get_data("SELECT * FROM {$CONFIG->dbprefix}access_collections WHERE id = {$collection_id}");
    		
    		return $get_collection;
    		
		}
		
		/**
		 * Adds a user to the specified user collection
		 *
		 * @param int $user_guid The GUID of the user to add
		 * @param int $collection_id The ID of the collection to add them to
		 * @return true|false Depending on success
		 */
		function add_user_to_access_collection($user_guid, $collection_id) {
			
			$collection_id = (int) $collection_id;
			$user_guid = (int) $user_guid;
			$collections = get_write_access_array();
			
			if (array_key_exists($collection_id, $collections) && $user = get_user($user_guid)) {
				
				global $CONFIG;
				insert_data("insert into {$CONFIG->dbprefix}access_collection_membership set access_collection_id = {$collection_id}, user_guid = {$user_guid}");
				return true;
				
			}
			
			return false;
			
		}

		/**
		 * Removes a user from an access collection
		 *
		 * @param int $user_guid The user GUID
		 * @param int $collection_id The access collection ID
		 * @return true|false Depending on success
		 */
		function remove_user_from_access_collection($user_guid, $collection_id) {
			
			$collection_id = (int) $collection_id;
			$user_guid = (int) $user_guid;
			$collections = get_write_access_array();
			
			if (array_key_exists($collection_id, $collections) && $user = get_user($user_guid)) {
				
				global $CONFIG;
				delete_data("delete from {$CONFIG->dbprefix}access_collection_membership where access_collection_id = {$collection_id} and user_guid = {$user_guid}");
				return true;
				
			}
			
			return false;
			
		}
		
		/**
		 * Get all of a users collections
		 *
		 * @param int $owner_guid The user ID
		 * @return true|false Depending on success
		 */
		function get_user_access_collections($owner_guid) {
			
			$owner_guid = (int) $owner_guid;
			
			global $CONFIG;
			
			$collections = get_data("SELECT * FROM {$CONFIG->dbprefix}access_collections WHERE owner_guid = {$owner_guid}");
			
			return $collections;
			
		}
		
		/**
		 * Get all of members of a friend collection
		 *
		 * @param int $collection The collection's ID
		 * @return ElggUser entities if successful, false if not
		 */
		function get_members_of_access_collection($collection) {
    		
    		$collection = (int)$collection;
    		
    		global $CONFIG;
		
		    $query = "select e.* from {$CONFIG->dbprefix}access_collection_membership m join {$CONFIG->dbprefix}entities e on e.guid = m.user_guid WHERE m.access_collection_id = {$collection}";
		    
			$collection_members = get_data($query, "entity_row_to_elggstar");
			
			return $collection_members;
			
		}
		
	/**
	 * Some useful constant definitions
	 */
		define('ACCESS_PRIVATE',0);
		define('ACCESS_LOGGED_IN',1);
		define('ACCESS_PUBLIC',2);
?>