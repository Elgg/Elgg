<?php
	/**
	 * Elgg relationships.
	 * Stub containing relationship functions, making import and export easier.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Relationship class.
	 * 
	 * @author Marcus Povey
	 * @package Elgg
	 * @subpackage Core
	 */
	class ElggRelationship implements 
		Importable, 
		Exportable,
		Iterator,	// Override foreach behaviour
		ArrayAccess // Override for array access
	{
		/**
		 * This contains the site's main properties (id, etc)
		 * @var array
		 */
		protected $attributes;
		
		/**
		 * Construct a new site object, optionally from a given id value or row.
		 *
		 * @param mixed $id
		 */
		function __construct($id = null) 
		{
			$this->attributes = array();
			
			if (!empty($id)) {
				
				if ($id instanceof stdClass)
					$relationship = $id; // Create from db row
				else
					$relationship = get_relationship($id);	
				
				if ($relationship) {
					$objarray = (array) $relationship;
					foreach($objarray as $key => $value) {
						$this->attributes[$key] = $value;
					}
				}
			}
		}
		
		/**
		 * Class member get overloading
		 *
		 * @param string $name
		 * @return mixed
		 */
		protected function __get($name) {
			if (isset($this->attributes[$name])) 
				return $this->attributes[$name];
	
			return null;
		}
		
		/**
		 * Class member set overloading
		 *
		 * @param string $name
		 * @param mixed $value
		 * @return mixed
		 */
		protected function __set($name, $value) {
			$this->attributes[$name] = $value;
			return true;
		}

		/**
		 * Save the relationship
		 *
		 * @return int the relationship id
		 */
		public function save()
		{
			if ($this->id > 0)
			{
				delete_relationship($this->id);
			}

			$this->id = add_entity_relationship($this->guid_one, $this->relationship, $this->guid_two);
			if (!$this->id) throw new IOException("Unable to save new ElggAnnotation");

			return $this->id;
			
		}
		
		/**
		 * Delete a given relationship.
		 */
		public function delete() 
		{ 
			return delete_relationship($this->id); 
		}
	
		// EXPORTABLE INTERFACE ////////////////////////////////////////////////////////////
		
		/**
		 * Export this relationship
		 *
		 * @return array
		 */
		public function export()
		{			
			return new ODDRelationship(
				guid_to_uuid($this->guid_one),
				$this->relationship,
				guid_to_uuid($this->guid_two)
			);
		}
		
		// IMPORTABLE INTERFACE ////////////////////////////////////////////////////////////
		
		/**
		 * Import a relationship
		 *
		 * @param array $data
		 * @param int $version
		 * @return ElggRelationship
		 * @throws ImportException
		 */
		public function import(ODD $data)
		{
			if (!($element instanceof ODDRelationship))
				throw new InvalidParameterException("ElggRelationship::import() passed an unexpected ODD class"); 
			
			$uuid_one = $data->getAttribute('uuid1');
			$uuid_two = $data->getAttribute('uuid2'); 	
				
			// See if this entity has already been imported, if so then we need to link to it
			$entity1 = get_entity_from_uuid($uuid_one);
			$entity2 = get_entity_from_uuid($uuid_two);
			if (($entity1) && ($entity2))
			{
				// Set the item ID
				$this->attributes['guid_one'] = $entity1->getGUID();
				$this->attributes['guid_two'] = $entity2->getGUID();
				
				// Map verb to relationship
				//$verb = $data->getAttribute('verb');
				//$relationship = get_relationship_from_verb($verb);
				$relationship = $data->getAttribute('type');
				
				if ($relationship)
				{	
					$this->attributes['relationship'] = $relationship;
					// save
					$result = $this->save(); 
					if (!$result)
						throw new ImportException("There was a problem saving the ElggExtender");
					
					return $this;
				}
			}
		}
		
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
	 * Convert a database row to a new ElggRelationship
	 *
	 * @param stdClass $row
	 * @return stdClass or ElggMetadata
	 */
	function row_to_elggrelationship($row) 
	{
		if (!($row instanceof stdClass))
			return $row;
			
		return new ElggRelationship($row);
	}
	
	/**
	 * Return a relationship.
	 *
	 * @param int $id
	 */
	function get_relationship($id)
	{
		global $CONFIG;
		
		$id = (int)$id;
		
		return row_to_elggrelationship(get_data_row("SELECT * from {$CONFIG->dbprefix}entity_relationships where id=$id"));
	}
	
	/**
	 * Delete a specific relationship.
	 *
	 * @param int $id
	 */
	function delete_relationship($id)
	{
		global $CONFIG;
		
		$id = (int)$id;
		
		return row_to_elggrelationship(get_data_row("delete from {$CONFIG->dbprefix}entity_relationships where id=$id"));
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
			
		return insert_data("INSERT into {$CONFIG->dbprefix}entity_relationships (guid_one, relationship, guid_two) values ($guid_one, '$relationship', $guid_two)");
	}
	
	/**
	 * Determine whether or not a relationship between two entities exists
	 *
	 * @param int $guid_one The GUID of the entity "owning" the relationship
	 * @param string $relationship The type of relationship
	 * @param int $guid_two The GUID of the entity the relationship is with
	 * @return true|false
	 */
	function check_entity_relationship($guid_one, $relationship, $guid_two)
	{
		global $CONFIG;
		
		$guid_one = (int)$guid_one;
		$relationship = sanitise_string($relationship);
		$guid_two = (int)$guid_two;
			
		if ($row = get_data_row("select guid_one from {$CONFIG->dbprefix}entity_relationships where guid_one=$guid_one and relationship='$relationship' and guid_two=$guid_two limit 1")) {
			return true;
		}
		return false;
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
			
		return delete_data("DELETE from {$CONFIG->dbprefix}entity_relationships where guid_one=$guid_one and relationship='$relationship' and guid_two=$guid_two");
	}

	/**
	 * Get all the relationships for a given guid.
	 * 
	 * @param int $guid
	 */
	function get_entity_relationships($guid)
	{
		global $CONFIG;
		
		$guid = (int)$guid;
		
		$query = "SELECT * from {$CONFIG->dbprefix}entity_relationships where guid_one=$guid";
		
		return get_data($query, "row_to_elggrelationship");
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
	 * @param boolean $count Set to true if you want to count the number of entities instead (default false)
	 * @return array|int|false An array of entities, or the number of entities, or false on failure
	 */
	function get_entities_from_relationship($relationship, $relationship_guid, $inverse_relationship = false, $type = "", $subtype = "", $owner_guid = 0, $order_by = "time_created desc", $limit = 10, $offset = 0, $count = false)
	{
		global $CONFIG;
		
		$relationship = sanitise_string($relationship);
		$relationship_guid = (int)$relationship_guid;
		$inverse_relationship = (bool)$inverse_relationship;
		$type = sanitise_string($type);
		$subtype = get_subtype_id($type, $subtype);
		$owner_guid = (int)$owner_guid;
		$order_by = sanitise_string($order_by);
		$limit = (int)$limit;
		$offset = (int)$offset;
		
		$access = get_access_list();
		
		$where = array();
		
		if ($relationship!="")
			$where[] = "r.relationship='$relationship'";
		if ($relationship_guid)
			$where[] = ($inverse_relationship ? "r.guid_one='$relationship_guid'" : "r.guid_two='$relationship_guid'");
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
			
		if ($count) {
			$query = "select count(distinct e.id) as total ";
		} else {
			$query = "select distinct e.* ";
		}
		$query .= " from {$CONFIG->dbprefix}entities e JOIN {$CONFIG->dbprefix}entity_relationships r on $joinon where ";
		foreach ($where as $w)
			$query .= " $w and ";
		$query .= " (e.access_id in {$access} or (e.access_id = 0 and e.owner_guid = {$_SESSION['id']}))"; // Add access controls
		if (!$count) {
			$query .= " order by $order_by limit $offset, $limit"; // Add order and limit
			return get_data($query, "entity_row_to_elggstar");
		} else {
			if ($count = get_data_row($query)) {
				return $count->total;
			}
		}
		return false;
		
	}
	
	/**
	 *  Handler called by trigger_plugin_hook on the "import" event.
	 */
	function import_relationship_plugin_hook($hook, $entity_type, $returnvalue, $params)
	{
		$element = $params['element'];
		
		$tmp = NULL;
		
		if ($element instanceof ODDRelationship)
		{
			$tmp = new ElggRelationship();
			$tmp->import($element);
			
			return $tmp;
		}
	}
	
	/**
	 *  Handler called by trigger_plugin_hook on the "export" event.
	 */
	function export_relationship_plugin_hook($hook, $entity_type, $returnvalue, $params)
	{
		global $CONFIG;
		
		// Sanity check values
		if ((!is_array($params)) && (!isset($params['guid'])))
			throw new InvalidParameterException("GUID has not been specified during export, this should never happen.");
			
		if (!is_array($returnvalue))
			throw new InvalidParameterException("Entity serialisation function passed a non-array returnvalue parameter");
			
		$guid = (int)$params['guid'];
		
		$result = get_entity_relationships($guid);
		
		if ($result)
		{
			foreach ($result as $r)
				$returnvalue[] = $r->export();
		}
		
		return $returnvalue;
	}
	
	/** Register the import hook */
	register_plugin_hook("import", "all", "import_relationship_plugin_hook", 3);
	
	/** Register the hook, ensuring entities are serialised first */
	register_plugin_hook("export", "all", "export_relationship_plugin_hook", 3);
?>