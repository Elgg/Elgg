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
	 * ElggMetadata
	 * This class describes metadata that can be attached to ElggEntities.
	 * 
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @package Elgg
	 * @subpackage Core
	 */
	class ElggMetadata extends ElggExtender
	{
			
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
					$this->attributes['type'] = "metadata";
				}
			}
		}
		
		/**
		 * Class member get overloading
		 *
		 * @param string $name
		 * @return mixed
		 */
		function __get($name) {
			return $this->get($name);
		}
		
		/**
		 * Class member set overloading
		 *
		 * @param string $name
		 * @param mixed $value
		 * @return mixed
		 */
		function __set($name, $value) {
			return $this->set($name, $value);
		}

		/**
		 * Determines whether or not the user can edit this piece of metadata
		 *
		 * @return true|false Depending on permissions
		 */
		function canEdit() {
			
			if ($entity = get_entity($this->get('entity_guid'))) {
				return $entity->canEdit();
			}
			return false;
			
		}
		
		/**
		 * Save matadata object
		 *
		 * @return int the metadata object id
		 */
		function save()
		{
			if ($this->id > 0)
				return update_metadata($this->id, $this->name, $this->value, $this->value_type, $this->owner_guid, $this->access_id);
			else
			{ 
				$this->id = create_metadata($this->entity_guid, $this->name, $this->value, $this->value_type, $this->owner_guid, $this->access_id);
				if (!$this->id) throw new IOException(sprintf(elgg_new('IOException:UnableToSaveNew'), get_class()));
				return $this->id;
			}
			
		}
		
		/**
		 * Delete a given metadata.
		 */
		function delete() 
		{ 
			return delete_metadata($this->id); 
		}
	
		// SYSTEM LOG INTERFACE ////////////////////////////////////////////////////////////

		/**
		 * For a given ID, return the object associated with it.
		 * This is used by the river functionality primarily.
		 * This is useful for checking access permissions etc on objects.
		 */
		public function getObjectFromID($id) { return get_metadata($id); }
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
	 * Get a specific item of metadata.
	 * 
	 * @param $id int The item of metadata being retrieved.
	 */
	function get_metadata($id)
	{
		global $CONFIG;

		$id = (int)$id;
		$access = get_access_sql_suffix("e");
				
		return row_to_elggmetadata(get_data_row("SELECT m.*, n.string as name, v.string as value from {$CONFIG->dbprefix}metadata m JOIN {$CONFIG->dbprefix}entities e on e.guid = m.entity_guid JOIN {$CONFIG->dbprefix}metastrings v on m.value_id = v.id JOIN {$CONFIG->dbprefix}metastrings n on m.name_id = n.id where m.id=$id and $access"));
	}
	
	/**
	 * Removes metadata on an entity with a particular name
	 *
	 * @param int $entity_guid The entity GUID
	 * @param string $name The name of the entity
	 * @return true|false Depending on success
	 */
	function remove_metadata($entity_guid, $name) {
		
		global $CONFIG;
		$entity_guid = (int) $entity_guid;
		$name = sanitise_string(trim($name));
		if ($existing = get_data("SELECT * from {$CONFIG->dbprefix}metadata WHERE entity_guid = $entity_guid and name_id=" . add_metastring($name))) {
			foreach($existing as $ex)
				delete_metadata($ex->id);
			return true;
		}
		return false;
		
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
	 * @param bool $allow_multiple
	 */
	function create_metadata($entity_guid, $name, $value, $value_type, $owner_guid, $access_id = 0, $allow_multiple = false)
	{
		global $CONFIG;

		$entity_guid = (int)$entity_guid;
		//$name = sanitise_string(trim($name));
		//$value = sanitise_string(trim($value));
		$value_type = detect_extender_valuetype($value, sanitise_string(trim($value_type)));
		$time = time();		
		$owner_guid = (int)$owner_guid;
		$allow_multiple = (boolean)$allow_multiple;
		
		if ($owner_guid==0) $owner_guid = $_SESSION['id'];
		
		$access_id = (int)$access_id;

		$id = false;
	
		$existing = get_data_row("SELECT * from {$CONFIG->dbprefix}metadata WHERE entity_guid = $entity_guid and name_id=" . add_metastring($name) . " limit 1");
		if (($existing) && (!$allow_multiple) && (isset($value))) 
		{
			$id = $existing->id;
			$result = update_metadata($id, $name, $value, $value_type, $owner_guid, $access_id);
			
			if (!$result) return false;
		}
		else if (isset($value))
		{
			// Support boolean types
			if (is_bool($value)) {
				if ($value)
					$value = 1;
				else
					$value = 0;
			}
			
			// Add the metastrings
			$value = add_metastring($value);
			if (!$value) return false;
			
			$name = add_metastring($name);
			if (!$name) return false;
			
			// If ok then add it
			$id = insert_data("INSERT into {$CONFIG->dbprefix}metadata (entity_guid, name_id, value_id, value_type, owner_guid, time_created, access_id) VALUES ($entity_guid, '$name','$value','$value_type', $owner_guid, $time, $access_id)");
		} else if ($existing) {
// TODO: Check... are you sure you meant to do this Ben? :)			
			$id = $existing->id;
			delete_metadata($id);
			
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
	
		if (!$md = get_metadata($id)) return false;
		if (!$md->canEdit()) return false;
		
		//$name = sanitise_string(trim($name));
		//$value = sanitise_string(trim($value));
		$value_type = detect_extender_valuetype($value, sanitise_string(trim($value_type)));
		
		$owner_guid = (int)$owner_guid;
		if ($owner_guid==0) $owner_guid = $_SESSION['id'];
		
		$access_id = (int)$access_id;
		
		$access = get_access_sql_suffix();
		
		// Support boolean types (as integers)
		if (is_bool($value)) {
			if ($value)
				$value = 1;
			else
				$value = 0;
		}
		
		// Add the metastring
		$value = add_metastring($value);
		if (!$value) return false;
		
		$name = add_metastring($name);
		if (!$name) return false;
		
		// If ok then add it
		return update_data("UPDATE {$CONFIG->dbprefix}metadata set value_id='$value', value_type='$value_type', access_id=$access_id, owner_guid=$owner_guid where id=$id and name_id='$name'");
	}
	
	/**
	 * This function creates metadata from an associative array of "key => value" pairs.
	 * 
	 * @param int $entity_guid
	 * @param string $name_and_values
	 * @param string $value_type
	 * @param int $owner_guid
	 * @param int $access_id
	 * @param bool $allow_multiple
	 */
	function create_metadata_from_array($entity_guid, array $name_and_values, $value_type, $owner_guid, $access_id = 0, $allow_multiple = false)
	{
		foreach ($name_and_values as $k => $v)
			if (!create_metadata($entity_guid, $k, $v, $value_type, $owner_guid, $access_id, $allow_multiple)) return false;
		
		return true;
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
		$metadata = get_metadata($id);
		
		if ($metadata->canEdit())
			return delete_data("DELETE from {$CONFIG->dbprefix}metadata where id=$id");
		
	}
	
	/**
	 * Return the metadata values that match your query.
	 * 
	 * @param string $meta_name
	 * @return mixed either a value, an array of ElggMetadata or false.
	 */
	function get_metadata_byname($entity_guid,  $meta_name)
	{
		global $CONFIG;
	
		$meta_name = get_metastring_id($meta_name);
		$entity_guid = (int)$entity_guid;
		$access = get_access_sql_suffix("e");
		$result = get_data("SELECT m.*, n.string as name, v.string as value from {$CONFIG->dbprefix}metadata m JOIN {$CONFIG->dbprefix}entities e ON e.guid = m.entity_guid JOIN {$CONFIG->dbprefix}metastrings v on m.value_id = v.id JOIN {$CONFIG->dbprefix}metastrings n on m.name_id = n.id where m.entity_guid=$entity_guid and m.name_id='$meta_name' and $access", "row_to_elggmetadata");
		if (!$result) 
			return false;
		
		if (count($result) == 1)
			return $result[0];
			
		return $result;
	}
	
	/**
	 * Return all the metadata for a given GUID.
	 * 
	 * @param int $entity_guid
	 */
	function get_metadata_for_entity($entity_guid)
	{
		global $CONFIG;
	
		$entity_guid = (int)$entity_guid;
		$access = get_access_sql_suffix("e");
		
		return get_data("SELECT m.*, n.string as name, v.string as value from {$CONFIG->dbprefix}metadata m JOIN {$CONFIG->dbprefix}entities e ON e.guid = m.entity_guid JOIN {$CONFIG->dbprefix}metastrings v on m.value_id = v.id JOIN {$CONFIG->dbprefix}metastrings n on m.name_id = n.id where m.entity_guid=$entity_guid and $access", "row_to_elggmetadata");
	}

	/**
	 * Get the metadata where the entities they are referring to match a given criteria.
	 * 
	 * @param mixed $meta_name 
	 * @param mixed $meta_value
	 * @param string $entity_type The type of entity to look for, eg 'site' or 'object'
	 * @param string $entity_subtype The subtype of the entity.
	 * @param int $limit 
	 * @param int $offset
	 * @param string $order_by Optional ordering.
	 * @param int $site_guid The site to get entities for. Leave as 0 (default) for the current site; -1 for all sites.
	 */
	function find_metadata($meta_name = "", $meta_value = "", $entity_type = "", $entity_subtype = "", $limit = 10, $offset = 0, $order_by = "e.time_created desc", $site_guid = 0)
	{
		global $CONFIG;
		
		$meta_n = get_metastring_id($meta_name);
		$meta_v = get_metastring_id($meta_value);
			
		$entity_type = sanitise_string($entity_type);
		$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
		$limit = (int)$limit;
		$offset = (int)$offset;
		$order_by = sanitise_string($order_by);
		$site_guid = (int) $site_guid;
		if ($site_guid == 0)
			$site_guid = $CONFIG->site_guid;
			
			
		$where = array();
		
		if ($entity_type!="")
			$where[] = "e.type='$entity_type'";
		if ($entity_subtype)
			$where[] = "e.subtype=$entity_subtype";
		if ($meta_name!="")
			$where[] = "m.name_id='$meta_n'";
		if ($meta_value!="")
			$where[] = "m.value_id='$meta_v'";
		if ($site_guid > 0)
			$where[] = "e.site_guid = {$site_guid}";
		
		$query = "SELECT m.*, n.string as name, v.string as value from {$CONFIG->dbprefix}entities e JOIN {$CONFIG->dbprefix}metadata m on e.guid = m.entity_guid JOIN {$CONFIG->dbprefix}metastrings v on m.value_id = v.id JOIN {$CONFIG->dbprefix}metastrings n on m.name_id = n.id where";
		foreach ($where as $w)
			$query .= " $w and ";
		$query .= get_access_sql_suffix("e"); // Add access controls
		$query .= " order by $order_by limit $offset, $limit"; // Add order and limit

		return get_data($query, "row_to_elggmetadata");
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
	 * @param int $site_guid The site to get entities for. Leave as 0 (default) for the current site; -1 for all sites.
	 */
	function get_entities_from_metadata($meta_name, $meta_value = "", $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "e.time_created desc", $site_guid = 0)
	{
		global $CONFIG;
		
		$meta_n = get_metastring_id($meta_name);
		$meta_v = get_metastring_id($meta_value);
			
		$entity_type = sanitise_string($entity_type);
		$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
		$limit = (int)$limit;
		$offset = (int)$offset;
		$order_by = sanitise_string($order_by);
		$site_guid = (int) $site_guid;
		$owner_guid = (int) $owner_guid;
		if ($site_guid == 0)
			$site_guid = $CONFIG->site_guid;
			
		//$access = get_access_list();
			
		$where = array();
		
		if ($entity_type!="")
			$where[] = "e.type='$entity_type'";
		if ($entity_subtype)
			$where[] = "e.subtype=$entity_subtype";
		if ($meta_name!="")
			$where[] = "m.name_id='$meta_n'";
		if ($meta_value!="")
			$where[] = "m.value_id='$meta_v'";
		if ($site_guid > 0)
			$where[] = "e.site_guid = {$site_guid}";
		if ($owner_guid > 0)
			$where[] = "e.owner_guid = {$owner_guid}";
		
		$query = "SELECT distinct e.* from {$CONFIG->dbprefix}entities e JOIN {$CONFIG->dbprefix}metadata m on e.guid = m.entity_guid where";
		foreach ($where as $w)
			$query .= " $w and ";
		$query .= get_access_sql_suffix("e"); // Add access controls
		$query .= " order by $order_by limit $offset, $limit"; // Add order and limit

		return get_data($query, "entity_row_to_elggstar");
	}

	/**
	 * Returns a list of entities based on the given search criteria.
	 *
	 * @param array $meta_array Array of 'name' => 'value' pairs
	 * @param string $entity_type The type of entity to look for, eg 'site' or 'object'
	 * @param string $entity_subtype The subtype of the entity.
	 * @param int $limit 
	 * @param int $offset
	 * @param string $order_by Optional ordering.
	 * @param int $site_guid The site to get entities for. Leave as 0 (default) for the current site; -1 for all sites.
	 * @return array List of ElggEntities
	 */
	function get_entities_from_metadata_multi($meta_array, $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "e.time_created desc", $site_guid = 0)
	{
		global $CONFIG;
		
		if (!is_array($meta_array) || sizeof($meta_array) == 0) {
			return false;
		}
		
		$where = array();
		
		$mindex = 1;
		$join = "";
		foreach($meta_array as $meta_name => $meta_value) {
			$meta_n = get_metastring_id($meta_name);
			$meta_v = get_metastring_id($meta_value);
			$join .= " JOIN {$CONFIG->dbprefix}metadata m{$mindex} on e.guid = m{$mindex}.entity_guid "; 
			if ($meta_name!="")
				$where[] = "m{$mindex}.name_id='$meta_n'";
			if ($meta_value!="")
				$where[] = "m{$mindex}.value_id='$meta_v'";
			$mindex++;
		}
			
		$entity_type = sanitise_string($entity_type);
		$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
		$limit = (int)$limit;
		$offset = (int)$offset;
		$order_by = sanitise_string($order_by);
		$owner_guid = (int) $owner_guid;
		
		$site_guid = (int) $site_guid;
		if ($site_guid == 0)
			$site_guid = $CONFIG->site_guid;
			
		//$access = get_access_list();
		
		if ($entity_type!="")
			$where[] = "e.type = '{$entity_type}'";
		if ($entity_subtype)
			$where[] = "e.subtype = {$entity_subtype}";
		if ($site_guid > 0)
			$where[] = "e.site_guid = {$site_guid}";
		if ($owner_guid > 0)
			$where[] = "e.owner_guid = {$owner_guid}";
		
		$query = "SELECT distinct e.* from {$CONFIG->dbprefix}entities e {$join} where";
		foreach ($where as $w)
			$query .= " $w and ";
		$query .= get_access_sql_suffix("e"); // Add access controls
		$query .= " order by $order_by limit $offset, $limit"; // Add order and limit

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
		if ($entity = get_entity($entity_guid)) {
			if ($entity->canEdit())
				return delete_data("DELETE from {$CONFIG->dbprefix}metadata where entity_guid={$entity_guid}");
		}
		return false;
	}
	
	/**
	 * Handler called by trigger_plugin_hook on the "export" event.
	 */
	function export_metadata_plugin_hook($hook, $entity_type, $returnvalue, $params)
	{
		// Sanity check values
		if ((!is_array($params)) && (!isset($params['guid'])))
			throw new InvalidParameterException(elgg_echo('InvalidParameterException:GUIDNotForExport'));
			
		if (!is_array($returnvalue))
			throw new InvalidParameterException(elgg_echo('InvalidParameterException:NonArrayReturnValue'));
		
		$guid = (int)$params['guid'];
		$name = $params['name'];	
		
		$result = get_metadata_for_entity($guid); 
				
		if ($result)
		{
			foreach ($result as $r)
				$returnvalue[] = $r->export();
		}
		
		return $returnvalue;
	}
	
	/**
	 * Takes in a comma-separated string and returns an array of tags which have been trimmed and set to lower case
	 *
	 * @param string $string Comma-separated tag string
	 * @return array|false An array of strings, or false on failure
	 */
	function string_to_tag_array($string) {
		
		if (is_string($string)) {
			$ar = explode(",",$string);
			array_walk($ar,'trim');
			array_walk($ar,'strtolower');
			return $ar;
		}
		return false;
		
	}
	
	/**
	 * Takes a metadata array (which has all kinds of properties) and turns it into a simple array of strings 
	 *
	 * @param array $array Metadata array
	 * @return array Array of strings
	 */
	function metadata_array_to_values($array) {
		
		$valuearray = array();
		
		if (is_array($array)) {
			foreach($array as $element) {
				$valuearray[] = $element->value;
			}
		}
		
		return $valuearray;
		
	}
	
	/** Register the hook */
	register_plugin_hook("export", "all", "export_metadata_plugin_hook", 2);
?>