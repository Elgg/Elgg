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
	 * @class ElggEntity The elgg entity superclass
	 * This class holds methods for accessing the main entities table.
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 */
	abstract class ElggEntity
	{
		/** 
		 * The main attributes of an entity.
		 * Blank entries for all database fields should be created by the constructor.
		 * Subclasses should add to this in their constructors.
		 * Any field not appearing in this will be viewed as a 
		 */
		protected $attributes;
				
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
			$meta = getMetaData($name);
			if ($meta)
				return $meta;
			
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
				if ((array_key_exists('guid')) && ($name=='guid'))
					return false;
					
				$this->attributes[$name] = $value;
			}
			else
				return setMetaData($name, $value);
			
			return true;
		}
			
		/**
		 * Get a given piece of metadata.
		 * 
		 * @param string $name
		 */
		public function getMetaData($name)
		{
			//TODO: Writeme
		}
		
		/**
		 * Set a piece of metadata.
		 * 
		 * @param string $name
		 * @param string $value
		 * @return bool
		 */
		public function setMetaData($name, $value)
		{
			// TODO: WRITEME
		}
		
		public function clearMetaData()
		{
			// TODO: WRITEME
		}
		
		/**
		 * Adds an annotation to an entity. By default, the type is detected automatically; however, 
		 * it can also be set. Note that by default, annotations are private.
		 * 
		 * @param string $name
		 * @param string $value
		 * @param int $access_id
		 * @param int $owner_id
		 * @param string $vartype
		 */
		function annotate($name, $value, $access_id = 0, $owner_id = 0, $vartype = "") 
		{ 
		// TODO: WRITEME
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
		// TODO: WRITEME
		}
		
		/**
		 * Remove all annotations or all annotations for this entity.
		 *
		 * @param string $name
		 */
		function clearAnnotations($name = "")
		{
			
		}
		
		/**
		 * Return the annotations for the entity.
		 *
		 * @param string $name The type of annotation.
		 */
		function countAnnotations($name) 
		{ 

		}

		/**
		 * Get the average of an integer type annotation.
		 *
		 * @param string $name
		 */
		function getAnnotationsAvg($name) 
		{
			
		}
		
		/**
		 * Get the sum of integer type annotations of a given name.
		 *
		 * @param string $name
		 */
		function getAnnotationsSum($name) 
		{
			
		}
		
		/**
		 * Get the minimum of integer type annotations of given name.
		 *
		 * @param string $name
		 */
		function getAnnotationsMin($name)
		{
			
		}
		
		/**
		 * Get the maximum of integer type annotations of a given name.
		 *
		 * @param string $name
		 */
		function getAnnotationsMax($name)
		{
			
		}
		
		public function getGUID() { return $this->get('guid'); }
		public function getOwner() { return $this->get('owner_guid'); }
		public function getType() { return $this->get('type'); }
		public function getSubtype() { return get_subtype_from_id($this->get('owner_guid')); }
		public function getTimeCreated() { return $this->get('time_created'); }
		public function getTimeUpdated() { return $this->get('time_updated'); }
		
		
		// TODO: Friends/relationships
		
		
		/**
		 * Save generic attributes to the entities table.
		 */
		public function save()
		{
			if ($this->get('guid') > 0)
				return update_entity(
					$this->get('guid'),
					$this->get('owner_guid'),
					$this->get('access_id')
				);
			else
			{ 
				$this->attributes['guid'] = create_entity($this->attributes['type'], $this->attributes['subtype'], $this->attributes['owner_guid'], $this->attributes['access_id']); // Create a new entity (nb: using attribute array directly 'cos set function does something special!)
				if (!$this->attributes['guid']) throw new IOException("Unable to save new object's base entity information!"); 
				
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
			return delete_entity($this->get('guid'));
		}
		
	}

	/**
	 * Return the integer ID for a given subtype, or false.
	 * 
	 * TODO: Move to a nicer place?
	 * 
	 * @param string $subtype
	 */
	function get_subtype_id($subtype)
	{
		global $CONFIG;
		
		$subtype = sanitise_string($subtype);
		
		$result = get_data_row("SELECT * from {$CONFIG->dbprefix}entity_subtypes where subtype='$subtype'");
		if ($result)
			return $result->id;
		
		return false;
	}
	
	/**
	 * For a given subtype ID, return its identifier text.
	 *  
	 * TODO: Move to a nicer place?
	 * 
	 * @param string $subtype_id
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
		
		$access = get_access_list();
		
		
		return update_data("UPDATE {$CONFIG->dbprefix}entities set owner_guid='$owner_guid', access_id='$access_id', time_updated='$time' WHERE guid=$guid and (access_id in {$access} or (access_id = 0 and owner_guid = {$_SESSION['id']}))");
	}
	
	/**
	 * Create a new entity of a given type.
	 * 
	 * @param string $type
	 * @param string $subtype
	 * @param int $owner_guid
	 * @param int $access_id
	 * @return mixed The new entity's GUID or false.
	 */
	function create_entity($type, $subtype, $owner_guid, $access_id)
	{
		global $CONFIG;
		
		$type = sanitise_string($type);
		$subtype = get_subtype_id($subtype);
		$owner_guid = (int)$owner_guid;
		$access_id = (int)$access_id;
		$time = time();
		
		if (!$subtype)
			throw new InvalidParameterException("Entity subtype '$subtype' is not supported");
			
		return insert_data("INSERT into {$CONFIG->dbprefix}entities (type,subtype,owner_guid,access_id,time_created,time_updated) values ('$type',$subtype, $owner_guid, $access_id, $time, $time)");
	}
	
	/**
	 * Retrieve the entity details for a specific GUID, returning it as a stdClass db row.
	 * 
	 * You will only get an object if a) it exists, b) you have access to it.
	 *
	 * @param int $guid
	 */
	function get_entity_as_row($guid)
	{
		global $CONFIG;
		
		$guid = (int)$guid;
		
		$access = get_access_list();
		
		return get_data_row("SELECT * from {$CONFIG->dbprefix}entities where guid=$guid and (access_id in {$access} or (access_id = 0 and owner_guid = {$_SESSION['id']}))");
	}
	
	/**
	 * Create an Elgg* object from a given entity row. 
	 */
	function entity_row_to_elggstar($row)
	{
		if (!($row instanceof stdClass))
			return $row;
		
		switch ($row->type)
		{
			case 'object' : return new ElggObject($row);
			case 'user' : return new ElggUser($row);
			case 'collection' : return new ElggCollection($row); 
			case 'site' : return new ElggSite($row); 
			default: default : throw new InstallationException("Type {$row->type} is not supported. This indicates an error in your installation, most likely caused by an incomplete upgrade.");
		}
		
		return false;
	}
	
	/**
	 * Return the entity for a given guid as the correct object.
	 * @param $guid
	 * @return a child of ElggEntity appropriate for the type.
	 */
	function get_entity($guid)
	{
		return entity_row_to_elggstar(get_entity_as_row($guid));
	}
	
	/**
	 * Return entities matching a given query.
	 * 
	 * @param string $type
	 * @param string $subtype
	 * @param int $owner_guid
	 * @param string $order_by
	 * @param int $limit
	 * @param int $offset
	 */
	function get_entities($type = "", $subtype = "", $owner_guid = 0, $order_by = "time_created desc", $limit = 10, $offset = 0)
	{
		$type = sanitise_string($type);
		$subtype = get_subtype_id($subtype);
		$owner_guid = (int)$owner_guid;
		$order_by = sanitise_string($order_by);
		$limit = (int)$limit;
		$offset = (int)$offset;
		
		$access = get_access_list();
		
		$where = array();
		
		if ($type != "")
			$where[] = "type='$type'";
		if ($subtype)
			$where[] = "subtype=$subtype";
		if ($owner_guid != "")
			$where[] = "owner_guid='$owner_guid'";
		
		$query = "SELECT * from {$CONFIG->dbprefix}entities where ";
		foreach ($where as $w)
			$query .= " $w and ";
		$query .= " (access_id in {$access} or (access_id = 0 and owner_guid = {$_SESSION['id']}))"; // Add access controls
		$query .= " order by $order_by limit $offset,$limit"; // Add order and limit
		
		return get_data($query, "entity_row_to_elggstar");
	}
	
	/**
	 * Return entities matching a given query joining against a relationship.
	 * 
	 * @param string $relationship The relationship eg "friends_of"
	 * @param int $relationship_guid The guid of the entity to use query
	 * @param bool $inverse_relationship Reverse the normal function of the query to instead say "give me all entities for whome $relationship_guid is a $relationship of"
	 * @param string $type 
	 * @param string $subtype
	 * @param int $owner_guid
	 * @param string $order_by
	 * @param int $limit
	 * @param int $offset
	 */
	function get_entities_from_relationship($relationship, $relationship_guid, $inverse_relationship = false, $type = "", $subtype = "", $owner_guid = 0, $order_by = "time_created desc", $limit = 10, $offset = 0)
	{
		$relationship = sanitise_string($relationship);
		$relationship_guid = (int)$relationship_guid;
		$inverse_relationship = (bool)$inverse_relationship;
		$type = sanitise_string($type);
		$subtype = get_subtype_id($subtype);
		$owner_guid = (int)$owner_guid;
		$order_by = sanitise_string($order_by);
		$limit = (int)$limit;
		$offset = (int)$offset;
		
		$access = get_access_list();
		
		$where = array();
		
		if ($relationship!="")
			$where[] = "r.relationship='$relationship'";
		if ($relationship_guid)
			$where[] = ($inverse_relationship ? "r.guid_one='$relationship'" : "r.guid_two='$relationship'");
		if ($type != "")
			$where[] = "e.type='$type'";
		if ($subtype)
			$where[] = "e.subtype=$subtype";
		if ($owner_guid != "")
			$where[] = "e.owner_guid='$owner_guid'";
		
		// Select what we're joining based on the options
		$joinon = "r.guid_two=e.guid";
		if (!$inverse_relationship)
			$joinon = "r.guid_one=e.guid";	
			
		$query = "SELECT * from {$CONFIG->dbprefix}entities e JOIN {$CONFIG->dbprefix}entity_relationships r on $joinon where ";
		foreach ($where as $w)
			$query .= " $w and ";
		$query .= " (e.access_id in {$access} or (e.access_id = 0 and e.owner_guid = {$_SESSION['id']}))"; // Add access controls
		$query .= " order by $order_by limit $offset,$limit"; // Add order and limit
		
		return get_data($query, "entity_row_to_elggstar");
	}
	
	/**
	 * Delete a given entity.
	 * 
	 * @param int $guid
	 */
	function delete_entity($guid)
	{
		global $CONFIG;
		
		$guid = (int)$guid;
		
		$access = get_access_list();
		
		return delete_data("DELETE from {$CONFIG->dbprefix}entities where where guid=$guid and (access_id in {$access} or (access_id = 0 and owner_guid = {$_SESSION['id']}))"); 
		
	}

	/**
	 * Define an arbitrary relationship between two entities.
	 * This relationship could be a friendship, a group membership or a site membership.
	 * 
	 * This function lets you make the statement "$guid_one has $relationship with $guid_two".
	 * 
	 * TODO: Access controls? Are they necessary - I don't think so since we are defining 
	 * relationships between and anyone can do that. The objects should patch some access 
	 * controls over the top tho.
	 * 
	 * @param int $guid_one
	 * @param string $relationship 
	 * @param int $guid_two
	 */
	function add_entity_relationship($guid_one, $relationship, $guid_two)
	{
		global $CONFIG;
		
		$guid_one = (int)$guid_one;
		$relationship = sanitise_string($relationship);
		$guid_two = (int)$guid_two;
			
		return insert_data("INSERT into {$CONFIG->dbprefix}entity_relationships (guid_one, relationship, guid_two) values ($guid_one, 'relationship', $guid_two)");
	}

	/**
	 * Remove an arbitrary relationship between two entities.
	 * 
	 * @param int $guid_one
	 * @param string $relationship 
	 * @param int $guid_two
	 */
	function remove_entity_relationship($guid_one, $relationship, $guid_two)
	{
		global $CONFIG;
		
		$guid_one = (int)$guid_one;
		$relationship = sanitise_string($relationship);
		$guid_two = (int)$guid_two;
			
		return insert_data("DELETE from {$CONFIG->dbprefix}entity_relationships where guid_one=$guid_one and relationship='$relationship' and guid_two=$guid_two");
	}
	
	
	
	// In annotations/ meta 
	
	
?>