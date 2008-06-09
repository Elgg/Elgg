<?php
	/**
	 * Elgg entities.
	 * Functions to manage all elgg entities (sites, collections, objects and users).
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * ElggEntity The elgg entity superclass
	 * This class holds methods for accessing the main entities table.
	 * 
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @package Elgg
	 * @subpackage Core
	 */
	abstract class ElggEntity implements 
		Exportable, // Allow export of data
		Importable, // Allow import of data
		Loggable,	// Can events related to this object class be logged
		Iterator,	// Override foreach behaviour
		ArrayAccess // Override for array access
	{
		/** 
		 * The main attributes of an entity.
		 * Blank entries for all database fields should be created by the constructor.
		 * Subclasses should add to this in their constructors.
		 * Any field not appearing in this will be viewed as a 
		 */
		protected $attributes;
		protected $temp_metadata;
				
		/**
		 * Initialise the attributes array. 
		 * This is vital to distinguish between metadata and base parameters.
		 * 
		 * Place your base parameters here.
		 * 
		 * @return void
		 */
		protected function initialise_attributes()
		{
			// Create attributes array if not already created
			if (!is_array($this->attributes)) $this->attributes = array();
			if (!is_array($this->temp_metadata)) $this->temp_metadata = array();
			
			$this->attributes['guid'] = "";
			$this->attributes['type'] = "";
			$this->attributes['subtype'] = "";
			$this->attributes['owner_guid'] = $_SESSION['guid'];
			$this->attributes['access_id'] = 0;
			$this->attributes['time_created'] = "";
			$this->attributes['time_updated'] = "";
		}
				
		/**
		 * Return the value of a given key.
		 * If $name is a key field (as defined in $this->attributes) that value is returned, otherwise it will
		 * then look to see if the value is in this object's metadata.
		 * 
		 * Q: Why are we not using __get overload here?
		 * A: Because overload operators cause problems during subclassing, so we put the code here and
		 * create overloads in subclasses. 
		 * 
		 * @param string $name
		 * @return mixed Returns the value of a given value, or null.
		 */
		public function get($name)
		{
			// See if its in our base attribute
			if (isset($this->attributes[$name])) {
				return $this->attributes[$name];
			}
			
			// No, so see if its in the meta data for this entity
			if ((int) ($this->guid) > 0) {
				$meta = $this->getMetaData($name);
				if ($meta)
					return $meta;
			} else {
				if (isset($this->temp_metadata[$name]))
					return $this->temp_metadata[$name];
			}
			
			// Can't find it, so return null
			return null;
		}

		/**
		 * Set the value of a given key, replacing it if necessary.
		 * If $name is a base attribute (as defined in $this->attributes) that value is set, otherwise it will
		 * set the appropriate item of metadata.
		 * 
		 * Note: It is important that your class populates $this->attributes with keys for all base attributes, anything
		 * not in their gets set as METADATA.
		 * 
		 * Q: Why are we not using __set overload here?
		 * A: Because overload operators cause problems during subclassing, so we put the code here and
		 * create overloads in subclasses.
		 * 
		 * @param string $name
		 * @param mixed $value  
		 */
		public function set($name, $value)
		{
			if (array_key_exists($name, $this->attributes))
			{
				// Check that we're not trying to change the guid! 
				if ((array_key_exists('guid', $this->attributes)) && ($name=='guid'))
					return false;
					
				$this->attributes[$name] = $value;
			}
			else if ((int) $this->guid > 0) {
				return $this->setMetaData($name, $value);
			} else {
				$this->temp_metadata[$name] = $value;
			}
			
			return true;
		}
			
		/**
		 * Get a given piece of metadata.
		 * 
		 * @param string $name
		 */
		public function getMetaData($name)
		{
			$md = get_metadata_byname($this->getGUID(), $name);

			if ($md && !is_array($md)) {
				return $md->value;
			} else if ($md && is_array($md)) {
				return metadata_array_to_values($md);
			}
				
			return null;
		}
		
		/**
		 * Set a piece of metadata.
		 * 
		 * @param string $name
		 * @param mixed $value
		 * @param string $value_type
		 * @param bool $multiple
		 * @return bool
		 */
		public function setMetaData($name, $value, $value_type = "", $multiple = false)
		{
			if (is_array($value))
			{
				remove_metadata($this->getGUID(), $name);
				$multiple = true;
				foreach ($value as $v) {
					if (!create_metadata($this->getGUID(), $name, $v, $value_type, $this->getOwner(), $this->getAccessID(), $multiple)) return false; 
				}
					
				return true;
			}
			else
				return create_metadata($this->getGUID(), $name, $value, $value_type, $this->getOwner(), $this->getAccessID());
		}
		
		/**
		 * Clear metadata.
		 */
		public function clearMetaData($name = "")
		{
			if (empty($name)) {
				return clear_metadata($this->getGUID());
			} else {
				return remove_metadata($this->getGUID(),$name);
			}
		}
		
		/**
		 * Remove all entities associated with this entity
		 *
		 * @return true
		 */
		public function clearRelationships() {
			remove_entity_relationships($this->getGUID());
			remove_entity_relationships($this->getGUID(),"",true);
			return true;
		}
		
		/**
		 * Adds an annotation to an entity. By default, the type is detected automatically; however, 
		 * it can also be set. Note that by default, annotations are private.
		 * 
		 * @param string $name
		 * @param mixed $value
		 * @param int $access_id
		 * @param int $owner_id
		 * @param string $vartype
		 */
		function annotate($name, $value, $access_id = 0, $owner_id = 0, $vartype = "") 
		{ 
			return create_annotation($this->getGUID(), $name, $value, $vartype, $owner_id, $access_id);
		}
		
		/**
		 * Get the annotations for an entity.
		 *
		 * @param string $name
		 * @param int $limit
		 * @param int $offset
		 */
		function getAnnotations($name, $limit = 50, $offset = 0) 
		{ 
			return get_annotations($this->getGUID(), "", "", $name, "", 0, $limit, $offset);
		}
		
		/**
		 * Remove all annotations or all annotations for this entity.
		 *
		 * @param string $name
		 */
		function clearAnnotations($name = "")
		{
			return clear_annotations($this->getGUID(), $name);
		}
		
		/**
		 * Return the annotations for the entity.
		 *
		 * @param string $name The type of annotation.
		 */
		function countAnnotations($name) 
		{ 
			return count_annotations($this->getGUID(), "","",$name);
		}

		/**
		 * Get the average of an integer type annotation.
		 *
		 * @param string $name
		 */
		function getAnnotationsAvg($name) 
		{
			return get_annotations_avg($this->getGUID(), "","",$name);
		}
		
		/**
		 * Get the sum of integer type annotations of a given name.
		 *
		 * @param string $name
		 */
		function getAnnotationsSum($name) 
		{
			return get_annotations_sum($this->getGUID(), "","",$name);
		}
		
		/**
		 * Get the minimum of integer type annotations of given name.
		 *
		 * @param string $name
		 */
		function getAnnotationsMin($name)
		{
			return get_annotations_min($this->getGUID(), "","",$name);
		}
		
		/**
		 * Get the maximum of integer type annotations of a given name.
		 *
		 * @param string $name
		 */
		function getAnnotationsMax($name)
		{
			return get_annotations_max($this->getGUID(), "","",$name);
		}
		
		/**
		 * Gets an array of entities from a specific relationship type
		 *
		 * @param string $relationship Relationship type (eg "friends")
		 * @param true|false $inverse Is this an inverse relationship?
		 * @param int $limit Number of elements to return
		 * @param int $offset Indexing offset
		 * @return array|false An array of entities or false on failure
		 */		
		function getEntitiesFromRelationship($relationship, $inverse = false, $limit = 50, $offset = 0) {
			return get_entities_from_relationship($relationship,$this->getGUID(),$inverse,"","","","time_created desc",$limit,$offset);			
		}
		
		/**
		 * Gets the number of of entities from a specific relationship type
		 *
		 * @param string $relationship Relationship type (eg "friends")
		 * @return int|false The number of entities or false on failure
		 */		
		function countEntitiesFromRelationship($relationship) {
			return get_entities_from_relationship($relationship,$this->getGUID(),false,"","","","time_created desc",null,null,true);			
		}
		
		/**
		 * Determines whether or not the specified user (by default the current one) can edit the entity 
		 *
		 * @param int $user_guid The user GUID, optionally (defaults to the currently logged in user)
		 * @return true|false
		 */
		function canEdit($user_guid = 0) {
			return can_edit_entity($this->getGUID(),$user_guid);
		}
		
		/**
		 * Enter description here...
		 *
		 * @return unknown
		 * @todo document me
		 */
		public function getAccessID() { return $this->get('access_id'); }
		
		/**
		 * Enter description here...
		 *
		 * @return unknown
		 * @todo document me
		 */
		public function getGUID() { return $this->get('guid'); }
		
		/**
		 * Enter description here...
		 *
		 * @return unknown
		 * @todo document me
		 */
		public function getOwner() { return $this->get('owner_guid'); }
		
		/**
		 * Returns the actual entity of the user who owns this entity, if any
		 *
		 * @return ElggEntity The owning user
		 */
		public function getOwnerEntity() { return get_entity($this->get('owner_guid')); }
		
		/**
		 * Enter description here...
		 *
		 * @return unknown
		 * @todo document me
		 */
		public function getType() { return $this->get('type'); }
		
		/**
		 * Enter description here...
		 *
		 * @return unknown
		 * @todo document me
		 */
		public function getSubtype() { return get_subtype_from_id($this->get('subtype')); }
		
		/**
		 * Enter description here...
		 *
		 * @return unknown
		 * @todo document me
		 */
		public function getTimeCreated() { return $this->get('time_created'); }
		
		/**
		 * Enter description here...
		 *
		 * @return unknown
		 * @todo document me
		 */
		public function getTimeUpdated() { return $this->get('time_updated'); }
		
		/**
		 * Gets the display URL for this entity
		 *
		 * @return string The URL
		 */
		public function getURL() { return get_entity_url($this->getGUID()); }
		
		/**
		 * Save generic attributes to the entities table.
		 */
		public function save()
		{
			if ($this->guid != "" && $this->guid > 0)
			{ 
				return update_entity(
					$this->get('guid'),
					$this->get('owner_guid'),
					$this->get('access_id')
				);
			}
			else
			{ 
				$this->attributes['guid'] = create_entity($this->attributes['type'], $this->attributes['subtype'], $this->attributes['owner_guid'], $this->attributes['access_id']); // Create a new entity (nb: using attribute array directly 'cos set function does something special!)
				if (!$this->attributes['guid']) throw new IOException(elgg_echo('IOException:BaseEntitySaveFailed')); 
				
				// Save any unsaved metadata
				if (sizeof($this->temp_metadata) > 0) {
					foreach($this->temp_metadata as $name => $value) {
						$this->$name = $value;
						unset($this->temp_metadata[$name]);
					}
				}
				
				return $this->attributes['guid'];
			}
		}
		
		/**
		 * Load the basic entity information and populate base attributes array.
		 * 
		 * @param int $guid 
		 */
		protected function load($guid)
		{		
			$row = get_entity_as_row($guid); 
		
			if ($row)
			{
				// Create the array if necessary - all subclasses should test before creating
				if (!is_array($this->attributes)) $this->attributes = array();
				
				// Now put these into the attributes array as core values
				$objarray = (array) $row;
				foreach($objarray as $key => $value) 
					$this->attributes[$key] = $value;
				
				return true;
			}
			
			return false;
		}
		
		/**
		 * Delete this entity.
		 */
		public function delete() 
		{ 
			$res = delete_entity($this->get('guid'));
			return $res;
		}

		// EXPORTABLE INTERFACE ////////////////////////////////////////////////////////////
		
		/**
		 * Export this class into a stdClass containing all necessary fields.
		 * Override if you wish to return more information than can be found in $this->attributes (shouldn't happen) 
		 * 
		 * @return stdClass
		 */
		public function export() 
		{ 
			$tmp = array();
			
			// Generate uuid
			$uuid = guid_to_uuid($this->getGUID());
			
			// Create entity 
			$odd = new ODDEntity(
				$uuid,
				$this->attributes['type'], 
				get_subtype_from_id($this->attributes['subtype'])
			);
			
			$tmp[] = $odd;
			
			// Now add its attributes
			foreach ($this->attributes as $k => $v)
			{
				$meta = NULL;
				
				switch ($k)
				{
					case 'guid' : 			// Dont use guid
					case 'subtype' :		// The subtype
					case 'type' : 			// Don't use type
					case 'access_id' : 		// Don't use access - if can export then its public for you, then importer decides what access to give this object.
					case 'time_updated' : 	// Don't use date in export
					break;
					
					case 'time_created' :	// Created = published
						$odd->setAttribute('published', date("r", $v));
					break;

					case 'owner_guid' :			// Convert owner guid to uuid, this will be stored in metadata
						 $k = 'owner_uuid';
						 $v = guid_to_uuid($v);
						 $meta = new ODDMetadata($uuid . "attr/$k/", $uuid, $k, $v);
					break; 	
					
					default : 
						$meta = new ODDMetadata($uuid . "attr/$k/", $uuid, $k, $v);
				}
				
				// set the time of any metadata created
				if ($meta)
				{
					$meta->setAttribute('published', date("r",$this->time_created));
					$tmp[] = $meta;
				}
			}
			
			
			return $tmp;
		}
		
		// IMPORTABLE INTERFACE ////////////////////////////////////////////////////////////
		
		/**
		 * Import data from an parsed xml data array.
		 * 
		 * @param array $data
		 * @param int $version 
		 */
		public function import(ODD $data)
		{
			if (!($data instanceof ODDEntity))
				throw new InvalidParameterException(elgg_echo('InvalidParameterException:UnexpectedODDClass')); 
			
			// Set type and subtype
			$this->attributes['type'] = $data->getAttribute('class');
			$this->attributes['subtype'] = $data->getAttribute('subclass');
			
			// Set owner
			$this->attributes['owner_guid'] = $_SESSION['id']; // Import as belonging to importer.
			
			// Set time
			$this->attributes['time_created'] = strtotime($data->getAttribute('published'));
			$this->attributes['time_updated'] = time();
			
			return true;
		}
		
		// SYSTEM LOG INTERFACE ////////////////////////////////////////////////////////////
		
		/**
		 * Return an identification for the object for storage in the system log. 
		 * This id must be an integer.
		 * 
		 * @return int 
		 */
		public function getSystemLogID() { return $this->getGUID();	}
		
		/**
		 * Return the class name of the object.
		 */
		public function getClassName() { return get_class($this); }
		
		/**
		 * For a given ID, return the object associated with it.
		 * This is used by the river functionality primarily.
		 * This is useful for checking access permissions etc on objects.
		 */
		public function getObjectFromID($id) { return get_entity($id); }
		
		/**
		 * Return the GUID of the owner of this object.
		 */
		public function getObjectOwnerGUID() { return $this->owner_guid; }

		// ITERATOR INTERFACE //////////////////////////////////////////////////////////////
		/*
		 * This lets an entity's attributes be displayed using foreach as a normal array.
		 * Example: http://www.sitepoint.com/print/php5-standard-library
		 */
		
		private $valid = FALSE; 
		
   		function rewind() 
   		{ 
   			$this->valid = (FALSE !== reset($this->attributes));  
   		}
   
   		function current() 
   		{ 
   			return current($this->attributes); 
   		}
		
   		function key() 
   		{ 
   			return key($this->attributes); 
   		}
		
   		function next() 
   		{
   			$this->valid = (FALSE !== next($this->attributes));  
   		}
   		
   		function valid() 
   		{ 
   			return $this->valid;  
   		}
	
   		// ARRAY ACCESS INTERFACE //////////////////////////////////////////////////////////
		/*
		 * This lets an entity's attributes be accessed like an associative array.
		 * Example: http://www.sitepoint.com/print/php5-standard-library
		 */

		function offsetSet($key, $value)
		{
   			if ( array_key_exists($key, $this->attributes) ) {
     			$this->attributes[$key] = $value;
   			}
 		} 
 		
 		function offsetGet($key) 
 		{
   			if ( array_key_exists($key, $this->attributes) ) {
     			return $this->attributes[$key];
   			}
 		} 
 		
 		function offsetUnset($key) 
 		{
   			if ( array_key_exists($key, $this->attributes) ) {
     			$this->attributes[$key] = ""; // Full unsetting is dangerious for our objects
   			}
 		} 
 		
 		function offsetExists($offset) 
 		{
   			return array_key_exists($offset, $this->attributes);
 		} 
	}

	/**
	 * Return the integer ID for a given subtype, or false.
	 * 
	 * TODO: Move to a nicer place?
	 * 
	 * @param string $type
	 * @param string $subtype
	 */
	function get_subtype_id($type, $subtype)
	{
		global $CONFIG;
		
		$type = sanitise_string($type);
		$subtype = sanitise_string($subtype);
	
		if ($subtype=="") return $subtype;
		
		$result = get_data_row("SELECT * from {$CONFIG->dbprefix}entity_subtypes where type='$type' and subtype='$subtype'");

		if ($result)
			return $result->id;
		
		return 0;
	}
	
	/**
	 * For a given subtype ID, return its identifier text.
	 *  
	 * TODO: Move to a nicer place?
	 * 
	 * @param int $subtype_id
	 */
	function get_subtype_from_id($subtype_id)
	{
		global $CONFIG;
		
		$subtype_id = (int)$subtype_id;
		
		$result = get_data_row("SELECT * from {$CONFIG->dbprefix}entity_subtypes where id=$subtype_id");
		if ($result)
			return $result->subtype;
		
		return false;
	}
	
	/**
	 * This function tests to see if a subtype has a registered class handler.
	 * 
	 * @param string $type The type
	 * @param string $subtype The subtype
	 * @return a class name or null
	 */
	function get_subtype_class($type, $subtype)
	{
		global $CONFIG;
		
		$type = sanitise_string($type);
		$subtype = sanitise_string($subtype);
		
		$result = get_data_row("SELECT * from {$CONFIG->dbprefix}entity_subtypes where type='$type' and subtype='$subtype'");
		if ($result)
			return $result->class;
		
		return NULL;
	}
	
	/**
	 * This function tests to see if a subtype has a registered class handler by its id.
	 * 
	 * @param int $subtype_id The subtype
	 * @return a class name or null
	 */
	function get_subtype_class_from_id($subtype_id)
	{
		global $CONFIG;
		
		$subtype = (int)$subtype_id;
		
		$result = get_data_row("SELECT * from {$CONFIG->dbprefix}entity_subtypes where id=$subtype_id");
		if ($result)
			return $result->class;
		
		return NULL;
	}
	
	/**
	 * This function will register a new subtype, returning its ID as required.
	 * 
	 * @param string $type The type you're subtyping
	 * @param string $subtype The subtype label
	 * @param string $class Optional class handler (if you don't want it handled by the generic elgg handler for the type)
	 */
	function add_subtype($type, $subtype, $class = "")
	{
		global $CONFIG;
		$type = sanitise_string($type);
		$subtype = sanitise_string($subtype);
		$class = sanitise_string($class);
	
		// Short circuit if no subtype is given
		if ($subtype == "")
			return 0;

		$id = get_subtype_id($type, $subtype);
	
		if ($id==0)
			return insert_data("insert into {$CONFIG->dbprefix}entity_subtypes (type, subtype, class) values ('$type','$subtype','$class')");
		
		return $id;
	}
	
	/**
	 * Update an existing entity.
	 *
	 * @param int $guid
	 * @param int $owner_guid
	 * @param int $access_id
	 */
	function update_entity($guid, $owner_guid,  $access_id)
	{
		global $CONFIG;
		
		$guid = (int)$guid;
		$owner_guid = (int)$owner_guid;
		$access_id = (int)$access_id;
		$time = time();
		
		$entity = get_entity($guid);
		
		if ($entity->canEdit()) {
			
			if (trigger_event('update',$entity->type,$entity)) {
				$ret = update_data("UPDATE {$CONFIG->dbprefix}entities set owner_guid='$owner_guid', access_id='$access_id', time_updated='$time' WHERE guid=$guid");
				
				// Handle cases where there was no error BUT no rows were updated!
				if ($ret===false)
					return false;

				return true;
			}
			
		}
	}
	
	/**
	 * Create a new entity of a given type.
	 * 
	 * @param string $type The type of the entity (site, user, object).
	 * @param string $subtype The subtype of the entity.
	 * @param int $owner_guid The GUID of the object's owner.
	 * @param int $access_id The access control group to create the entity with.
	 * @param int $site_guid The site to add this entity to. Leave as 0 (default) for the current site.
	 * @return mixed The new entity's GUID, or false on failure
	 */
	function create_entity($type, $subtype, $owner_guid, $access_id, $site_guid = 0)
	{
		global $CONFIG;
	
		$type = sanitise_string($type);
		$subtype = add_subtype($type, $subtype);
		$owner_guid = (int)$owner_guid; 
		$access_id = (int)$access_id;
		$time = time();
		if ($site_guid == 0)
			$site_guid = $CONFIG->site_guid;
		$site_guid = (int) $site_guid;
					
		if ($type=="") throw new InvalidParameterException(elgg_echo('InvalidParameterException:EntityTypeNotSet'));

		return insert_data("INSERT into {$CONFIG->dbprefix}entities (type, subtype, owner_guid, site_guid, access_id, time_created, time_updated) values ('$type',$subtype, $owner_guid, $site_guid, $access_id, $time, $time)"); 
	}
	
	/**
	 * Retrieve the entity details for a specific GUID, returning it as a stdClass db row.
	 * 
	 * You will only get an object if a) it exists, b) you have access to it.
	 *
	 * @param int $guid The GUID of the object to extract
	 */
	function get_entity_as_row($guid)
	{
		global $CONFIG;
		
		$guid = (int) $guid;
		
		$access = get_access_sql_suffix();
		
		return get_data_row("SELECT * from {$CONFIG->dbprefix}entities where guid=$guid and $access");
	}
	
	/**
	 * Create an Elgg* object from a given entity row. 
	 */
	function entity_row_to_elggstar($row)
	{
		if (!($row instanceof stdClass))
			return $row;
		// See if there are any registered subtype handler classes for this type and subtype

		$classname = get_subtype_class_from_id($row->subtype);
		if ($classname!="")
		{
			$tmp = new $classname($row);
			
			if (!($tmp instanceof ElggEntity))
				throw new ClassException(sprintf(elgg_echo('ClassException:ClassnameNotClass'), $classname, get_class()));

			return $tmp;
		}
		else
		{
			switch ($row->type)
			{
				case 'object' : 
					return new ElggObject($row);
				case 'user' : 
					return new ElggUser($row);
				case 'collection' : 
					return new ElggCollection($row); 
				case 'site' : 
					return new ElggSite($row); 
				default: throw new InstallationException(sprintf(elgg_echo('InstallationException:TypeNotSupported'), $row->type));
			}
		}
		
		return false;
	}
	
	/**
	 * Return the entity for a given guid as the correct object.
	 * @param int $guid The GUID of the entity
	 * @return a child of ElggEntity appropriate for the type.
	 */
	function get_entity($guid)
	{
		return entity_row_to_elggstar(get_entity_as_row($guid));
	}
	
	/**
	 * Return entities matching a given query, or the number thereof
	 * 
	 * @param string $type The type of entity (eg "user", "object" etc)
	 * @param string $subtype The arbitrary subtype of the entity
	 * @param int $owner_guid The GUID of the owning user
	 * @param string $order_by The field to order by; by default, time_created desc
	 * @param int $limit The number of entities to return; 10 by default
	 * @param int $offset The indexing offset, 0 by default
	 * @param boolean $count Set to true to get a count rather than the entities themselves (limits and offsets don't apply in this context). Defaults to false.
	 * @param int $site_guid The site to get entities for. Leave as 0 (default) for the current site; -1 for all sites. 
	 */
	function get_entities($type = "", $subtype = "", $owner_guid = 0, $order_by = "time_created desc", $limit = 10, $offset = 0, $count = false, $site_guid = 0)
	{
		global $CONFIG;
		
		if ($subtype === false || $subtype === null || $subtype === 0)
			return false;
		
		$type = sanitise_string($type);
		$subtype = get_subtype_id($type, $subtype);
		
		$order_by = sanitise_string($order_by);
		$limit = (int)$limit;
		$offset = (int)$offset;
		$site_guid = (int) $site_guid;
		if ($site_guid == 0)
			$site_guid = $CONFIG->site_guid;
				
		$where = array();
		
		if ($type != "")
			$where[] = "type='$type'";
		if ($subtype!=="")
			$where[] = "subtype=$subtype";
		if ($owner_guid != "") {
			if (!is_array($owner_guid)) {
				$owner_guid = (int) $owner_guid;
				$where[] = "owner_guid = '$owner_guid'";
			} else if (sizeof($owner_guid) > 0) {
				// Cast every element to the owner_guid array to int
				$owner_guid = array_map("sanitise_int", $owner_guid);
				$owner_guid = implode(",",$owner_guid);
				$where[] = "owner_guid in ({$owner_guid})";
			}
		}
		if ($site_guid > 0)
			$where[] = "site_guid = {$site_guid}";

		if (!$count) {
			$query = "SELECT * from {$CONFIG->dbprefix}entities where ";
		} else {
			$query = "select count(guid) as total from {$CONFIG->dbprefix}entities where ";
		}
		foreach ($where as $w)
			$query .= " $w and ";
		$query .= get_access_sql_suffix(); // Add access controls
		if (!$count) {
			$query .= " order by $order_by";
			if ($limit) $query .= " limit $offset, $limit"; // Add order and limit

			$dt = get_data($query, "entity_row_to_elggstar");
			return $dt;
		} else {
			$total = get_data_row($query);
			return $total->total;
		}
	}
	
	/**
	 * Delete a given entity.
	 * 
	 * @param int $guid
	 */
	function delete_entity($guid)
	{
		global $CONFIG;
		
		// TODO Make sure this deletes all metadata/annotations/relationships/etc!!
		
		$guid = (int)$guid;
		if ($entity = get_entity($guid)) {
			if (trigger_event('delete',$entity->type,$entity)) {
				if ($entity->canEdit()) {
					$entity->clearMetadata();
					$entity->clearAnnotations();
					$entity->clearRelationships();
					$res = delete_data("DELETE from {$CONFIG->dbprefix}entities where guid={$guid}");
					return $res;
				} 
			}
		}
		return false;
		
	}
	
	/**
	 * Delete multiple entities that match a given query.
	 * This function itterates through and calls delete_entity on each one, this is somewhat inefficient but lets 
	 * the 'delete' even be called for each entity.
	 * 
	 * @param string $type The type of entity (eg "user", "object" etc)
	 * @param string $subtype The arbitrary subtype of the entity
	 * @param int $owner_guid The GUID of the owning user
	 */
	function delete_entities($type = "", $subtype = "", $owner_guid = 0)
	{
		$entities = get_entities($type, $subtype, $owner_guid, "time_created desc", 0);
		
		foreach ($entities as $entity)
			delete_entity($entity->guid);
		
		return true;
	}

	/**
	 * Handler called by trigger_plugin_hook on the "export" event.
	 */
	function export_entity_plugin_hook($hook, $entity_type, $returnvalue, $params)
	{
		// Sanity check values
		if ((!is_array($params)) && (!isset($params['guid'])))
			throw new InvalidParameterException(elgg_echo('InvalidParameterException:GUIDNotForExport'));
			
		if (!is_array($returnvalue))
			throw new InvalidParameterException(elgg_echo('InvalidParameterException:NonArrayReturnValue'));
			
		$guid = (int)$params['guid'];
		
		// Get the entity
		$entity = get_entity($guid);
		if (!($entity instanceof ElggEntity))
			throw new InvalidClassException(sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $guid, get_class()));
		
		$export = $entity->export();
		
		if (is_array($export))
			foreach ($export as $e)
				$returnvalue[] = $e;
		else
			$returnvalue[] = $export;
		
		return $returnvalue;
	}
	
	/**
	 * Import an entity.
	 * This function checks the passed XML doc (as array) to see if it is a user, if so it constructs a new 
	 * elgg user and returns "true" to inform the importer that it's been handled.
	 */
	function import_entity_plugin_hook($hook, $entity_type, $returnvalue, $params)
	{
		$element = $params['element'];
		
		$tmp = NULL;
		
		if ($element instanceof ODDEntity)
		{
			$class = $element->getAttribute('class');
			$subclass = $element->getAttribute('subclass');
			
			// See if we already have imported this uuid
			$tmp = get_entity_from_uuid($element->getAttribute('uuid'));
			
			if (!$tmp)
			{
				// Construct new class with owner from session
				$classname = get_subtype_class($class, $subclass);
				if ($classname!="")
				{
					$tmp = new $classname();
					
					if (!($tmp instanceof ElggEntity))
						throw new ClassException(sprintf(elgg_echo('ClassException:ClassnameNotClass', $classname, get_class())));
						
				}
				else
				{
					switch ($class)
					{
						case 'object' : $tmp = new ElggObject($row); break;
						case 'user' : $tmp = new ElggUser($row); break;
						case 'collection' : $tmp = new ElggCollection($row); break; 
						case 'site' : $tmp = new ElggSite($row); break; 
						default: throw new InstallationException(sprintf(elgg_echo('InstallationException:TypeNotSupported'), $class));
					}
				}
			}
			
			if ($tmp)
			{
				if (!$tmp->import($element))
					throw new ImportException(sprintf(elgg_echo('ImportException:ImportFailed'), $element->getAttribute('uuid')));
					
				if (!$tmp->save()) // Make sure its saved
					throw new ImportException(sprintf(elgg_echo('ImportException:ProblemSaving'), $element->getAttribute('uuid')));
	
				// Belts and braces
				if (!$tmp->guid)
					throw new ImportException(elgg_echo('ImportException:NoGUID')); 
				
				add_uuid_to_guid($tmp->guid, $element->getAttribute('uuid')); // We have saved, so now tag
				
				return $tmp;
			}
		}
	}
	
	/**
	 * Determines whether or not the specified user can edit the specified entity.
	 * 
	 * This is extendible by registering a plugin hook taking in the parameters 'entity' and 'user',
	 * which are the entity and user entities respectively
	 * 
	 * @see register_plugin_hook 
	 *
	 * @param int $entity_guid The GUID of the entity
	 * @param int $user_guid The GUID of the user
	 * @return true|false Whether the specified user can edit the specified entity.
	 */
	function can_edit_entity($entity_guid, $user_guid = 0) {
		
		if ($user_guid == 0) {
			if (isset($_SESSION['user'])) {
				$user = $_SESSION['user'];
			} else {
				$user = null;
			}
		} else {
			$user = get_entity($user_guid);
		}

		if (($entity = get_entity($entity_guid)) && (!is_null($user))) {

			if ($entity->getOwner() == $user->getGUID()) return true;
			if ($entity->type == "user" && $entity->getGUID() == $user->getGUID()) return true;
			
			return trigger_plugin_hook('permissions_check',$entity->type,array('entity' => $entity, 'user' => $user),false);
		
		} else {
			
			return false;
			
		}
		
	}
	
	/**
	 * Gets the URL for an entity, given a particular GUID
	 *
	 * @param int $entity_guid The GUID of the entity
	 * @return string The URL of the entity
	 */
	function get_entity_url($entity_guid) {
		
		global $CONFIG;
		if ($entity = get_entity($entity_guid)) {

			$url = "";
			
			if (isset($CONFIG->entity_url_handler[$entity->getType()][$entity->getSubType()])) {
				$function =  $CONFIG->entity_url_handler[$entity->getType()][$entity->getSubType()];
				if (is_callable($function)) {
					$url = $function($entity);
				}
			}
			if (isset($CONFIG->entity_url_handler[$entity->getType()]['all'])) {
				$function =  $CONFIG->entity_url_handler[$entity->getType()]['all'];
				if (is_callable($function)) {
					$url = $function($entity);
				}
			}
			if (isset($CONFIG->entity_url_handler['all']['all'])) {
				$function =  $CONFIG->entity_url_handler['all']['all'];
				if (is_callable($function)) {
					$url = $function($entity);
				}
			}

			if ($url == "") {
				$url = $CONFIG->url . "pg/view/" . $entity_guid;
			}
			return $url;
			
		}
		return false;
		
	}
	
	/**
	 * Sets the URL handler for a particular entity type and subtype
	 *
	 * @param string $function_name The function to register
	 * @param string $entity_type The entity type
	 * @param string $entity_subtype The entity subtype
	 * @return true|false Depending on success
	 */
	function register_entity_url_handler($function_name, $entity_type = "all", $entity_subtype = "all") {
		global $CONFIG;
		
		if (!is_callable($function_name)) return false;
		
		if (!isset($CONFIG->entity_url_handler)) {
			$CONFIG->entity_url_handler = array();
		}
		if (!isset($CONFIG->entity_url_handler[$entity_type])) {
			$CONFIG->entity_url_handler[$entity_type] = array();
		}
		$CONFIG->entity_url_handler[$entity_type][$entity_subtype] = $function_name;
		
		return true;
		
	}
	
	/**
	 * Page handler for generic entities view system
	 *
	 * @param array $page Page elements from pain page handler
	 */
	function entities_page_handler($page) {
		if (isset($page[0])) {
			global $CONFIG;
			set_input('guid',$page[0]);
			@include($CONFIG->path . "entities/index.php");
		}
	}
	
	/**
	 * Entities init function; establishes the page handler
	 *
	 */
	function entities_init() {
		register_page_handler('view','entities_page_handler');
	}
	
	/** Register the import hook */
	register_plugin_hook("import", "all", "import_entity_plugin_hook", 0);
	
	/** Register the hook, ensuring entities are serialised first */
	register_plugin_hook("export", "all", "export_entity_plugin_hook", 0);
	
	/** Register init system event **/
	register_event_handler('init','system','entities_init');
	
?>