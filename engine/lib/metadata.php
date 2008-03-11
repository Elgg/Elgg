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
		function getOwner() { return get_user($this->owner_id); }		
		
		function save()
		{
			if ($this->id > 0)
				return update_metadata($this->id, $this->name, $this->value, $this->value_type, $this->owner_id, $this->access_id);
			else
			{ 
				$this->id = create_metadata($this->object_id, $this->object_type, $this->name, $this->value, $this->value_type, $this->owner_id, $this->access_id);
				if (!$this->id) throw new IOException("Unable to save new ElggAnnotation");
				return $this->id;
			}
			
		}
		
		/**
		 * Delete a given site.
		 */
		function delete() { return delete_metadata($this->id); }
		
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
	 * Create a new metadata object, or update an existing one.
	 *
	 * @param int $object_id
	 * @param string $object_type
	 * @param string $name
	 * @param string $value
	 * @param string $value_type
	 * @param int $owner_id
	 * @param int $access_id
	 */
	function create_metadata($object_id, $object_type, $name, $value, $value_type, $owner_id, $access_id = 0)
	{
		global $CONFIG;

		$object_id = (int)$object_id;
		$object_type = sanitise_string(trim($object_type));
		$name = sanitise_string(trim($name));
		$value = sanitise_string(trim($value));
		$value_type = detect_metadata_valuetype(sanitise_string(trim($value_type)));
		$time = time();
		
		$owner_id = (int)$owner_id;
		if ($owner_id==0) $owner_id = $_SESSION['id'];
		
		$access_id = (int)$access_id;

		$id = false;
		
		$existing = get_data_row("SELECT * from {$CONFIG->dbprefix}metadata WHERE object_id = $object_id and object_type='$object_type' and name='$name' limit 1");
		if ($existing) 
		{
			$id = $existing->id;
			$result = update_metadata($id,$name, $value, $value_type, $owner_id, $access_id);
			
			if (!$result) return false;
		}
		else
		{
			// Add the metastring
			$value = add_metastring($value);
			if (!$value) return false;
			
			// If ok then add it
			$id = insert_data("INSERT into {$CONFIG->dbprefix}metadata (object_id, object_type, name, value, value_type, owner_id, created, access_id) VALUES ($object_id,'$object_type','$name','$value','$value_type', $owner_id, $time, $access_id)");
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
	 * @param int $owner_id
	 * @param int $access_id
	 */
	function update_metadata($id, $name, $value, $value_type, $owner_id, $access_id)
	{
		global $CONFIG;

		$id = (int)$id;
		$name = sanitise_string(trim($name));
		$value = sanitise_string(trim($value));
		$value_type = detect_metadata_valuetype(sanitise_string(trim($value_type)));
		
		$owner_id = (int)$owner_id;
		if ($owner_id==0) $owner_id = $_SESSION['id'];
		
		$access_id = (int)$access_id;
		
		$access = get_access_list();
		
		
		// Add the metastring
		$value = add_metastring($value);
		if (!$value) return false;
		
		// If ok then add it
		return update_data("UPDATE {$CONFIG->dbprefix}metadata set value='$value', value_type='$value_type', access_id=$access_id, owner_id=$owner_id where id=$id and name='$name' and (access_id in {$access} or (access_id = 0 and owner_id = {$_SESSION['id']}))");
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
				
		return row_to_elggmetadata(get_data_row("SELECT * from {$CONFIG->dbprefix}metadata where id=$id and (access_id in {$access} or (access_id = 0 and owner_id = {$_SESSION['id']}))"));
	}

	/**
	 * Get a list of metadatas for a given object/user/metadata type.
	 *
	 * @param int $object_id
	 * @param string $object_type
	 * @param int $owner_id
	 * @param string $order_by
	 * @param int $limit
	 * @param int $offset
	 * @return array of ElggMetadata
	 */
	function get_metadatas($object_id = 0, $object_type = "", $name = "", $value = "", $owner_id = 0, $order_by = "created desc", $limit = 10, $offset = 0)
	{
		global $CONFIG;
		
		$object_id = (int)$object_id;
		$object_type = sanitise_string(trim($object_type));
		$name = sanitise_string(trim($name));
		$value = sanitise_string(trim($value));
		
		$owner_id = (int)$owner_id;
		
		$limit = (int)$limit;
		$offset = (int)$offset;
		
		// Construct query
		$where = array();
		
		if ($object_id != 0)
			$where[] = "object_id=$object_id";
			
		if ($object_type != "")
			$where[] = "object_type='$object_type'";
		
		if ($owner_id != 0)
			$where[] = "owner_id=$owner_id";
			
		if ($name != "")
			$where[] = "name='$name'";
			
		if ($value != "")
			$where[] = "value='$value'";
			
		// add access controls
		$access = get_access_list();
		$where[] = "(access_id in {$access} or (access_id = 0 and owner_id = {$_SESSION['id']}))";
			
		// construct query.
		$query = "SELECT * from {$CONFIG->dbprefix}metadata where ";
		for ($n = 0; $n < count($where); $n++)
		{
			if ($n > 0) $query .= " and ";
			$query .= $where[$n];
		}
		
		return get_data($query, "row_to_elggmetadata");
	}

	/**
	 * Similar to get_metadatas, but instead returns the objects associated with a given meta search.
	 * 
	 * @param int $object_id
	 * @param string $object_type
	 * @param int $owner_id
	 * @param string $order_by
	 * @param int $limit
	 * @param int $offset
	 * @return mixed Array of objects or false.
	 */
	function get_objects_from_metadatas($object_id = 0, $object_type = "", $name = "", $value = "", $owner_id = 0, $order_by = "created desc", $limit = 10, $offset = 0)
	{
		$results = get_metadatas($object_id, $object_type, $name, $value, $owner_id, $order_by, $limit, $offset);
		$objects = false;
		
		if ($results)
		{
			$objects = array();
			
			foreach ($results as $r)
			{
				
				switch ($r->object_type)
				{
					case 'object' : $objects[] = new ElggObject((int)$r->object_id); break;
					case 'user' : $objects[] = new ElggUser((int)$r->object_id); break;
					case 'collection' : $objects[] = new ElggCollection((int)$r->object_id); break;
					case 'site' : $objects[] = new ElggSite((int)$r->object_id); break;
					default: default : throw new InstallationException("Type {$r->object_type} is not supported. This indicates an error in your installation, most likely caused by an incomplete upgrade.");
				}
			}
		}
		
		return $objects;
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
				
		return delete_data("DELETE from {$CONFIG->dbprefix}metadata where id=$id and (access_id in {$access} or (access_id = 0 and owner_id = {$_SESSION['id']}))");
		
	}
?>