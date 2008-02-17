<?php

	/**
	 * Elgg objects
	 * Forms the basis of object storage and retrieval
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Get object reverse ordered by publish time, optionally filtered by user and/or type
	 *
	 * @param int $user_id The ID of the publishing user; set to 0 for all users
	 * @param string $type The type of the object; set to blank for all types
	 * @param string $metadata_type The type of metadata that we're searching on (blank for none)
	 * @param string $metadata_value The value of metadata that we're searching on (blank for none)
	 * @param int $limit The number of objects (default 10)
	 * @param int $offset The offset of the return, for pagination
	 * @param int $site_id The site the objects belong to (leave blank for default site)
	 * @return unknown
	 */
		function get_objects($user_id = 0, $type = "", $metadata_type = "", $metadata_value = "", $limit = 10, $offset = 0, $site_id = 0) {
			
			global $CONFIG;
			
			$user_id = (int) $user_id;
			$type = sanitise_string($type);
			$limit = (int) $limit;
			$offset = (int) $offset;
			$site_id = (int) $site_id;
			if ($site_id == 0) $site_id = $CONFIG->site_id;
			$access = get_access_list();
			
			$query = "select o.*, ot.name as typename from {$CONFIG->dbprefix}objects o ";
			if (!empty($type)) $query .= " left join {$CONFIG->dbprefix}object_types ot on ot.id = o.type_id ";
			if (!empty($metadata_type) && !empty($metadata_value)) {
				$metadata_type = sanitise_string($metadata_type);
				$metadata_value = sanitise_string($metadata_value);
				$query .= " left join {$CONFIG->dbprefix}object_metadata om on om.object_id = o.id ";
				$query .= " left join {$CONFIG->dbprefix}metadata_value mv on mv.id = om.value_id ";
				$query .= " left join {$CONFIG->dbprefix}metadata_type mt on mt.id = om.metadata_type_id "; 
			}
			$query .= " where o.site_id = {$site_id} ";
			$query .= " and (o.access_id in {$access} or (o.access_id = 0 and o.owner_id = {$_SESSION['id']}))";
			if (!empty($type)) $query .= " and ot.name = '{$type}'";
			if ($user_id > 0) $query .= " and o.owner_id = {$user_id} ";
			if (!empty($metadata_type) && !empty($metadata_value)) {
				$query .= " and mv.value = '{$metadata_value}' and mt.name = '{$metadata_type}' ";
				$query .= " and (om.access_id in {$access} or (om.access_id = 0 and o.owner_id = {$_SESSION['id']}))";
			}
			$query .= " order by o.time_created desc ";
			if ($limit > 0 || $offset > 0) $query .= " limit {$offset}, {$limit}";
			
			return get_data($query);
			
		}

	/**
	 * Retrieves details about an object, if the current user is allowed to see it
	 *
	 * @param int $object_id The ID of the object to load
	 * @return object A database representation of the object
	 */
		function get_object($object_id) {
			
			global $CONFIG;
			
			$object_id = (int) $object_id;
			$access = get_access_list();
			
			return get_data_row("select o.*, ot.name as typename from {$CONFIG->dbprefix}objects left join {$CONFIG->dbprefix}object_types ot on ot.id = o.type_id where (o.access_id in {$access} or (o.access_id = 0 and o.owner_id = {$_SESSION['id']}))");			
			
		}
		
	/**
	 * Deletes an object and all accompanying metadata
	 *
	 * @param int $object_id The ID of the object
	 * @return true|false Depending on success
	 */
		function delete_object($object_id) {
			
			global $CONFIG;
			
			$object_id = (int) $object_id;
			$access = get_access_list();
			
			if (delete_data("delete from {$CONFIG->dbprefix}objects where o.id = {$object_id} and o.owner_id = {$_SESSION['id']}")) {
				remove_object_metadata($object_id);
				return true;
			}
			
			return false;
		}

		
	/**
	 * Creates an object
	 *
	 * @param string $title Object title
	 * @param string $description A description of the object
	 * @param string $type The textual type of the object (eg "blog")
	 * @param int $owner The owner of the object (defaults to currently logged in user)
	 * @param int $access_id The access restriction on the object (defaults to private)
	 * @param int $site_id The site the object belongs to
	 * @return int The ID of the newly-inserted object
	 */
		function create_object($title, $description, $type, $owner = 0, $access_id = 0, $site_id = 0) {
			
			global $CONFIG;
			
			$title = sanitise_string($title);
			$description = sanitise_string($description);
			$owner = (int) $owner;
			$site_id = (int) $site_id;
			$access_id = (int) $access_id;
			if ($site_id == 0) $site_id = $CONFIG->site_id;
			if ($owner == 0) $owner = $_SESSION['id'];
			
			// We can't let non-logged in users create data
			// We also need the access restriction to be valid
			if ($owner > 0 && in_array($access_id,get_access_array())) {
				
				$type_id = get_object_type_id($type);
				
				$query = " insert into {$CONFIG->dbprefix}objects ";
				$query .= "(`title`,`description`,`type_id`,`owner_id`,`site_id`,`access_id`) values ";
				$query .= "('{$title}','{$description}', {$type_id}, {$owner}, {$site_id}, {$access_id}";
				return insert_data($query);
				
			}
			return false;
			
		}

	/**
	 * Update an object
	 * Note that to write to an object, you must be logged in as the owner
	 *
	 * @param int $id The ID of the object
	 * @param string $title Object title
	 * @param string $description A description of the object
	 * @param string $type The textual type of the object (eg "blog")
	 * @param int $owner The owner of the object (defaults to currently logged in user)
	 * @param int $access_id The access restriction on the object (defaults to private)
	 * @param int $site_id The site the object belongs to
	 * @return int|false Either 1 or 0 (the number of objects updated) or false on failure
	 */
		
		function update_object($id, $title = null, $description = null, $type = null, $owner = null, $access_id = null, $site_id = null) {

			global $CONFIG;
			
			$id = (int) $id;
			if ($title != null) $title = sanitise_string($title);
			if ($description != null) $description = sanitise_string($description);
			if ($owner_id != null) $owner_id = (int) $owner_id;
			if ($site_id != null) $site_id = (int) $site_id;
			if ($access_id != null) $access_id = (int) $access_id;
			if ($site_id != null) if ($site_id == 0) $site_id = $CONFIG->site_id;
			if ($owner_id != null) if ($owner_id == 0) $owner = $_SESSION['id'];
			
			// We can't let non-logged in users create data
			// We also need the access restriction to be valid
			if ($owner > 0 && in_array($access_id,get_access_array())) {
			
				$params = array();
				foreach(array('title','description','owner','site_id','access_id','site_id','owner') as $param) {
					if ($$param != null) {
						$params[] = "{$param} = '{$$param}'";
					}
				}
				
				return update_data("update {$CONFIG->prefix}objects set " . implode(",",$params) . " where id = {$id} and owner_id = {$_SESSION['id']}");
				
			}
			return false;
			
		}
		
	/**
	 * Gets the ID of an object type in the database, setting it if necessary 
	 *
	 * @param string $type The name of the object type
	 * @return int|false The database ID of the object type, or false if the given type was invalid
	 */
		function get_object_type_id($type) {
			
			global $CONFIG;
			
			$type = strtolower(trim(sanitise_string($type)));
			if (!empty($type) && $dbtype = get_data_row("select id from {$CONFIG->dbprefix}object_types where name = '{$type}'")) {
				return $dbtype->id;
			} else if (!empty($type)) {
				return insert_data("insert into {$CONFIG->dbprefix}object_types set name = '{$type}'");
			}
			return false;
			
		}
		
	/**
	 * Gets the ID of an object metadata type in the database, setting it if necessary 
	 *
	 * @param string $type The name of the metadata type
	 * @return int|false The database ID of the metadata type, or false if the given type was invalid
	 */
		function get_metadata_type_id($type) {
			
			global $CONFIG;
			$type = strtolower(trim(sanitise_string($type)));
			if (!empty($type) && $dbtype = get_data_row("select id from {$CONFIG->dbprefix}metadata_type where name = '{$type}'")) {
				return $dbtype->id;
			} else if (!empty($type)) {
				return insert_data("insert into {$CONFIG->dbprefix}metadata_type set name = '{$type}'");
			}
			return false;
			
		}

	/**
	 * Gets the ID of an object metadata value in the database, setting it if necessary 
	 *
	 * @param string $type The metadata value
	 * @return int|false The database ID of the metadata value, or false if the given value was invalid
	 */
		function get_metadata_value_id($value) {
			
			global $CONFIG;
			$type = strtolower(trim(sanitise_string($value)));
			if (!empty($value) && $dbtype = get_data_row("select id from {$CONFIG->dbprefix}metadata_value where value = '{$value}'")) {
				return $dbtype->id;
			} else if (!empty($value)) {
				return insert_data("insert into {$CONFIG->dbprefix}metadata_value set value = '{$value}'");
			}
			return false;
			
		}
		
	/**
	 * Sets a piece of metadata for a particular object.
	 *
	 * @param string $metadata_name The type of metadata
	 * @param string $metadata_value Its value
	 * @param int $access_id The access level of the metadata
	 * @param int $object_id The ID of the object
	 * @return true|false depending on success
	 */
		function set_object_metadata($metadata_name, $metadata_value, $access_id, $object_id, $site_id = 0) {
			global $CONFIG;
			$object_id = (int) $object_id;
			if ($object = get_object($object_id)) {
				if ($object->owner_id == $_SESSION['id']) {
					
					$access_id = (int) $access_id;
					if ($site_id == 0) $site_id = $CONFIG->site_id;
					$site_id = (int) $site_id;
					
					if ($type_id = get_object_metadata_type_id($metadata_name)
						&& $value_id = get_object_metadata_value_id($metadata_value)
						&& in_array($access_id,get_access_array())) {
						delete_data("delete from {$CONFIG->dbprefix}object_metadata where metadata_type_id = {$type_id} and object_id = {$object_id}");
						return insert_data("insert into {$CONFIG->dbprefix}object_metadata set object_id = {$object_id}, access_id = {$access_id}, metadata_type_id = {$type_id}, value_id = {$value_id}, site_id = {$site_id}");						
					} else {
						return false;
					}
					
				}
			} else {
				return false;
			}
		}

	/**
	 * Removes a piece of (or all) metadata for a particular object.
	 *
	 * @param int $object_id The ID of the object
	 * @param string $metadata_name The type of metadata; blank for all metadata
	 * @return true|false depending on success
	 */
		function remove_object_metadata($object_id, $metadata_name = "") {
			global $CONFIG;
			$object_id = (int) $object_id;
			if ($object = get_object($object_id)) {
				if ($object->owner_id == $_SESSION['id']) {
					
					if ($type_id = get_object_metadata_type_id($metadata_name)) {
						return delete_data("delete from {$CONFIG->dbprefix}object_metadata where metadata_type_id = {$type_id} and object_id = {$object_id}");						
					} else {
						return false;
					}
					
				}
			} else {
				return false;
			}
			return true;
		}

?>