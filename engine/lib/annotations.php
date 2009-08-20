<?php
	/**
	 * Elgg annotations
	 * Functions to manage object annotations.
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd <info@elgg.com>

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
	 * @author Curverider Ltd <info@elgg.com>
	 */
	class ElggAnnotation extends ElggExtender
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
				if (!$this->id) throw new IOException(sprintf(elgg_new('IOException:UnableToSaveNew'), get_class()));
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
		 * Get a url for this annotation.
		 *
		 * @return string
		 */
		public function getURL() { return get_annotation_url($this->id); }
	
		// SYSTEM LOG INTERFACE ////////////////////////////////////////////////////////////

		/**
		 * For a given ID, return the object associated with it.
		 * This is used by the river functionality primarily.
		 * This is useful for checking access permissions etc on objects.
		 */
		public function getObjectFromID($id) { return get_annotation($id); }
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
		$access = get_access_sql_suffix("a");
		
		return row_to_elggannotation(get_data_row("SELECT a.*, n.string as name, v.string as value from {$CONFIG->dbprefix}annotations a JOIN {$CONFIG->dbprefix}metastrings n on a.name_id = n.id JOIN {$CONFIG->dbprefix}metastrings v on a.value_id = v.id where a.id=$annotation_id and $access"));			
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
	function create_annotation($entity_guid, $name, $value, $value_type, $owner_guid, $access_id = ACCESS_PRIVATE)
	{
		global $CONFIG;

		$result = false;
		
		$entity_guid = (int)$entity_guid;
		//$name = sanitise_string(trim($name));
		//$value = sanitise_string(trim($value));
		$value_type = detect_extender_valuetype($value, sanitise_string(trim($value_type)));
		
		$owner_guid = (int)$owner_guid;
		if ($owner_guid==0) $owner_guid = get_loggedin_userid();
		
		$access_id = (int)$access_id;
		
		$time = time();
		
		// Add the metastring
		$value = add_metastring($value);
		if (!$value) return false;
		
		$name = add_metastring($name);
		if (!$name) return false;
		
		$entity = get_entity($entity_guid);
		
		if (trigger_elgg_event('annotate',$entity->type,$entity)) {
			system_log($entity, 'annotate');
			
			// If ok then add it
			$result = insert_data("INSERT into {$CONFIG->dbprefix}annotations (entity_guid, name_id, value_id, value_type, owner_guid, time_created, access_id) VALUES ($entity_guid,'$name',$value,'$value_type', $owner_guid, $time, $access_id)");
			if ($result!==false) {
				$obj = get_annotation($result);
				if (trigger_elgg_event('create', 'annotation', $obj)) {
					return true;
				} else {
					delete_annotation($result);
				}
			}
		}
		
		return $result;
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
		$name = (trim($name));
		$value = (trim($value));
		$value_type = detect_extender_valuetype($value, sanitise_string(trim($value_type)));
		
		$owner_guid = (int)$owner_guid;
		if ($owner_guid==0) $owner_guid = get_loggedin_userid();
		
		$access_id = (int)$access_id;
		
		$access = get_access_sql_suffix();
		
		// Add the metastring
		$value = add_metastring($value);
		if (!$value) return false;
		
		$name = add_metastring($name);
		if (!$name) return false;
		
		// If ok then add it		
		$result = update_data("UPDATE {$CONFIG->dbprefix}annotations set value_id='$value', value_type='$value_type', access_id=$access_id, owner_guid=$owner_guid where id=$annotation_id and name_id='$name' and $access");
		if ($result!==false) {
			$obj = get_annotation($annotation_id);
			if (trigger_elgg_event('update', 'annotation', $obj)) {
				return true;
			} else {
				delete_annotation($annotation_id);
			}
		}
		
		return $result;
	}
	
	/**
	 * Get a list of annotations for a given object/user/annotation type.
	 *
	 * @param int|array $entity_guid
	 * @param string $entity_type
	 * @param string $entity_subtype
	 * @param string $name
	 * @param mixed $value
	 * @param int|array $owner_guid
	 * @param int $limit
	 * @param int $offset
	 * @param string $order_by
	 */
	function get_annotations($entity_guid = 0, $entity_type = "", $entity_subtype = "", $name = "", 
	$value = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "asc", $timelower = 0, $timeupper = 0)
	{
		global $CONFIG;
		
		$timelower = (int) $timelower;
		$timeupper = (int) $timeupper;
		
		if (is_array($entity_guid)) {
			if (sizeof($entity_guid) > 0) {
				foreach($entity_guid as $key => $val) {
					$entity_guid[$key] = (int) $val;			
				}
			} else {
				$entity_guid = 0;
			}
		} else {
			$entity_guid = (int)$entity_guid;
		}
		$entity_type = sanitise_string($entity_type);
		$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
		if ($name)
		{
			$name = get_metastring_id($name);
		
			if ($name === false)
				$name = 0;
		}
		if ($value != "") $value = get_metastring_id($value);
		if (is_array($owner_guid)) {
			if (sizeof($owner_guid) > 0) {
				foreach($owner_guid as $key => $val) {
					$owner_guid[$key] = (int) $val;
				}
			} else {
				$owner_guid = 0;
			}
		} else {
			$owner_guid = (int)$owner_guid;
		}
		$limit = (int)$limit;
		$offset = (int)$offset;
		if($order_by == 'asc')
		    $order_by = "a.time_created asc";
		    
		if($order_by == 'desc')
		    $order_by = "a.time_created desc";
		
		$where = array();
		
		if ($entity_guid != 0 && !is_array($entity_guid)) {
			$where[] = "a.entity_guid=$entity_guid";
		} else if (is_array($entity_guid)) {
			$where[] = "a.entity_guid in (". implode(",",$entity_guid) . ")";
		}
			
		if ($entity_type != "")
			$where[] = "e.type='$entity_type'";
			
		if ($entity_subtype != "")
			$where[] = "e.subtype='$entity_subtype'";
		
		if ($owner_guid != 0 && !is_array($owner_guid)) {
			$where[] = "a.owner_guid=$owner_guid";
		} else {
			if (is_array($owner_guid))
				$where[] = "a.owner_guid in (" . implode(",",$owner_guid) . ")";
		}
			
		if ($name !== "")
			$where[] = "a.name_id='$name'";
			
		if ($value != "")
			$where[] = "a.value_id='$value'";
			
		if ($timelower)
			$where[] = "a.time_created >= {$timelower}";
		if ($timeupper)
			$where[] = "a.time_created <= {$timeupper}";
			
		$query = "SELECT a.*, n.string as name, v.string as value from {$CONFIG->dbprefix}annotations a JOIN {$CONFIG->dbprefix}entities e on a.entity_guid = e.guid JOIN {$CONFIG->dbprefix}metastrings v on a.value_id=v.id JOIN {$CONFIG->dbprefix}metastrings n on a.name_id = n.id where ";
		foreach ($where as $w)
			$query .= " $w and ";
		$query .= get_access_sql_suffix("a"); // Add access controls
		$query .= " order by $order_by limit $offset,$limit"; // Add order and limit
		
		return get_data($query, "row_to_elggannotation");
		
	}
	
	/**
	 * Return a list of entities which are annotated with a specific annotation. 
	 * These can be ordered by when the annotation was created/updated.
	 * 
	 * @param string $entity_type Type of entity.
	 * @param string $entity_subtype Subtype of entity.
	 * @param string $name Name of annotation.
	 * @param string $value Value of annotation.
	 * @param int $owner_guid Owner.
	 * @param int $group_guid Group container. Currently this is only supported if $entity_type == 'object'
	 * @param int $limit Maximum number of results to return.
	 * @param int $offset Place to start.
	 * @param string $order_by How to order results.
	 * @param boolean $count Whether to count entities rather than return them
	 * @param int $timelower The earliest time the annotation can have been created. Default: all
	 * @param int $timeupper The latest time the annotation can have been created. Default: all
	 */
	function get_entities_from_annotations($entity_type = "", $entity_subtype = "", $name = "", $value = "", $owner_guid = 0, $group_guid = 0, $limit = 10, $offset = 0, $order_by = "asc", $count = false, $timelower = 0, $timeupper = 0)
	{
		global $CONFIG;
		
		$entity_type = sanitise_string($entity_type);
		$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
		$timelower = (int) $timelower;
		$timeupper = (int) $timeupper;
		
		if ($name)
		{
			$name = get_metastring_id($name);
		
			if ($name === false)
				$name = 0;
		}
		if ($value != "") $value = get_metastring_id($value);
		if (is_array($owner_guid)) {
			if (sizeof($owner_guid) > 0) {
				foreach($owner_guid as $key => $val) {
					$owner_guid[$key] = (int) $val;
				}
			} else {
				$owner_guid = 0;
			}
		} else {
			$owner_guid = (int)$owner_guid;
		}
		$group_guid = (int)$group_guid;
		
		$limit = (int)$limit;
		$offset = (int)$offset;
		if($order_by == 'asc')
		    $order_by = "maxtime asc";
		    
		if($order_by == 'desc')
		    $order_by = "maxtime desc";
		
		$where = array();
		
		if ($entity_type != "")
			$where[] = "e.type='$entity_type'";
			
		if ($entity_subtype != "")
			$where[] = "e.subtype='$entity_subtype'";
		
		if ($owner_guid != 0 && !is_array($owner_guid)) {
			$where[] = "a.owner_guid=$owner_guid";
		} else {
			if (is_array($owner_guid))
				$where[] = "a.owner_guid in (" . implode(",",$owner_guid) . ")";
		}
		
		if (($group_guid != 0) && ($entity_type=='object'))
			$where[] = "e.container_guid = $group_guid";
			
		if ($name !== "")
			$where[] = "a.name_id='$name'";
			
		if ($value != "")
			$where[] = "a.value_id='$value'";

		if ($timelower)
			$where[] = "a.time_created >= {$timelower}";
		if ($timeupper)
			$where[] = "a.time_created <= {$timeupper}";
		
		if ($count) {
			$query = "SELECT count(distinct e.guid) as total ";
		} else {
			$query = "SELECT e.*, max(a.time_created) as maxtime ";			
		}
		$query .= "from {$CONFIG->dbprefix}annotations a JOIN {$CONFIG->dbprefix}entities e on e.guid = a.entity_guid ";
		if ($value != "")
			$query .= " JOIN {$CONFIG->dbprefix}metastrings v on a.value_id=v.id";
		
		if (($group_guid != 0) && ($entity_type=='object')) $query .= "JOIN {$CONFIG->dbprefix}objects_entity o on o.guid = e.guid";
		$query .= " where";
		    
		foreach ($where as $w)
			$query .= " $w and ";
		$query .= get_access_sql_suffix("a"); // Add access controls
		$query .= ' and ' . get_access_sql_suffix("e"); // Add access controls
		
		if ($count) {
			$row = get_data_row($query);
			return $row->total;
		} else {
			$query .= " group by a.entity_guid order by $order_by limit $offset,$limit"; // Add order and limit
			return get_data($query, "entity_row_to_elggstar");
		}    
	}
	
	/**
	 * Lists entities
	 *
	 * @see elgg_view_entity_list
	 * 
	 * @param string $entity_type Type of entity.
	 * @param string $entity_subtype Subtype of entity.
	 * @param string $name Name of annotation.
	 * @param string $value Value of annotation.
	 * @param int $limit Maximum number of results to return.
	 * @param int $owner_guid Owner.
	 * @param int $group_guid Group container. Currently this is only supported if $entity_type == 'object'
	 * @param boolean $asc Whether to list in ascending or descending order (default: desc)
	 * @param boolean $fullview Whether to display the entities in full
	 * @param boolean $viewtypetoggle Determines whether or not the 'gallery' view can be displayed (default: no)
	 * @return string Formatted entity list
	 */
	function list_entities_from_annotations($entity_type = "", $entity_subtype = "", $name = "", $value = "", $limit = 10, $owner_guid = 0, $group_guid = 0, $asc = false, $fullview = true, $viewtypetoggle = false) {
		
		if ($asc) {
			$asc = "asc";
		} else {
			$asc = "desc";
		}
		$count = get_entities_from_annotations($entity_type, $entity_subtype, $name, $value, $owner_guid, $group_guid, null, null, $asc, true);
		$offset = (int) get_input("offset",0);
		$entities = get_entities_from_annotations($entity_type, $entity_subtype, $name, $value, $owner_guid, $group_guid, $limit, $offset, $asc);

		return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, $viewtypetoggle);
		
	}
	
	/**
	 * Returns a human-readable list of annotations on a particular entity.
	 *
	 * @param int $entity_guid The entity GUID
	 * @param string $name The name of the kind of annotation
	 * @param int $limit The number of annotations to display at once
	 * @param true|false $asc Whether or not the annotations are displayed in ascending order. (Default: true)
	 * @return string HTML (etc) version of the annotation list
	 */
	function list_annotations($entity_guid, $name = "", $limit = 25, $asc = true) {
		
		if ($asc) {
			$asc = "asc";
		} else {
			$asc = "desc";
		}
		$count = count_annotations($entity_guid, "", "", $name);
		$offset = (int) get_input("annoff",0);
		$annotations = get_annotations($entity_guid, "", "", $name, "", "", $limit, $offset, $asc);
		
		return elgg_view_annotation_list($annotations, $count, $offset, $limit);
		
	}

	/**
	 * Return the sum of a given integer annotation.
	 * 
	 * @param $entity_guid int
	 * @param $entity_type string
	 * @param $entity_subtype string
	 * @param $name string
	 */
	function get_annotations_sum($entity_guid, $entity_type = "", $entity_subtype = "", $name = "", $value = "", $value_type = "", $owner_guid = 0)
	{
		return __get_annotations_calculate_x("sum", $entity_guid, $entity_type, $entity_subtype, $name, $value, $value_type, $owner_guid);
	}
	
	/**
	 * Return the max of a given integer annotation.
	 * 
	 * @param $entity_guid int
	 * @param $entity_type string
	 * @param $entity_subtype string
	 * @param $name string
	 */
	function get_annotations_max($entity_guid, $entity_type = "", $entity_subtype = "", $name = "", $value = "", $value_type = "", $owner_guid = 0)
	{
		return __get_annotations_calculate_x("max", $entity_guid, $entity_type, $entity_subtype, $name, $value, $value_type, $owner_guid);
	}
	
	/**
	 * Return the minumum of a given integer annotation.
	 * 
	 * @param $entity_guid int
	 * @param $entity_type string
	 * @param $entity_subtype string
	 * @param $name string
	 */
	function get_annotations_min($entity_guid, $entity_type = "", $entity_subtype = "", $name = "", $value = "", $value_type = "", $owner_guid = 0)
	{
		return __get_annotations_calculate_x("min", $entity_guid, $entity_type, $entity_subtype, $name, $value, $value_type, $owner_guid);
	}
	
	/**
	 * Return the average of a given integer annotation.
	 * 
	 * @param $entity_guid int
	 * @param $entity_type string
	 * @param $entity_subtype string
	 * @param $name string
	 */
	function get_annotations_avg($entity_guid, $entity_type = "", $entity_subtype = "", $name = "", $value = "", $value_type = "", $owner_guid = 0)
	{
		return __get_annotations_calculate_x("avg", $entity_guid, $entity_type, $entity_subtype, $name, $value, $value_type, $owner_guid);
	}
	
	/**
	 * Count the number of annotations based on search parameters
	 *
	 * @param int $entity_guid
	 * @param string $entity_type
	 * @param string $entity_subtype
	 * @param string $name
	 */
	function count_annotations($entity_guid = 0, $entity_type = "", $entity_subtype = "", $name = "", $value = "", $value_type = "", $owner_guid = 0, $timelower = 0, $timeupper = 0)
	{
		return __get_annotations_calculate_x("count", $entity_guid, $entity_type, $entity_subtype, $name, $value, $value_type, $owner_guid, $timelower, $timeupper);
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
	function __get_annotations_calculate_x($sum = "avg", $entity_guid, $entity_type = "", $entity_subtype = "", $name = "", $value = "", $value_type = "", $owner_guid = 0, $timelower = 0, $timeupper = 0)
	{
		global $CONFIG;
		
		$sum = sanitise_string($sum);
		$entity_guid = (int)$entity_guid;
		$entity_type = sanitise_string($entity_type);
		$timeupper = (int)$timeupper;
		$timelower = (int)$timelower;
		$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
		if ($name != '' AND !$name = get_metastring_id($name))
			return 0;
		if ($value != '' AND !$value = get_metastring_id($value))
			return 0;
		$value_type = sanitise_string($value_type);
		$owner_guid = (int)$owner_guid;
		
		// if (empty($name)) return 0;
		
		$where = array();
		
		if ($entity_guid)
			$where[] = "e.guid=$entity_guid";
		if ($entity_type!="")
			$where[] = "e.type='$entity_type'";
		if ($entity_subtype)
			$where[] = "e.subtype=$entity_subtype";
		if ($name!="")
			$where[] = "a.name_id='$name'";
		if ($value!="")
			$where[] = "a.value_id='$value'";
		if ($value_type!="")
			$where[] = "a.value_type='$value_type'";
		if ($owner_guid)
			$where[] = "a.owner_guid='$owner_guid'";
		if ($timelower)
			$where[] = "a.time_created >= {$timelower}";
		if ($timeupper)
			$where[] = "a.time_created <= {$timeupper}";
			
		if ($sum != "count")
			$where[] = "a.value_type='integer'"; // Limit on integer types
		
		$query = "SELECT $sum(ms.string) as sum from {$CONFIG->dbprefix}annotations a JOIN {$CONFIG->dbprefix}entities e on a.entity_guid = e.guid JOIN {$CONFIG->dbprefix}metastrings ms on a.value_id=ms.id WHERE ";
		foreach ($where as $w)
			$query .= " $w and ";
		$query .= get_access_sql_suffix("a"); // now add access
		$query .= ' and ' . get_access_sql_suffix("e"); // now add access
		
		$row = get_data_row($query);
		if ($row)
			return $row->sum;
			
		return false;
	}

	/**
	 * Get entities ordered by a mathematical calculation
	 *
	 * @param $sum string
	 * @param $entity_type string
	 * @param $entity_subtype string
	 * @param $name string
	 * @param $mdname string
	 * @param $mdvalue string
	 * @param $limit int
	 * @param string $orderdir Default: asc - the sort order
	 * @return unknown
	 */
	function __get_entities_from_annotations_calculate_x($sum = "sum", $entity_type = "", $entity_subtype = "", $name = "", $mdname = '', $mdvalue = '', $owner_guid = 0, $limit = 10, $offset = 0, $orderdir = 'desc', $count = false)
	{
		global $CONFIG;
		
		$sum = sanitise_string($sum);
		$entity_type = sanitise_string($entity_type);
		$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
		$name = get_metastring_id($name);
		$limit = (int) $limit;
		$offset = (int) $offset;
		$owner_guid = (int) $owner_guid;
		if (!empty($mdname) && !empty($mdvalue)) {
			$meta_n = get_metastring_id($mdname);
			$meta_v = get_metastring_id($mdvalue);
		}
		
		if (empty($name)) return 0;
		
		$where = array();
		
		if ($entity_type!="")
			$where[] = "e.type='$entity_type'";
		if ($owner_guid > 0)
			$where[] = "e.container_guid = $owner_guid";
		if ($entity_subtype)
			$where[] = "e.subtype=$entity_subtype";
		if ($name!="")
			$where[] = "a.name_id='$name'";
			
		if (!empty($mdname) && !empty($mdvalue)) {
			if ($mdname!="")
				$where[] = "m.name_id='$meta_n'";
			if ($mdvalue!="")
				$where[] = "m.value_id='$meta_v'";
		}
			
		if ($sum != "count")
			$where[] = "a.value_type='integer'"; // Limit on integer types

		if (!$count) {
			$query = "SELECT distinct e.*, $sum(ms.string) as sum ";
		} else {
			$query = "SELECT count(distinct e.guid) as num, $sum(ms.string) as sum ";
		}
		$query .= " from {$CONFIG->dbprefix}entities e JOIN {$CONFIG->dbprefix}annotations a on a.entity_guid = e.guid JOIN {$CONFIG->dbprefix}metastrings ms on a.value_id=ms.id ";
		
		if (!empty($mdname) && !empty($mdvalue)) {
			$query .= " JOIN {$CONFIG->dbprefix}metadata m on m.entity_guid = e.guid "; 
		}
		
		$query .= " WHERE ";
		foreach ($where as $w)
			$query .= " $w and ";
		$query .= get_access_sql_suffix("a"); // now add access
		$query .= ' and ' . get_access_sql_suffix("e"); // now add access
		if (!$count) $query .= ' group by e.guid';
		
		if (!$count) {
			$query .= ' order by sum ' . $orderdir;
			$query .= ' limit ' . $offset . ' , ' . $limit;
			return get_data($query, "entity_row_to_elggstar");
		} else {
			if ($row = get_data_row($query)) {
				return $row->num;
			}
		}
		return false;
	}

	/**
	 * Returns entities ordered by the sum of an annotation
	 *
	 * @param unknown_type $entity_type
	 * @param unknown_type $entity_subtype
	 * @param unknown_type $name
	 * @param string $mdname
	 * @param string $mdvalue
	 * @param unknown_type $owner_guid
	 * @param int $limit
	 * @param int $offset
	 * @param true|false $count
	 * @return unknown
	 */
	function get_entities_from_annotation_count($entity_type = "", $entity_subtype = "", $name = "", $mdname = '', $mdvalue = '', $owner_guid = 0, $limit = 10, $offset = 0, $orderdir = 'desc', $count = false) {
		return __get_entities_from_annotations_calculate_x('sum',$entity_type,$entity_subtype,$name,$mdname, $mdvalue, $owner_guid,$limit, $offset, $orderdir, $count);
	}

	/**
	 * Lists entities by the totals of a particular kind of annotation
	 *
	 * @param string $entity_type Type of entity.
	 * @param string $entity_subtype Subtype of entity.
	 * @param string $name Name of annotation.
	 * @param int $limit Maximum number of results to return.
	 * @param int $owner_guid Owner.
	 * @param int $group_guid Group container. Currently this is only supported if $entity_type == 'object'
	 * @param boolean $asc Whether to list in ascending or descending order (default: desc)
	 * @param boolean $fullview Whether to display the entities in full
	 * @param boolean $viewtypetoggle Determines whether or not the 'gallery' view can be displayed (default: no)
	 * @return string Formatted entity list
	 */
	function list_entities_from_annotation_count($entity_type = "", $entity_subtype = "", $name = "", $limit = 10, $owner_guid = 0, $group_guid = 0, $asc = false, $fullview = true, $viewtypetoggle = false, $pagination = true, $orderdir = 'desc') {
		
		if ($asc) {
			$asc = "asc";
		} else {
			$asc = "desc";
		}
		
		$offset = (int) get_input("offset",0);
		$count = get_entities_from_annotation_count($entity_type, $entity_subtype, $name, '', '', $owner_guid, $limit, $offset, $orderdir, true); 
		$entities = get_entities_from_annotation_count($entity_type, $entity_subtype, $name, '', '', $owner_guid, $limit, $offset, $orderdir, false);

		return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, $viewtypetoggle, $pagination);
		
	}
	
	/**
	 * Lists entities by the totals of a particular kind of annotation AND the value of a piece of metadata
	 *
	 * @param string $entity_type Type of entity.
	 * @param string $entity_subtype Subtype of entity.
	 * @param string $name Name of annotation.
	 * @param string $mdname Metadata name
	 * @param string $mdvalue Metadata value
	 * @param int $limit Maximum number of results to return.
	 * @param int $owner_guid Owner.
	 * @param int $group_guid Group container. Currently this is only supported if $entity_type == 'object'
	 * @param boolean $asc Whether to list in ascending or descending order (default: desc)
	 * @param boolean $fullview Whether to display the entities in full
	 * @param boolean $viewtypetoggle Determines whether or not the 'gallery' view can be displayed (default: no)
	 * @return string Formatted entity list
	 */
	function list_entities_from_annotation_count_by_metadata($entity_type = "", $entity_subtype = "", $name = "", $mdname = '', $mdvalue = '', $limit = 10, $owner_guid = 0, $group_guid = 0, $asc = false, $fullview = true, $viewtypetoggle = false, $pagination = true, $orderdir = 'desc') {
		
		if ($asc) {
			$asc = "asc";
		} else {
			$asc = "desc";
		}
		
		$offset = (int) get_input("offset",0);
		$count = get_entities_from_annotation_count($entity_type, $entity_subtype, $name, $mdname, $mdvalue, $owner_guid, $limit, $offset, $orderdir, true); 
		$entities = get_entities_from_annotation_count($entity_type, $entity_subtype, $name, $mdname, $mdvalue, $owner_guid, $limit, $offset, $orderdir, false);

		return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, $viewtypetoggle, $pagination);
		
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
		
		$access = get_access_sql_suffix();
		$annotation = get_annotation($id);
		
		if (trigger_elgg_event('delete', 'annotation', $annotation))
			return delete_data("DELETE from {$CONFIG->dbprefix}annotations  where id=$id and $access");
			
		return false;
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
		
		if (!empty($name))
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
	 * Clear all annotations belonging to a given owner_guid
	 *
	 * @param int $owner_guid The owner
	 */
	function clear_annotations_by_owner($owner_guid)
	{
		global $CONFIG;
		
		$owner_guid = (int)$owner_guid;
		
		$annotations = get_data("SELECT id from {$CONFIG->dbprefix}annotations WHERE owner_guid=$owner_guid");
		$deleted = 0;
		
		if (!$annotations)
			return 0;
		
		foreach ($annotations as $id)
		{
			if (delete_annotation($id->id)) // Is this the best way?
				$deleted++;
		}
		
		return $deleted;
	}
	
	/**
	 * Handler called by trigger_plugin_hook on the "export" event.
	 */
	function export_annotation_plugin_hook($hook, $entity_type, $returnvalue, $params)
	{
		// Sanity check values
		if ((!is_array($params)) && (!isset($params['guid'])))
			throw new InvalidParameterException(elgg_echo('InvalidParameterException:GUIDNotForExport'));
			
		if (!is_array($returnvalue))
			throw new InvalidParameterException(elgg_echo('InvalidParameterException:NonArrayReturnValue'));
		
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
	
	/**
	 * Get the URL for this item of metadata, by default this links to the export handler in the current view.
	 *
	 * @param int $id
	 */
	function get_annotation_url($id)
	{
		$id = (int)$id;
		
		if ($extender = get_annotation($id)) {
			return get_extender_url($extender);	
		}
		return false;
	}
	
	
	/**
	 * Register an annotation url handler.
	 *
	 * @param string $function_name The function.
	 * @param string $extender_name The name, default 'all'.
	 */
	function register_annotation_url_handler($function_name, $extender_name = "all") {
		return register_extender_url_handler($function_name, 'annotation', $extender_name);
	}
	
	/** Register the hook */
	register_plugin_hook("export", "all", "export_annotation_plugin_hook", 2);
?>