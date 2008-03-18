<?php
	/**
	 * Elgg metadata
	 * Functions to manage object metadata.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * @class ElggMetadata
	 * This class describes metadata that can be attached to ElggEntities.
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 */
	class ElggMetadata
	{
		/**
		 * This contains the site's main properties (id, etc)
		 * @var array
		 */
		private $attributes;
		
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
					$metadata = $id; // Create from db row
				else
					$metadata = get_metadata($id);	
				
				if ($metadata) {
					$objarray = (array) $metadata;
					foreach($objarray as $key => $value) {
						$this->attributes[$key] = $value;
					}
				}
			}
		}
		
		function __get($name) {
			if (isset($this->attributes[$name])) {
				
				// Sanitise value if necessary
				if ($name=='value')
				{
					switch ($this->attributes['value_type'])
					{
						case 'integer' :  return (int)$this->attributes['value'];
						case 'tag' :
						case 'text' :
						case 'file' : return sanitise_string($this->attributes['value']);
							
						default : throw new InstallationException("Type {$this->attributes['value_type']} is not supported. This indicates an error in your installation, most likely caused by an incomplete upgrade.");
					}
				}
				
				return $this->attributes[$name];
			}
			return null;
		}
		
		function __set($name, $value) {
			$this->attributes[$name] = $value;
			return true;
		}	
		
		/**
		 * Return the owner of this metadata.
		 *
		 * @return mixed
		 */
		function getOwner() 
		{ 
			return get_user($this->owner_guid); 
		}		
		
		function save()
		{
			if ($this->id > 0)
				return update_metadata($this->id, $this->name, $this->value, $this->value_type, $this->owner_guid, $this->access_id);
			else
			{ 
				$this->id = create_metadata($this->entity_guid, $this->name, $this->value, $this->value_type, $this->owner_guid, $this->access_id);
				if (!$this->id) throw new IOException("Unable to save new ElggAnnotation");
				return $this->id;
			}
			
		}
		
		/**
		 * Delete a given site.
		 */
		function delete() 
		{ 
			return delete_metadata($this->id); 
		}
	}
	
	
	/**
	 * Convert a database row to a new ElggMetadata
	 *
	 * @param stdClass $row
	 * @return stdClass or ElggMetadata
	 */
	function row_to_elggmetadata($row) 
	{
		if (!($row instanceof stdClass))
			return $row;
			
		return new ElggMetadata($row);
	}
	
	/**
	 * Detect the value_type for a given value.
	 * Currently this is very crude.
	 * 
	 * TODO: Make better!
	 *
	 * @param mixed $value
	 * @param string $value_type If specified, overrides the detection.
	 * @return string
	 */
	function detect_metadata_valuetype($value, $value_type = "")
	{
		if ($value_type!="")
			return $value_type;
			
		// This is crude
		if (is_int($value)) return 'integer';
		
		return 'tag';
	}
			
	/**
	 * Get a specific item of metadata.
	 * 
	 * @param $id int The item of metadata being retrieved.
	 */
	function get_metadata($id)
	{
		global $CONFIG;

		$id = (int)$id;
		$access = get_access_list();
				
		return row_to_elggmetadata(get_data_row("SELECT m.*, v.string as value from {$CONFIG->dbprefix}metadata m JOIN {$CONFIG->dbprefix}metastrings v on m.value_id = v.id where m.id=$id and (m.access_id in {$access} or (m.access_id = 0 and m.owner_guid = {$_SESSION['id']}))"));
	}
	
	/**
	 * Create a new metadata object, or update an existing one.
	 *
	 * @param int $entity_guid
	 * @param string $name
	 * @param string $value
	 * @param string $value_type
	 * @param int $owner_guid
	 * @param int $access_id
	 */
	function create_metadata($entity_guid, $name, $value, $value_type, $owner_guid, $access_id = 0)
	{
		global $CONFIG;

		$entity_guid = (int)$entity_guid;
		$name = sanitise_string(trim($name));
		$value = sanitise_string(trim($value));
		$value_type = detect_metadata_valuetype($value, sanitise_string(trim($value_type)));
		$time = time();		
		$owner_guid = (int)$owner_guid;
		
		if ($owner_guid==0) $owner_guid = $_SESSION['id'];
		
		$access_id = (int)$access_id;

		$id = false;
	
		$existing = get_data_row("SELECT * from {$CONFIG->dbprefix}metadata WHERE entity_guid = $entity_guid and name='$name' limit 1");
		if ($existing) 
		{
			$id = $existing->id;
			$result = update_metadata($id, $name, $value, $value_type, $owner_guid, $access_id);
			
			if (!$result) return false;
		}
		else
		{
			// Add the metastring
			$value = add_metastring($value);
			if (!$value) return false;
			
			// If ok then add it
			$id = insert_data("INSERT into {$CONFIG->dbprefix}metadata (entity_guid, name, value_id, value_type, owner_guid, time_created, access_id) VALUES ($entity_guid, '$name','$value','$value_type', $owner_guid, $time, $access_id)");
		}
		
		return $id;
	}
	
	/**
	 * Update an item of metadata.
	 *
	 * @param int $id
	 * @param string $name
	 * @param string $value
	 * @param string $value_type
	 * @param int $owner_guid
	 * @param int $access_id
	 */
	function update_metadata($id, $name, $value, $value_type, $owner_guid, $access_id)
	{
		global $CONFIG;

		$id = (int)$id;
		$name = sanitise_string(trim($name));
		$value = sanitise_string(trim($value));
		$value_type = detect_metadata_valuetype($value, sanitise_string(trim($value_type)));
		
		$owner_guid = (int)$owner_guid;
		if ($owner_guid==0) $owner_guid = $_SESSION['id'];
		
		$access_id = (int)$access_id;
		
		$access = get_access_list();
		
		
		// Add the metastring
		$value = add_metastring($value);
		if (!$value) return false;
		
		// If ok then add it
		return update_data("UPDATE {$CONFIG->dbprefix}metadata set value_id='$value', value_type='$value_type', access_id=$access_id, owner_guid=$owner_guid where id=$id and name='$name' and (access_id in {$access} or (access_id = 0 and owner_guid = {$_SESSION['id']}))");
	}
	
	
	/**
	 * Delete an item of metadata, where the current user has access.
	 * 
	 * @param $id int The item of metadata to delete.
	 */
	function delete_metadata($id)
	{
		global $CONFIG;

		$id = (int)$id;
		$access = get_access_list();
				
		return delete_data("DELETE from {$CONFIG->dbprefix}metadata where id=$id and (access_id in {$access} or (access_id = 0 and owner_guid = {$_SESSION['id']}))");
		
	}
	
	/**
	 * Return the metadata values that match your query.
	 * 
	 * @param string $meta_name
	 */
	function get_metadata_byname($entity_guid,  $meta_name)
	{
		global $CONFIG;
	
		$meta_name = sanitise_string($meta_name);
		$entity_guid = (int)$entity_guid;
		$access = get_access_list();
		
		return row_to_elggmetadata(get_data_row("SELECT m.*, v.string as value from {$CONFIG->dbprefix}metadata m JOIN {$CONFIG->dbprefix}metastrings v on m.value_id = v.id where m.entity_guid=$entity_guid and m.name='$meta_name' and (m.access_id in {$access} or (m.access_id = 0 and m.owner_guid = {$_SESSION['id']}))"));
	}

	/**
	 * Return a list of entities based on the given search criteria.
	 * 
	 * @param mixed $meta_name 
	 * @param mixed $meta_value
	 * @param string $entity_type The type of entity to look for, eg 'site' or 'object'
	 * @param string $entity_subtype The subtype of the entity.
	 * @param int $limit 
	 * @param int $offset
	 * @param string $order_by Optional ordering.
	 */
	function get_entities_from_metadata($meta_name, $meta_value = "", $entity_type = "", $entity_subtype = "", $limit = 10, $offset = 0, $order_by = "e.time_created desc")
	{
		global $CONFIG;
		
		$meta_name = sanitise_string($meta_name);
		$meta_value = get_metastring_id($meta_value);
			
		$entity_type = sanitise_string($entity_type);
		$entity_subtype = get_subtype_id($entity_subtype);
		$limit = (int)$limit;
		$offset = (int)$offset;
		$order_by = sanitise_string($order_by);
			
		$access = get_access_list();
			
		$where = array();
		
		if ($entity_type!="")
			$where[] = "e.type='$entity_type'";
		if ($entity_subtype)
			$where[] = "e.subtype=$entity_subtype";
		if ($meta_name!="")
			$where[] = "m.name='$meta_name'";
		if ($meta_value!="")
			$where[] = "m.value_id='$meta_value'";
		
		$query = "SELECT * from {$CONFIG->dbprefix}entities e JOIN {$CONFIG->dbprefix}metadata m on e.guid = m.entity_guid where";
		foreach ($where as $w)
			$query .= " $w and ";
		$query .= " (e.access_id in {$access} or (e.access_id = 0 and e.owner_guid = {$_SESSION['id']}))"; // Add access controls
		$query .= " order by $order_by limit $limit, $offset"; // Add order and limit
	echo $query;	
		return get_data($query, "entity_row_to_elggstar");
	}
	
	/**
	 * Clear all the metadata for a given entity, assuming you have access to that metadata.
	 * 
	 * @param int $guid
	 */
	function clear_metadata($entity_guid)
	{
		global $CONFIG;
		
		$entity_guid = (int)$entity_guid;
		
		return delete_data("DELETE from {$CONFIG->dbprefix}metadata where entity_guid=$entity_guid and access_id in {$access} or (access_id = 0 and owner_guid = {$_SESSION['id']})");
	}
	
?>