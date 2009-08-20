<?php
	/**
	 * Elgg metadata
	 * Functions to manage object metadata.
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd <info@elgg.com>

	 * @link http://elgg.org/
	 */

	/**
	 * ElggMetadata
	 * This class describes metadata that can be attached to ElggEntities.
	 * 
	 * @author Curverider Ltd <info@elgg.com>
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
				return $entity->canEditMetadata($this);
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
		
		/**
		 * Get a url for this item of metadata.
		 *
		 * @return string
		 */
		public function getURL() { return get_metadata_url($this->id); }
	
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
		$md_access = get_access_sql_suffix("m");

		return row_to_elggmetadata(get_data_row("SELECT m.*, n.string as name, v.string as value from {$CONFIG->dbprefix}metadata m JOIN {$CONFIG->dbprefix}entities e on e.guid = m.entity_guid JOIN {$CONFIG->dbprefix}metastrings v on m.value_id = v.id JOIN {$CONFIG->dbprefix}metastrings n on m.name_id = n.id where m.id=$id and $access and $md_access"));
	}
	
	/**
	 * Removes metadata on an entity with a particular name, optionally with a given value.
	 *
	 * @param int $entity_guid The entity GUID
	 * @param string $name The name of the metadata
	 * @param string $value The optional value of the item (useful for removing a single item in a multiple set)
	 * @return true|false Depending on success
	 */
	function remove_metadata($entity_guid, $name, $value = "") {
		
		global $CONFIG;
		$entity_guid = (int) $entity_guid;
		$name = sanitise_string($name);
		$value = sanitise_string($value);

		$query = "SELECT * from {$CONFIG->dbprefix}metadata WHERE entity_guid = $entity_guid and name_id=" . add_metastring($name);
		if ($value!="")
			$query .= " and value_id=" . add_metastring($value);
		
		if ($existing = get_data($query)) {
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
	function create_metadata($entity_guid, $name, $value, $value_type, $owner_guid, $access_id = ACCESS_PRIVATE, $allow_multiple = false)
	{
		global $CONFIG;

		$entity_guid = (int)$entity_guid;
		//$name = sanitise_string(trim($name));
		//$value = sanitise_string(trim($value));
		$value_type = detect_extender_valuetype($value, sanitise_string(trim($value_type)));
		$time = time();		
		$owner_guid = (int)$owner_guid;
		$allow_multiple = (boolean)$allow_multiple;
		
		if ($owner_guid==0) $owner_guid = get_loggedin_userid();
		
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
			
			if ($id!==false) {
				$obj = get_metadata($id);
				if (trigger_elgg_event('create', 'metadata', $obj)) {
					return true;
				} else {
					delete_metadata($id);
				}
			}
			
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

		// If memcached then we invalidate the cache for this entry
		static $metabyname_memcache;
		if ((!$metabyname_memcache) && (is_memcache_available()))
			$metabyname_memcache = new ElggMemcache('metabyname_memcache');
		if ($metabyname_memcache) $metabyname_memcache->delete("{$md->entity_guid}:{$md->name_id}");
		
		//$name = sanitise_string(trim($name));
		//$value = sanitise_string(trim($value));
		$value_type = detect_extender_valuetype($value, sanitise_string(trim($value_type)));
		
		$owner_guid = (int)$owner_guid;
		if ($owner_guid==0) $owner_guid = get_loggedin_userid();
		
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
		$result = update_data("UPDATE {$CONFIG->dbprefix}metadata set value_id='$value', value_type='$value_type', access_id=$access_id, owner_guid=$owner_guid where id=$id and name_id='$name'");
		if ($result!==false) {
			$obj = get_metadata($id);
			if (trigger_elgg_event('update', 'metadata', $obj)) {
				return true;
			} else {
				delete_metadata($id);
			}
		}
			
		return $result;
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
	function create_metadata_from_array($entity_guid, array $name_and_values, $value_type, $owner_guid, $access_id = ACCESS_PRIVATE, $allow_multiple = false)
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
		
		if ($metadata) {
			// Tidy up if memcache is enabled.
			static $metabyname_memcache;
			if ((!$metabyname_memcache) && (is_memcache_available()))
				$metabyname_memcache = new ElggMemcache('metabyname_memcache');
			if ($metabyname_memcache) $metabyname_memcache->delete("{$metadata->entity_guid}:{$metadata->name_id}");
			
			if (($metadata->canEdit()) && (trigger_elgg_event('delete', 'metadata', $metadata)))
				return delete_data("DELETE from {$CONFIG->dbprefix}metadata where id=$id");
		}
		
		return false;
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
		
		if (empty($meta_name)) return false;
		
		$entity_guid = (int)$entity_guid;
		$access = get_access_sql_suffix("e");
		$md_access = get_access_sql_suffix("m");
		
		// If memcache is available then cache this (cache only by name for now since this is the most common query)
		$meta = null;
		static $metabyname_memcache;
		if ((!$metabyname_memcache) && (is_memcache_available()))
			$metabyname_memcache = new ElggMemcache('metabyname_memcache');
		if ($metabyname_memcache) $meta = $metabyname_memcache->load("{$entity_guid}:{$meta_name}");
		if ($meta) return $meta;	

		$result = get_data("SELECT m.*, n.string as name, v.string as value from {$CONFIG->dbprefix}metadata m JOIN {$CONFIG->dbprefix}entities e ON e.guid = m.entity_guid JOIN {$CONFIG->dbprefix}metastrings v on m.value_id = v.id JOIN {$CONFIG->dbprefix}metastrings n on m.name_id = n.id where m.entity_guid=$entity_guid and m.name_id='$meta_name' and $access and $md_access", "row_to_elggmetadata");
		if (!$result) 
			return false;
			
		// Cache if memcache available
		if ($metabyname_memcache)
		{ 
			if (count($result) == 1) $r = $result[0]; else $r = $result;
			$metabyname_memcache->setDefaultExpiry(3600); // This is a bit of a hack - we shorten the expiry on object metadata so that it'll be gone in an hour. This means that deletions and more importantly updates will filter through eventually.
			$metabyname_memcache->save("{$entity_guid}:{$meta_name}", $r);
			
		}
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
		$md_access = get_access_sql_suffix("m");
		
		return get_data("SELECT m.*, n.string as name, v.string as value from {$CONFIG->dbprefix}metadata m JOIN {$CONFIG->dbprefix}entities e ON e.guid = m.entity_guid JOIN {$CONFIG->dbprefix}metastrings v on m.value_id = v.id JOIN {$CONFIG->dbprefix}metastrings n on m.name_id = n.id where m.entity_guid=$entity_guid and $access and $md_access", "row_to_elggmetadata");
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
	function find_metadata($meta_name = "", $meta_value = "", $entity_type = "", $entity_subtype = "", $limit = 10, $offset = 0, $order_by = "", $site_guid = 0)
	{
		global $CONFIG;
		
		$meta_n = get_metastring_id($meta_name);
		$meta_v = get_metastring_id($meta_value);
		
		$entity_type = sanitise_string($entity_type);
		$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
		$limit = (int)$limit;
		$offset = (int)$offset;
		if ($order_by == "") $order_by = "e.time_created desc";
		$order_by = sanitise_string($order_by);
		$site_guid = (int) $site_guid;
		if ($site_guid == 0)
			$site_guid = $CONFIG->site_guid;
			
			
		$where = array();
		
		if ($entity_type!="")
			$where[] = "e.type='$entity_type'";
		if ($entity_subtype)
			$where[] = "e.subtype=$entity_subtype";
		if ($meta_name!="") {
			if (!$meta_v) return false; // The value is set, but we didn't get a value... so something went wrong.
			$where[] = "m.name_id='$meta_n'";
		}
		if ($meta_value!="") {
			if (!$meta_v) return false; // The value is set, but we didn't get a value... so something went wrong.
			$where[] = "m.value_id='$meta_v'";
		}
		if ($site_guid > 0)
			$where[] = "e.site_guid = {$site_guid}";
		
		$query = "SELECT m.*, n.string as name, v.string as value from {$CONFIG->dbprefix}entities e JOIN {$CONFIG->dbprefix}metadata m on e.guid = m.entity_guid JOIN {$CONFIG->dbprefix}metastrings v on m.value_id = v.id JOIN {$CONFIG->dbprefix}metastrings n on m.name_id = n.id where";
		foreach ($where as $w)
			$query .= " $w and ";
		$query .= get_access_sql_suffix("e"); // Add access controls
		$query .= ' and ' . get_access_sql_suffix("m"); // Add access controls
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
	 * @param true|false $count If set to true, returns the total number of entities rather than a list. (Default: false)
	 * 
	 * @return int|array A list of entities, or a count if $count is set to true
	 */
	function get_entities_from_metadata($meta_name, $meta_value = "", $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "", $site_guid = 0, $count = false)
	{
		global $CONFIG;
		
		$meta_n = get_metastring_id($meta_name);
		$meta_v = get_metastring_id($meta_value);
			
		$entity_type = sanitise_string($entity_type);
		$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
		$limit = (int)$limit;
		$offset = (int)$offset;
		if ($order_by == "") 
			$order_by = "e.time_created desc";
		else
			$order_by = "e.time_created, {$order_by}";
		$order_by = sanitise_string($order_by);
		$site_guid = (int) $site_guid;
		if ((is_array($owner_guid) && (count($owner_guid)))) {
			foreach($owner_guid as $key => $guid) {
				$owner_guid[$key] = (int) $guid;
			}
		} else {
			$owner_guid = (int) $owner_guid;
		}
		if ($site_guid == 0)
			$site_guid = $CONFIG->site_guid;
			
		//$access = get_access_list();
			
		$where = array();
		
		if ($entity_type!=="")
			$where[] = "e.type='$entity_type'";
		if ($entity_subtype)
			$where[] = "e.subtype=$entity_subtype";
		if ($meta_name!=="")
			$where[] = "m.name_id='$meta_n'";
		if ($meta_value!=="")
			$where[] = "m.value_id='$meta_v'";
		if ($site_guid > 0)
			$where[] = "e.site_guid = {$site_guid}";
		if (is_array($owner_guid)) {
			$where[] = "e.container_guid in (".implode(",",$owner_guid).")";
		} else if ($owner_guid > 0)
			$where[] = "e.container_guid = {$owner_guid}";
		
		if (!$count) {
			$query = "SELECT distinct e.* "; 
		} else {
			$query = "SELECT count(distinct e.guid) as total ";
		}
			
		$query .= "from {$CONFIG->dbprefix}entities e JOIN {$CONFIG->dbprefix}metadata m on e.guid = m.entity_guid where";
		foreach ($where as $w)
			$query .= " $w and ";
		$query .= get_access_sql_suffix("e"); // Add access controls
		$query .= ' and ' . get_access_sql_suffix("m"); // Add access controls
		
		if (!$count) {
			$query .= " order by $order_by limit $offset, $limit"; // Add order and limit
			return get_data($query, "entity_row_to_elggstar");
		} else {
			if ($row = get_data_row($query))
				return $row->total;
		}
		return false;
	}
	
	/**
	 * Return a list of entities suitable for display based on the given search criteria.
	 * 
	 * @see elgg_view_entity_list
	 * 
	 * @param mixed $meta_name Metadata name to search on
	 * @param mixed $meta_value The value to match, optionally
	 * @param string $entity_type The type of entity to look for, eg 'site' or 'object'
	 * @param string $entity_subtype The subtype of the entity
	 * @param int $limit Number of entities to display per page
	 * @param true|false $fullview Whether or not to display the full view (default: true)
	 * @param true|false $viewtypetoggle Whether or not to allow users to toggle to the gallery view. Default: true
	 * @param true|false $pagination Display pagination? Default: true
	 * 
	 * @return string A list of entities suitable for display
	 */
	function list_entities_from_metadata($meta_name, $meta_value = "", $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $fullview = true, $viewtypetoggle = true, $pagination = true) {
		
		$offset = (int) get_input('offset');
		$limit = (int) $limit;
		$count = get_entities_from_metadata($meta_name, $meta_value, $entity_type, $entity_subtype, $owner_guid, $limit, $offset, "", 0, true);
		$entities = get_entities_from_metadata($meta_name, $meta_value, $entity_type, $entity_subtype, $owner_guid, $limit, $offset, "", 0, false);
		
		return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, $viewtypetoggle, $pagination);
		
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
	 * @param true|false $count If set to true, returns the total number of entities rather than a list. (Default: false)
	 * @param string $meta_array_operator Operator used for joining the metadata array together
	 * @return int|array List of ElggEntities, or the total number if count is set to false
	 */
	function get_entities_from_metadata_multi($meta_array, $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "", $site_guid = 0, $count = false, $meta_array_operator = 'and')
	{
		global $CONFIG;
		
		if (!is_array($meta_array) || sizeof($meta_array) == 0) {
			return false;
		}
		
		$where = array();
		
		$mindex = 1;
		$join = "";
		$metawhere = array();
		$meta_array_operator = sanitise_string($meta_array_operator);
		foreach($meta_array as $meta_name => $meta_value) {
			$meta_n = get_metastring_id($meta_name);
			$meta_v = get_metastring_id($meta_value);
			$join .= " JOIN {$CONFIG->dbprefix}metadata m{$mindex} on e.guid = m{$mindex}.entity_guid "; 
			/*if ($meta_name!=="")
				$where[] = "m{$mindex}.name_id='$meta_n'";
			if ($meta_value!=="")
				$where[] = "m{$mindex}.value_id='$meta_v'";*/
			$metawhere[] = "(m{$mindex}.name_id='$meta_n' AND m{$mindex}.value_id='$meta_v')";
			$mindex++;
		}
		$where[] = "(".implode($meta_array_operator, $metawhere).")";
			
		$entity_type = sanitise_string($entity_type);
		$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
		$limit = (int)$limit;
		$offset = (int)$offset;
		if ($order_by == "") $order_by = "e.time_created desc";
		$order_by = sanitise_string($order_by);
		if ((is_array($owner_guid) && (count($owner_guid)))) {
			foreach($owner_guid as $key => $guid) {
				$owner_guid[$key] = (int) $guid;
			}
		} else {
			$owner_guid = (int) $owner_guid;
		}
		
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
		if (is_array($owner_guid)) {
			$where[] = "e.container_guid in (".implode(",",$owner_guid).")";
		} else if ($owner_guid > 0)
			$where[] = "e.container_guid = {$owner_guid}";
		//if ($owner_guid > 0)
		//	$where[] = "e.container_guid = {$owner_guid}";
		
		if ($count) {
			$query = "SELECT count(distinct e.guid) as total ";
		} else {
			$query = "SELECT distinct e.* "; 
		}
			
		$query .= " from {$CONFIG->dbprefix}entities e {$join} where";
		foreach ($where as $w)
			$query .= " $w and ";
		$query .= get_access_sql_suffix("e"); // Add access controls
	
		$mindex = 1;
		foreach($meta_array as $meta_name => $meta_value) {
			$query .= ' and ' . get_access_sql_suffix("m{$mindex}"); // Add access controls
			$mindex++;
		}
		
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
	 * Returns a viewable list of entities based on the given search criteria.
	 *
	 * @see elgg_view_entity_list
	 * 
	 * @param array $meta_array Array of 'name' => 'value' pairs
	 * @param string $entity_type The type of entity to look for, eg 'site' or 'object'
	 * @param string $entity_subtype The subtype of the entity.
	 * @param int $limit 
	 * @param int $offset
	 * @param string $order_by Optional ordering.
	 * @param true|false $fullview Whether or not to display the full view (default: true)
	 * @param true|false $viewtypetoggle Whether or not to allow users to toggle to the gallery view. Default: true
	 * @param true|false $pagination Display pagination? Default: true
	 * @return string List of ElggEntities suitable for display
	 */
	function list_entities_from_metadata_multi($meta_array, $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $fullview = true, $viewtypetoggle = true, $pagination = true) {
		
		$offset = (int) get_input('offset');
		$limit = (int) $limit;
		$count = get_entities_from_metadata_multi($meta_array, $entity_type, $entity_subtype, $owner_guid, $limit, $offset, "", $site_guid, true);
		$entities = get_entities_from_metadata_multi($meta_array, $entity_type, $entity_subtype, $owner_guid, $limit, $offset, "", $site_guid, false);
	
		return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, $viewtypetoggle, $pagination);
		
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
	 * Clear all annotations belonging to a given owner_guid
	 *
	 * @param int $owner_guid The owner
	 */
	function clear_metadata_by_owner($owner_guid)
	{
		global $CONFIG;
		
		$owner_guid = (int)$owner_guid;
		
		$metas = get_data("SELECT id from {$CONFIG->dbprefix}metadata WHERE owner_guid=$owner_guid");
		$deleted = 0;
		
		foreach ($metas as $id)
		{
			if (delete_metadata($id->id)) // Is this the best way?
				$deleted++;
		}
		
		return $deleted;
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
			$ar = array_map('trim', $ar); // trim blank spaces
			$ar = array_map('elgg_strtolower', $ar); // make lower case : [Marcus Povey 20090605 - Using mb wrapper function using UTF8 safe function where available]
			$ar = array_filter($ar, 'is_not_null'); // Remove null values
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
	
	/**
	 * Get the URL for this item of metadata, by default this links to the export handler in the current view.
	 *
	 * @param int $id
	 */
	function get_metadata_url($id)
	{
		$id = (int)$id;
		
		if ($extender = get_metadata($id)) {
			return get_extender_url($extender);	
		} 
		return false;
	}
	
	/**
	 * Mark entities with a particular type and subtype as having access permissions
	 * that can be changed independently from their parent entity
	 *
	 * @param string $type The type - object, user, etc
	 * @param string $subtype The subtype; all subtypes by default
	 */
	function register_metadata_as_independent($type, $subtype = '*') {
		global $CONFIG;
		if (!isset($CONFIG->independents)) $CONFIG->independents = array();
		$CONFIG->independents[$type][$subtype] = true;
	}
	
	/**
	 * Determines whether entities of a given type and subtype should not change
	 * their metadata in line with their parent entity 
	 *
	 * @param string $type The type - object, user, etc
	 * @param string $subtype The entity subtype
	 * @return true|false
	 */
	function is_metadata_independent($type, $subtype) {
		global $CONFIG;
		if (empty($CONFIG->independents)) return false;
		if (!empty($CONFIG->independents[$type][$subtype])
			|| !empty($CONFIG->independents[$type]['*'])) return true;
		return false;
	}
	
	/**
	 * When an entity is updated, resets the access ID on all of its child metadata
	 *
	 * @param string $event The name of the event
	 * @param string $object_type The type of object
	 * @param ElggEntity $object The entity itself
	 */
	function metadata_update($event, $object_type, $object) {
		if ($object instanceof ElggEntity) {
			if (!is_metadata_independent($object->getType(), $object->getSubtype())) {
				global $CONFIG;
				$access_id = (int) $object->access_id;
				$guid = (int) $object->getGUID();
				update_data("update {$CONFIG->dbprefix}metadata set access_id = {$access_id} where entity_guid = {$guid}");
			}
		}	
		return true;	
	}
	
	/**
	 * Register a metadata url handler.
	 *
	 * @param string $function_name The function.
	 * @param string $extender_name The name, default 'all'.
	 */
	function register_metadata_url_handler($function_name, $extender_name = "all") {
		return register_extender_url_handler($function_name, 'metadata', $extender_name);
	}
		
	/** Register the hook */
	register_plugin_hook("export", "all", "export_metadata_plugin_hook", 2);
	/** Call a function whenever an entity is updated **/
	register_elgg_event_handler('update','all','metadata_update');
	
?>
