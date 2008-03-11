<?php

	/**
	 * Elgg sites
	 * Functions to manage multiple or single sites in an Elgg install
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	
	/**
	 * @class ElggSite
	 * This class represents an elgg site.
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 */
	class ElggSite
	{
		
		/**
		 * This contains the site's main properties (id, etc)
		 * @var array
		 */
		private $attributes;
		
		/**
		 * Construct a new site object, optionally from a given id value.
		 *
		 * @param mixed $id
		 */
		function __construct($id = null) 
		{
			$this->attributes = array();
			
			if (!empty($id)) {
				
				$site = null;
				
				if ($id instanceof stdClass) {
					$site = $id;	// This is a db row, so serialise directly

				} else if ($id instanceof ElggSite) {
					//$site = $id;
					$site = new stdClass;
					foreach ($id->attributes as $k => $v)
						$site->$k = $v;
	
				} else if (strpos($id, "http") !== false) {
					$site = get_site_byurl($id);
				} else { 
					$tmp = get_site((int)$id); // This is an integer ID
					$site = new stdClass;
					foreach ($tmp->attributes as $k => $v)
						$site->$k = $v;

				}
				
				if ($site) {
					$objarray = (array) $site;
					
					foreach($objarray as $key => $value) {
						$this->attributes[$key] = $value;
						error_log("$key => $value");
					}
				}
				else
					throw new IOException("Could not create ElggSite object");
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
		 * Return the owner of this site.
		 *
		 * @return mixed
		 */
		function getOwner() { return get_user($this->owner_id); }
		
		/**
		 * Return a list of users using this site.
		 *
		 * @param int $limit
		 * @param int $offset
		 * @return array of ElggUsers
		 */
		function getMembers($limit, $offset) { return get_site_users($this->id, $limit, $offset); }
		
		/**
		 * Get an array of member ElggObjects.
		 *
		 * @param string $type
		 * @param int $limit
		 * @param int $offset
		 */
		function getObjects($type="", $limit = 10, $offset = 0)	{ return get_site_objects($this->id, $type, $limit, $offset); }
		
		/**
		 * Get the collections associated with a site.
		 *
		 * @param string $type
		 * @param int $limit
		 * @param int $offset
		 * @return unknown
		 */
		function getCollections($type="", $limit = 10, $offset = 0) { return get_site_collections($this->id, $type, $limit, $offset); }
		
		/**
		 * Add a user to the site.
		 *
		 * @param int $user_id
		 */
		function addUser($user_id) { return add_site_user($this->id, $user_id); }
		
		/**
		 * Remove a site user.
		 *
		 * @param int $user_id
		 */
		function removeUser($user_id) { return remove_site_user($this->id, $user_id); }
		
		/**
		 * Set the meta data.
		 *
		 * @param string $name
		 * @param string $value
		 * @param int $access_id
		 * @param string $vartype
		 */
		function setMetadata($name, $value, $access_id = 0, $vartype = "") { return set_site_metadata($name, $value, $access_id, $this->id, $vartype); }
		
		/**
		 * Get the metadata for a site.
		 *
		 * @param string $name
		 */
		function getMetadata($name) { return get_site_metadata($name, $this->id); }
		
		/**
		 * Clear the metadata for a given site.
		 *
		 * @param string $name
		 */
		function clearMetadata($name = "") { return remove_site_metadata($this->id, $name); }
			
		/**
		 * Adds an annotation to a site. By default, the type is detected automatically; however, 
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
		 * Get the annotations for a site.
		 *
		 * @param string $name
		 * @param int $limit
		 * @param int $offset
		 */
		function getAnnotations($name, $limit = 50, $offset = 0) { return get_site_annotations($name, $this->id, $limit, $offset); }
		
		/**
		 * Return the annotations for the site.
		 *
		 * @param string $name The type of annotation.
		 */
		function countAnnotations($name) { return count_site_annotations($name, $this->id); }

		/**
		 * Get the average of an integer type annotation.
		 *
		 * @param string $name
		 */
		function getAnnotationsAvg($name) { return get_site_annotations_avg($name, $this->id); }
		
		/**
		 * Get the sum of integer type annotations of a given type.
		 *
		 * @param string $name
		 */
		function getAnnotationsSum($name) { return get_site_annotations_sum($name, $this->id); }
		
		/**
		 * Get the minimum of integer type annotations of given type.
		 *
		 * @param string $name
		 */
		function getAnnotationsMin($name) { return get_site_annotations_min($name, $this->id); }
		
		/**
		 * Get the maximum of integer type annotations of a given type.
		 *
		 * @param string $name
		 */
		function getAnnotationsMax($name) { return get_site_annotations_max($name, $this->id); }
		
		/**
		 * Remove all annotations or all annotations of a given site.
		 *
		 * @param string $name
		 */
		function removeAnnotations($name = "") { return remove_site_annotations($this->id, $name); }
		
		/**
		 * Saves or updates the site to the db depending on whether or not id is specified.
		 */
		function save() 
		{ 
			if ($this->id > 0)
				return update_site($this->id, $this->title, $this->description, $this->url, $this->owner_id, $this->access_id); // ID Specified, update ID
			else
			{ 
				$this->id = create_site($this->title, $this->description, $this->url, $this->owner_id, $this->access_id); // Create a site
				if (!$this->id) throw new IOException("Unable to save new ElggSite"); 
				
				return $this->id;
			}
		}
		
		/**
		 * Delete a given site.
		 */
		function delete() { return delete_site($this->id); }
	}
	
	/**
	 * Convert a database row to a new ElggSite
	 *
	 * @param stdClass $row
	 * @return stdClass or ElggSite
	 */
	function row_to_elggsite($row) 
	{
		if (!($row instanceof stdClass))
			return $row;
			
		return new ElggSite($row);
	}
	
	/**
	 * Get sites based on the provided information.
	 *
	 * @param int $user_id
	 * @param string $type
	 * @param string $metadata_name 
	 * @param string $metadata_value
	 * @param string $order_by
	 * @param int $limit
	 * @param int $offset
	 */
	function get_sites($user_id = 0, $type = "", $metadata_name = "", $metadata_value = "", $order_by = "created desc", $limit = 10, $offset = 0)
	{
		// I'm not sure what this is meant to do, or how its useful.
		
		//return get_objects_from_metadatas(0, 'site', $metadata_name, $metadata_value, $user_id, $order_by, $limit, $offset);
	}
	
	/**
	 * Retrieves details about a site, if the current user is allowed to see it
	 *
	 * @param int $object_id The ID of the object to load
	 * @return object A database representation of the object
	 */
	function get_site($site_id) {
		
		global $CONFIG;
		
		$site_id = (int) $site_id;
		$access = get_access_list();
		
		return row_to_elggsite(get_data_row("select * from {$CONFIG->dbprefix}sites where id=$site_id and (access_id in {$access} or (access_id = 0 and owner_id = {$_SESSION['id']}))"));					
	}
		
	/**
	 * Retrieve details about a site via its URL, if the current user is allowed to see it
	 *
	 * @param string $url
	 * @return object A database representation of the object
	 */
	function get_site_byurl($url)
	{
		global $CONFIG;
		
		$url = sanitise_string(trim($url));	
		$access = get_access_list();
		
		return row_to_elggsite(get_data_row("select * from {$CONFIG->dbprefix}sites where url='$url' and (access_id in {$access} or (access_id = 0 and owner_id = {$_SESSION['id']}))"));			
		
	}
	
	/**
	 * Get a list of users using a given site.
	 *
	 * @param int $site_id
	 * @param int $limit
	 * @param int $offset
	 */
	function get_site_users($site_id, $limit, $offset)
	{
		$site_id = (int)$site_id;
		$limit = (int)$limit;
		$offset = (int)$offset;
		
		return get_data("SELECT u.* from users u, users_sites s where s.site_id=$site_id and u.id = s.user_id","row_to_elgguser");
	}
	
	/**
	 * Get the objects for a given site
	 *
	 * @param int $site_id
	 * @param string $type
	 * @param int $limit
	 * @param int $offset
	 */
	function get_site_objects($site_id, $type = "", $limit = 10, $offset = 0) 
	{
		/*global $CONFIG;
		
		$site_id = (int)$site_id;
		$type = sanitise_string($type);
		$limit = (int)$limit;
		$offset = (int)$offset;
		$owner_id = (int)$owner_id; if ($owner_id==0) $owner_id = $_SESSION['id'];
		
		$access = get_access_list();
		$type = get_data_row("SELECT * from {$CONFIG->dbprefix}object_types where name='$type'");
		
		if (!$type)
			return false;
		
		return get_data("SELECT * from {$CONFIG->dbprefix}objects where site_id=$site_id and type_id={$type->id} and (access_id in {$access} or (access_id = 0 and owner_id = {$_SESSION['id']}))","row_to_elggobject");
		*/
		return get_objects(0,$type, $limit, $offset, $site_id);
	}
	
	/**
	 * Get the collections associated with this site.
	 *
	 * @param int $site_id
	 * @param string $type
	 * @param int $limit
	 * @param int $offset
	 */
	function get_site_collections($site_id, $type = "", $limit = 10, $offset = 0)
	{
		// TODO : Writeme
		throw new NotImplementedException("Writeme!");
	}
	
	/**
	 * Add a site user.
	 *
	 * @param int $site_id
	 * @param int $user_id
	 */
	function add_site_user($site_id, $user_id)
	{
		global $CONFIG;
		
		$site = get_site($site_id);
		$user = get_user($user_id);
		
		if (($site) && ($user))
			return insert_data("INSERT into {$CONFIG->dbprefix}users_sites (site_id, user_id) VALUES ({$site->id},{$user->id})");
		
		return false;
	}
	
	/**
	 * Remove a site user.
	 *
	 * @param int $site_id
	 * @param int $user_id
	 */
	function remove_site_user($site_id, $user_id)
	{
		global $CONFIG;
	
		$site_id = (int)$site_id;
		$user_id = (int)$user_id;
		
		return delete_data("DELETE from {$CONFIG->dbprefix}users_sites where site_id=$site_id and user_id=$user_id");
	}
	
	/**
	 * Set the site metadata.
	 *
	 * @param string $name
	 * @param string $value
	 * @param int $access_id
	 * @param int $site_id
	 * @param string $vartype
	 */
	function set_site_metadata($name, $value, $access_id, $site_id, $vartype = "")
	{
		$name = sanitise_string($name);
		$value = sanitise_string($value);
		$access_id = (int)$access_id;
		$site_id = (int)$site_id;
		$vartype = sanitise_string($vartype);
		$owner_id = $_SESSION['id'];
		
		$id = create_metadata($site_id, 'site', $name, $value, $vartype, $owner_id, $access_id);
		return $id;
	}
	
	/**
	 * Get the site metadata.
	 *
	 * @param string $name
	 * @param int $site_id
	 */
	function get_site_metadata($name, $site_id)
	{
		$name = sanitise_string($name);
		$site_id = (int)$site_id;
		
		return get_metadatas($site_id, 'site');
	}
	
	/**
	 * Remove site metadata
	 *
	 * @param int $site_id
	 * @param string $name
	 */
	function remove_site_metadata($site_id, $name)
	{
		$result = get_metadatas($site_id, 'site', $name);
		
		if ($result)
		{
			foreach ($result as $r)
				delete_metadata($r->id);
		}
		
		return false;
	}
	
	/**
	 * Adds an annotation to a site. By default, the type is detected automatically; 
	 * however, it can also be set. Note that by default, annotations are private.
	 * 
	 * @param string $name
	 * @param string $value
	 * @param int $access_id
	 * @param int $owner_id
	 * @param int $site_id
	 * @param string $vartype
	 */
	function add_site_annotation($name, $value, $access_id, $owner_id, $site_id, $vartype)
	{
		$name = sanitise_string($name);
		$value = sanitise_string($value);
		$access_id = (int)$access_id;
		$owner_id = (int)$owner_id; if ($owner_id==0) $owner_id = $_SESSION['id'];
		$site_id = (int)$site_id;
		$vartype = sanitise_string($vartype);
		
		$id = create_annotation($site_id, 'site', $name, $value, $vartype, $owner_id, $access_id);
		
		return $id;
	}
	
	/**
	 * Get the annotations for a site.
	 *
	 * @param string $name
	 * @param int $site_id
	 * @param int $limit
	 * @param int $offset
	 */
	function get_site_annotations($name, $site_id, $limit, $offset)
	{
		$name = sanitise_string($name);
		$site_id = (int)$site_id;
		$limit = (int)$limit;
		$offset = (int)$offset;
		$owner_id = (int)$owner_id; if ($owner_id==0) $owner_id = $_SESSION['id']; // Consider adding the option to change in param?
		
		return get_annotations($site_id, 'site', "","", $owner_id, "created desc", $limit, $offset);
	}
	
	/**
	 * Count the site annotations for a site of a given type.
	 *
	 * @param string $name
	 * @param int $site_id
	 */
	function count_site_annotations($name, $site_id) { return count_annotations($site_id, 'site', $name); }
	
	/**
	 * Get the average of an integer type annotation.
	 *
	 * @param string $name
	 * @param int $site_id
	 */
	function get_site_annotations_avg($name, $site_id) { return get_annotations_avg($site_id, 'site', $name); }
	
	/**
	 * Get the sum of integer type annotations of a given type.
	 *
	 * @param string $name
	 * @param int $site_id
	 */
	function get_site_annotations_sum($name, $site_id) { return get_annotations_sum($site_id, 'site', $name); }
	
	/**
	 * Get the min of integer type annotations of a given type.
	 *
	 * @param string $name
	 * @param int $site_id
	 */
	function get_site_annotations_min($name, $site_id) { return get_annotations_min($site_id, 'site', $name); }
	
	/**
	 * Get the max of integer type annotations of a given type.
	 *
	 * @param string $name
	 * @param int $site_id
	 */
	function get_site_annotations_max($name, $site_id) { return get_annotations_max($site_id, 'site', $name); }
	
	/**
	 * Remove all site annotations, or site annotations of a given type.
	 *
	 * @param int $site_id
	 * @param string $name
	 */
	function remove_site_annotations($site_id, $name)
	{
		$annotations = get_annotations($site_id, 'site', $name);
		
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
	 * Create a site.
	 *
	 * @param string $title
	 * @param string $description
	 * @param string $url
	 * @param int $owner_id
	 * @param int $access_id
	 */
	function create_site($title, $description, $url, $owner_id = 0, $access_id = 0)
	{
		global $CONFIG;
				
		$title = sanitise_string($title);
		$description = sanitise_string($description);
		$url = sanitise_string($url);
		$owner_id = (int)$owner_id;
		$access_id = (int)$access_id;
		$time = time();
		
		return insert_data("INSERT into {$CONFIG->dbprefix}sites (name, description, url, owner_id, created, last_updated, access_id) VALUES ('$title','$description','$url',$owner_id,'$time','$time', $access_id)");
	}
	
	/**
	 * Saves or updates the site to the db depending on whether or not id is specified
	 *
	 * @param int $id
	 * @param string $title
	 * @param string $description
	 * @param string $url
	 * @param int $owner_id
	 * @param int $access_id
	 */
	function update_site($id, $title, $description, $url, $owner_id, $access_id)
	{
		global $CONFIG;

		$id = (int)$id;
		$title = sanitise_string($title);
		$description = sanitise_string($description);
		$url = sanitise_string($url);
		$owner_id = (int)$owner_id;
		$access_id = (int)$access_id;
		$time = time();
		
		$access = get_access_list();
		
		return update_data("UPDATE {$CONFIG->dbprefix}sites set name='$title', description='$description', url='$url', last_updated='$time', owner_id=$owner_id, access_id=$access_id WHERE id=$id and owner_id = {$_SESSION['id']}");
	}
	
	/**
	 * Delete a given site.
	 *
	 * @param int $site_id
	 */
	function delete_site($site_id)
	{
		global $CONFIG;
		
		$site_id = (int)$site_id;
		
		return delete_data("DELETE from {$CONFIG->dbprefix}sites where id=$site_id");
	}
	
	/**
	 * Initialise site handling
	 *
	 * Called at the beginning of system running, to set the ID of the current site.
	 * This is 0 by default, but plugins may alter this behaviour by attaching functions
	 * to the sites init event and changing $CONFIG->site_id.
	 * 
	 * @uses $CONFIG
	 * @param string $event Event API required parameter
	 * @param string $object_type Event API required parameter
	 * @param null $object Event API required parameter
	 * @return true
	 */
		function sites_init($event, $object_type, $object) {
			global $CONFIG;
			
			$CONFIG->site_id = 1;
			
			trigger_event('init','sites');
			
			if ($site = get_data_row("select * from {$CONFIG->dbprefix}sites where id = 1")) {
				if (!empty($site->name))
					$CONFIG->sitename = $site->name;
				if (!empty($site->domain))
					$CONFIG->wwwroot = $site->domain;
			}
			
			return true;
		}
		
	// Register event handlers

		register_event_handler('init','system','sites_init',0);

?>