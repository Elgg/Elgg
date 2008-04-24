<?php
	/**
	 * Elgg annotations
	 * Functions to manage object annotations.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

    /**
     * Include the ElggExtender superclass
     * 
     */
	require_once('extender.php');

	/**
	 * ElggAnnotation
	 * 
	 * An annotation is similar to metadata each entity can contain more than one of each annotation.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 */
	class ElggAnnotation extends ElggExtender implements Exportable
	{
		
		/**
		 * Construct a new site object, optionally from a given id value or db row.
		 *
		 * @param mixed $id
		 */
		function __construct($id = null) 
		{
			$this->attributes = array();
			
			if (!empty($id)) {
				if ($id instanceof stdClass)
					$annotation = $id;
				else
					$annotation = get_annotation($id);
				
				if ($annotation) {
					$objarray = (array) $annotation;
					foreach($objarray as $key => $value) {
						$this->attributes[$key] = $value;
					}
					$this->attributes['type'] = "annotation";
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
		 * @return void
		 */
		function __set($name, $value) {
			return $this->set($name, $value);
		}		
		
		/**
		 * Save this instance
		 *
		 * @return int an object id
		 */
		function save()
		{
			if ($this->id > 0)
				return update_annotation($this->id, $this->name, $this->value, $this->value_type, $this->owner_guid, $this->access_id);
			else
			{ 
				$this->id = create_annotation($this->entity_guid, $this->name, $this->value, $this->value_type, $this->owner_guid, $this->access_id);
				if (!$this->id) throw new IOException("Unable to save new ElggAnnotation");
				return $this->id;
			}
		}
		
		/**
		 * Delete a given site.
		 */
		function delete() 
		{ 
			return delete_annotation($this->id); 
		}

		/**
		 * Export this object
		 *
		 * @return array
		 */
		public function export()
		{
			$type = "annotation";
			$uuid = guid_to_uuid($this->entity_guid). $type . "/{$this->id}/";
			
			$meta = new ODDMetadata($uuid, guid_to_uuid($this->entity_guid), $this->attributes[$name], $this->attributes['value'], $type, guid_to_uuid($this->owner_guid));
			$meta->setAttribute('published', date("r", $this->time_created));
			
			return $meta;
		}
	}
	
	/**
	 * Convert a database row to a new ElggAnnotation
	 *
	 * @param stdClass $row
	 * @return stdClass or ElggAnnotation
	 */
	function row_to_elggannotation($row) 
	{
		if (!($row instanceof stdClass))
			return $row;
			
		return new ElggAnnotation($row);
	}
	
	/**
	 * Get a specific annotation.
	 *
	 * @param int $annotation_id
	 */
	function get_annotation($annotation_id)
	{
		global $CONFIG;
		
		$annotation_id = (int) $annotation_id;
		$access = get_access_list();
		
		return row_to_elggannotation(get_data_row("select a.*, n.string as name, v.string as value from {$CONFIG->dbprefix}annotations a JOIN {$CONFIG->dbprefix}metastrings n on a.name_id = n.id JOIN {$CONFIG->dbprefix}metastrings v on a.value_id = v.id where a.id=$annotation_id and (a.access_id in {$access} or (a.access_id = 0 and a.owner_guid = {$_SESSION['id']}))"));			
	}
	
	/**
	 * Create a new annotation.
	 *
	 * @param int $entity_guid
	 * @param string $name
	 * @param string $value
	 * @param string $value_type
	 * @param int $owner_guid
	 * @param int $access_id
	 */
	function create_annotation($entity_guid, $name, $value, $value_type, $owner_guid, $access_id = 0)
	{
		global $CONFIG;

		$entity_guid = (int)$entity_guid;
		//$name = sanitise_string(trim($name));
		//$value = sanitise_string(trim($value));
		$value_type = detect_extender_valuetype($value, sanitise_string(trim($value_type)));
		
		$owner_guid = (int)$owner_guid;
		if ($owner_guid==0) $owner_guid = $_SESSION['id'];
		
		$access_id = (int)$access_id;
		
		$time = time();
		
		// Add the metastring
		$value = add_metastring($value);
		if (!$value) return false;
		
		$name = add_metastring($name);
		if (!$name) return false;
		
		// If ok then add it
		return insert_data("INSERT into {$CONFIG->dbprefix}annotations (entity_guid, name_id, value_id, value_type, owner_guid, time_created, access_id) VALUES ($entity_guid,'$name',$value,'$value_type', $owner_guid, $time, $access_id)");
	}
	
	/**
	 * Update an annotation.
	 *
	 * @param int $annotation_id
	 * @param string $name
	 * @param string $value
	 * @param string $value_type
	 * @param int $owner_guid
	 * @param int $access_id
	 */
	function update_annotation($annotation_id, $name, $value, $value_type, $owner_guid, $access_id)
	{
		global $CONFIG;

		$annotation_id = (int)$annotation_id;
		$name = sanitise_string(trim($name));
		$value = sanitise_string(trim($value));
		$value_type = detect_extender_valuetype($value, sanitise_string(trim($value_type)));
		
		$owner_guid = (int)$owner_guid;
		if ($owner_guid==0) $owner_guid = $_SESSION['id'];
		
		$access_id = (int)$access_id;
		
		$access = get_access_list();
		
		// Add the metastring
		$value = add_metastring($value);
		if (!$value) return false;
		
		$name = add_metastring($name);
		if (!$name) return false;
		
		// If ok then add it		
		return update_data("UPDATE {$CONFIG->dbprefix}annotations set value_id='$value', value_type='$value_type', access_id=$access_id, owner_guid=$owner_guid where id=$annotation_id and name_id='$name' and (access_id in {$access} or (access_id = 0 and owner_guid = {$_SESSION['id']}))");
	}
	
	/**
	 * Get a list of annotations for a given object/user/annotation type.
	 *
	 * @param int $entity_guid
	 * @param string $entity_type
	 * @param string $entity_subtype
	 * @param string $name
	 * @param mixed $value
	 * @param int $owner_id
	 * @param int $limit
	 * @param int $offset
	 * @param string $order_by
	 */
	function get_annotations($entity_guid = 0, $entity_type = "", $entity_subtype = "", $name = "", $value = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "a.time_created desc")
	{
		global $CONFIG;
		
		$entity_guid = (int)$entity_guid;
		$entity_type = sanitise_string($entity_type);
		$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
		$name = get_metastring_id($name);
		if ($value != "") $value = get_metastring_id($value);
		$owner_guid = (int)$owner_guid;
		$limit = (int)$limit;
		$offset = (int)$offset;
		$order_by = sanitise_string($order_by);
		
		$access = get_access_list();
		
		$where = array();
		
		if ($entity_guid != 0)
			$where[] = "a.entity_guid=$entity_guid";
			
		if ($entity_type != "")
			$where[] = "e.type='$entity_type'";
			
		if ($entity_subtype != "")
			$where[] = "e.subtype='$entity_subtype'";
		
		if ($owner_guid != 0)
			$where[] = "a.owner_guid=$owner_guid";
			
		if ($name != "")
			$where[] = "a.name_id='$name'";
			
		if ($value != "")
			$where[] = "a.value_id='$value'";
		
		$query = "SELECT a.*, n.string as name, v.string as value from {$CONFIG->dbprefix}annotations a JOIN {$CONFIG->dbprefix}entities e on a.entity_guid = e.guid JOIN {$CONFIG->dbprefix}metastrings v on a.value_id=v.id JOIN {$CONFIG->dbprefix}metastrings n on a.name_id = n.id where ";
		foreach ($where as $w)
			$query .= " $w and ";
		$query .= " (a.access_id in {$access} or (a.access_id = 0 and a.owner_guid = {$_SESSION['id']}))"; // Add access controls
		$query .= " order by $order_by limit $offset,$limit"; // Add order and limit
		return get_data($query, "row_to_elggannotation");
		
	}

	/**
	 * Return the sum of a given integer annotation.
	 * 
	 * @param $entity_guid int
	 * @param $entity_type string
	 * @param $entity_subtype string
	 * @param $name string
	 */
	function get_annotations_sum($entity_guid, $entity_type = "", $entity_subtype = "", $name = "")
	{
		return __get_annotations_calculate_x("sum", $entity_guid, $entity_type, $entity_subtype, $name);
	}
	
	/**
	 * Return the max of a given integer annotation.
	 * 
	 * @param $entity_guid int
	 * @param $entity_type string
	 * @param $entity_subtype string
	 * @param $name string
	 */
	function get_annotations_max($entity_guid, $entity_type = "", $entity_subtype = "", $name = "")
	{
		return __get_annotations_calculate_x("max", $entity_guid, $entity_type, $entity_subtype, $name);
	}
	
	/**
	 * Return the minumum of a given integer annotation.
	 * 
	 * @param $entity_guid int
	 * @param $entity_type string
	 * @param $entity_subtype string
	 * @param $name string
	 */
	function get_annotations_min($entity_guid, $entity_type = "", $entity_subtype = "", $name = "")
	{
		return __get_annotations_calculate_x("min", $entity_guid, $entity_type, $entity_subtype, $name);
	}
	
	/**
	 * Return the average of a given integer annotation.
	 * 
	 * @param $entity_guid int
	 * @param $entity_type string
	 * @param $entity_subtype string
	 * @param $name string
	 */
	function get_annotations_avg($entity_guid, $entity_type = "", $entity_subtype = "", $name = "")
	{
		return __get_annotations_calculate_x("avg", $entity_guid, $entity_type, $entity_subtype, $name);
	}
	
	/**
	 * Count the number of annotations based on search parameters
	 *
	 * @param int $entity_guid
	 * @param string $entity_type
	 * @param string $entity_subtype
	 * @param string $name
	 */
	function count_annotations($entity_guid = 0, $entity_type = "", $entity_subtype = "", $name = "", $value = "", $value_type = "", $owner_guid = 0)
	{
		return __get_annotations_calculate_x("count", $entity_guid, $entity_type, $entity_subtype, $name);
	}
	
	/**
	 * Perform a mathmatical calculation on integer annotations.
	 * 
	 * @param $sum string
	 * @param $entity_id int
	 * @param $entity_type string
	 * @param $entity_subtype string
	 * @param $name string
	 */
	function __get_annotations_calculate_x($sum = "avg", $entity_guid, $entity_type = "", $entity_subtype = "", $name = "")
	{
		global $CONFIG;
		
		$sum = sanitise_string($sum);
		$entity_guid = (int)$entity_id;
		$entity_type = sanitise_string($entity_type);
		$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
		$name = get_metastring_id($name);
		$access = get_access_list();
		
		$where = array();
		
		if ($entity_guid)
			$where[] = "e.guid=$entity_guid";
		if ($entity_type!="")
			$where[] = "e.type='$entity_type'";
		if ($entity_subtype)
			$where[] = "e.subtype=$entity_subtype";
		if ($name!="")
			$where[] = "a.name_id='$name'";
			
		$where[] = "a.value_type='integer'"; // Limit on integer types
		
		$query = "SELECT $sum(ms.string) as sum from {$CONFIG->dbprefix}annotations a JOIN {$CONFIG->dbprefix}entities e on a.entity_guid = e.guid JOIN {$CONFIG->dbprefix}metastrings ms on a.value=ms.id WHERE ";
		foreach ($where as $w)
			$query .= " $w and ";
		$query .= " (a.access_id in {$access} or (a.access_id = 0 and a.owner_guid = {$_SESSION['id']}))"; // now add access
		
		$row = get_data_row($query);
		
		if ($row)
			return $row->sum;
			
		return false;
	}
	
	/**
	 * Delete a given annotation.
	 * 
	 * @param $id int The id
	 */
	function delete_annotation($id)
	{
		global $CONFIG;

		$id = (int)$id;
		
		$access = get_access_list();
		
		return delete_data("DELETE from {$CONFIG->dbprefix}annotations  where id=$id and (access_id in {$access} or (access_id = 0 and owner_guid = {$_SESSION['id']}))");
	}
	
	/**
	 * Clear all the annotations for a given entity, assuming you have access to that metadata.
	 * 
	 * @param int $guid
	 */
	function clear_annotations($guid, $name = "")
	{
		global $CONFIG;
		
		$guid = (int)$guid;
		$name = get_metastring_id($name);
		
		$entity_guid = (int) $guid;
		if ($entity = get_entity($entity_guid)) {
			if ($entity->canEdit()) {
		
				$where = array();
				
				if ($name != "")
					$where[] = " name_id='$name'";
				
				$query = "DELETE from {$CONFIG->dbprefix}annotations where entity_guid=$guid "; 
				foreach ($where as $w)
					$query .= " and $w";
				
				return delete_data($query);
		
			}
		}
	}
	
	/**
	 * Handler called by trigger_plugin_hook on the "export" event.
	 */
	function export_annotation_plugin_hook($hook, $entity_type, $returnvalue, $params)
	{
		// Sanity check values
		if ((!is_array($params)) && (!isset($params['guid'])))
			throw new InvalidParameterException("GUID has not been specified during export, this should never happen.");
			
		if (!is_array($returnvalue))
			throw new InvalidParameterException("Entity serialisation function passed a non-array returnvalue parameter");
		
		$guid = (int)$params['guid'];
		$name = $params['name'];	
		
		$result = get_annotations($guid); 
				
		if ($result)
		{
			foreach ($result as $r)
				$returnvalue[] = $r->export();
		}
		
		return $returnvalue;
	}
	
	/** Register the hook */
	register_plugin_hook("export", "all", "export_annotation_plugin_hook", 2);
?>