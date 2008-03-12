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
	 * This class represents an Elgg object.
	 *
	 */
		class ElggObject {
			
			private $attributes = array();
			
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
			
			function __construct($id = null) {
				
				global $CONFIG;
				$this->attributes = array();
				
				if (!empty($id)) {
					if ($id instanceof stdClass)
						$object = $id; // Create from db row
					else
						$object = get_object($id);
						
					if ($object) {
						$objarray = (array) $object;
						foreach($objarray as $key => $value) {
							$this->attributes[$key] = $value;
						}
					}
				} else {
					$this->site_id = $CONFIG->site_id;
				}
			}

			
		/**
		 * Obtains the parent site
		 *
		 * @return ElggSite The parent site
		 */
			function getSite() {
				return get_site($this->site_id);
			}
			
		/**
		 * Obtains the owning user
		 *
		 * @return ElggUser The owning user
		 */
			function getOwner() {
				return get_user($this->owner_id);
			}
			
		/**
		 * Inserts or updates the object, depending on whether it's new or not
		 *
		 * @return true|false Depending on success
		 */
			function save() {
				if (isset($this->id)) {
					return update_object($this->id, $this->title, $this->description, $this->type, $this->owner_id, $this->access_id, $this->site_id);				
				} else if ($id = create_object($this->title,$this->description,$this->type,$this->owner_id,$this->access_id,$this->site_id)) {
					$this->id = $id;
					return true;
				}
				return false;
			}
			
		/**
		 * Deletes this object
		 *
		 * @uses delete_object
		 * @return int|false The number of objects deleted, or false on failure
		 */
			function delete() {
				if (!empty($this->id)) {
					return delete_object($this->id);
				}
				return false;
			}
			
					
		/**
		 * Set the meta data.
		 *
		 * @param string $name
		 * @param string $value
		 * @param int $access_id
		 * @param string $vartype
		 */
			function setMetadata($name, $value, $access_id = 0, $vartype = "") { return set_object_metadata($name, $value, $access_id, $this->id, $vartype); }
		
		/**
		 * Get the metadata for a object.
		 *
		 * @param string $name
		 */
			function getMetadata($name) { return get_object_metadata($name, $this->id); }
		
		/**
		 * Clear the metadata for a given object.
		 *
		 * @param string $name
		 */
			function clearMetadata($name = "") { return remove_object_metadata($this->id, $name); }
			
		/**
		 * Adds an annotation to a object. By default, the type is detected automatically; however, 
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
		 * Get the annotations for a object.
		 *
		 * @param string $name
		 * @param int $limit
		 * @param int $offset
		 */
			function getAnnotations($name, $limit = 50, $offset = 0) { return get_site_annotations($name, $this->id, $limit, $offset); }
		
		/**
		 * Return the annotations for the object.
		 *
		 * @param string $name The type of annotation.
		 */
			function countAnnotations($name) { return count_object_annotations($name, $this->id); }

		/**
		 * Get the average of an integer type annotation.
		 *
		 * @param string $name
		 */
			function getAnnotationsAvg($name) { return get_object_annotations_avg($name, $this->id); }
		
		/**
		 * Get the sum of integer type annotations of a given type.
		 *
		 * @param string $name
		 */
			function getAnnotationsSum($name) { return get_object_annotations_sum($name, $this->id); }
		
		/**
		 * Get the minimum of integer type annotations of given type.
		 *
		 * @param string $name
		 */
			function getAnnotationsMin($name) { return get_object_annotations_min($name, $this->id); }
		
		/**
		 * Get the maximum of integer type annotations of a given type.
		 *
		 * @param string $name
		 */
			function getAnnotationsMax($name) { return get_object_annotations_max($name, $this->id); }
		
		/**
		 * Remove all annotations or all annotations of a given object.
		 *
		 * @param string $name
		 */
			function removeAnnotations($name = "") { return remove_object_annotations($this->id, $name); }
		

		}
	

	/**
	 * Converts a standard database row result to an ElggObject
	 *
	 * @param object $row The database row object
	 * @return ElggObject The formatted ElggObject
	 */
		function row_to_elggobject($row) {
			if (empty($row))
				return $row;
			
			return new ElggObject($row);
		}

	/**
	 * Get object reverse ordered by publish time, optionally filtered by user and/or type
	 *
	 * @param int $user_id The ID of the publishing user; set to 0 for all users
	 * @param string $type The type of the object; set to blank for all types
	 * @param int $limit The number of objects (default 10)
	 * @param int $offset The offset of the return, for pagination
	 * @param int $site_id The site the objects belong to (leave blank for default site)
	 * @return unknown
	 */
		function get_objects($user_id = 0, $type = "", $limit = 10, $offset = 0, $site_id = 0) {
			
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
			$query .= " where o.site_id = {$site_id} ";
			$query .= " and (o.access_id in {$access} or (o.access_id = 0 and o.owner_id = {$_SESSION['id']}))";
			if (!empty($type)) $query .= " and ot.name = '{$type}'";
			if ($user_id > 0) $query .= " and o.owner_id = {$user_id} ";
			$query .= " order by o.time_created desc ";
			if ($limit > 0 || $offset > 0) $query .= " limit {$offset}, {$limit}";
			
			return get_data($query,"row_to_elggobject");
			
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
			
			$row = get_data_row("select o.*, ot.name as typename from {$CONFIG->dbprefix}objects left join {$CONFIG->dbprefix}object_types ot on ot.id = o.type_id where (o.access_id in {$access} or (o.access_id = 0 and o.owner_id = {$_SESSION['id']}))");
			return row_to_elggobject($row);			
			
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
			
			if (!($object = get_object($object_id)))
				return false;
			
			if (!(trigger_event("delete","object",$ibject)))
				return false;

			$object->removeAnnotations();
			$object->clearMetadata();
				
			if (delete_data("delete from {$CONFIG->dbprefix}objects where o.id = {$object_id} and o.owner_id = {$_SESSION['id']}")) {
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
			if (in_array($access_id,get_access_array())) {
				
				$type_id = get_object_type_id($type);
				
				$query = " insert into {$CONFIG->dbprefix}objects ";
				$query .= "(`title`,`description`,`type_id`,`owner_id`,`site_id`,`access_id`) values ";
				$query .= "('{$title}','{$description}', {$type_id}, {$owner}, {$site_id}, {$access_id})";
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

		function update_object($id, $title = null, $description = null, $type = null, $owner_id = null, $access_id = null, $site_id = null) {

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
			if ($owner == $_SESSION['id'] && in_array($access_id,get_access_array())) {
			
				$type_id = get_object_type_id($type);
				
				$params = array();
				foreach(array('title','description','owner','access_id','site_id','owner','type_id') as $param) {
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
	 * Set the site metadata.
	 *
	 * @param string $name
	 * @param string $value
	 * @param int $access_id
	 * @param int $object_id
	 * @param string $vartype
	 */
	function set_object_metadata($name, $value, $access_id, $object_id, $vartype = "")
	{
		$name = sanitise_string($name);
		$value = sanitise_string($value);
		$access_id = (int)$access_id;
		$object_id = (int)$object_id;
		$vartype = sanitise_string($vartype);
		$owner_id = $_SESSION['id'];
		
		$id = create_metadata($object_id, 'object', $name, $value, $vartype, $owner_id, $access_id);
		return $id;
	}
	
	/**
	 * Get object metadata.
	 *
	 * @param string $name
	 * @param int $object_id
	 */
	function get_object_metadata($name, $object_id)
	{
		$name = sanitise_string($name);
		$object_id = (int)$object_id;
		
		return get_metadatas($object_id, 'object');
	}
	
	/**
	 * Remove object metadata
	 *
	 * @param int $object_id
	 * @param string $name
	 */
	function remove_object_metadata($object_id, $name)
	{
		$result = get_metadatas($object_id, 'object', $name);
		
		if ($result)
		{
			foreach ($result as $r)
				delete_metadata($r->id);
		}
		
		return false;
	}
	
	/**
	 * Adds an annotation to a object. By default, the type is detected automatically; 
	 * however, it can also be set. Note that by default, annotations are private.
	 * 
	 * @param string $name
	 * @param string $value
	 * @param int $access_id
	 * @param int $owner_id
	 * @param int $object_id
	 * @param string $vartype
	 */
	function add_object_annotation($name, $value, $access_id, $owner_id, $object_id, $vartype)
	{
		$name = sanitise_string($name);
		$value = sanitise_string($value);
		$access_id = (int)$access_id;
		$owner_id = (int)$owner_id; if ($owner_id==0) $owner_id = $_SESSION['id'];
		$object_id = (int)$object_id;
		$vartype = sanitise_string($vartype);
		
		$id = create_annotation($object_id, 'object', $name, $value, $vartype, $owner_id, $access_id);
		
		return $id;
	}
	
	/**
	 * Get the annotations for a object.
	 *
	 * @param string $name
	 * @param int $object_id
	 * @param int $limit
	 * @param int $offset
	 */
	function get_object_annotations($name, $object_id, $limit, $offset)
	{
		$name = sanitise_string($name);
		$object_id = (int)$object_id;
		$limit = (int)$limit;
		$offset = (int)$offset;
		$owner_id = (int)$owner_id; if ($owner_id==0) $owner_id = $_SESSION['id']; // Consider adding the option to change in param?
		
		return get_annotations($object_id, 'site', "","", $owner_id, "created desc", $limit, $offset);
	}
	
	/**
	 * Count the annotations for a object of a given type.
	 *
	 * @param string $name
	 * @param int $object_id
	 */
	function count_object_annotations($name, $object_id) { return count_annotations($object_id, 'object', $name); }
	
	/**
	 * Get the average of an integer type annotation.
	 *
	 * @param string $name
	 * @param int $object_id
	 */
	function get_object_annotations_avg($name, $object_id) { return get_annotations_avg($object_id, 'object', $name); }
	
	/**
	 * Get the sum of integer type annotations of a given type.
	 *
	 * @param string $name
	 * @param int $object_id
	 */
	function get_object_annotations_sum($name, $object_id) { return get_annotations_sum($object_id, 'object', $name); }
	
	/**
	 * Get the min of integer type annotations of a given type.
	 *
	 * @param string $name
	 * @param int $object_id
	 */
	function get_object_annotations_min($name, $object_id) { return get_annotations_min($object_id, 'object', $name); }
	
	/**
	 * Get the max of integer type annotations of a given type.
	 *
	 * @param string $name
	 * @param int $object_id
	 */
	function get_object_annotations_max($name, $object_id) { return get_annotations_max($object_id, 'object', $name); }
	
	/**
	 * Remove all object annotations, or object annotations of a given type.
	 *
	 * @param int $object_id
	 * @param string $name
	 */
	function remove_object_annotations($object_id, $name)
	{
		$annotations = get_annotations($object_id, 'site', $name);
		
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
		
	
?>