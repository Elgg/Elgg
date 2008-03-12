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
	 * @class ElggAnnotation
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 */
	class ElggAnnotation
	{
		/**
		 * This contains the site's main properties (id, etc)
		 * @var array
		 */
		private $attributes;
		
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
				}
			}
		}
		
		function __get($name) {
			if (isset($this->attributes[$name])) {
				
				// Sanitise outputs if required
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
		 * Return the owner of this annotation.
		 *
		 * @return mixed
		 */
		function getOwner() { return get_user($this->owner_id); }		
		
		function save()
		{
			if ($this->id > 0)
				return update_annotation($this->id, $this->name, $this->value, $this->value_type, $this->owner_id, $this->access_id);
			else
			{ 
				$this->id = create_annotation($this->entity_id, $this->entity_type, $this->name, $this->value, $this->value_type, $this->owner_id, $this->access_id);
				if (!$this->id) throw new IOException("Unable to save new ElggAnnotation");
				return $this->id;
			}
		}
		
		/**
		 * Delete a given site.
		 */
		function delete() { return delete_annotation($this->id); }
		
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
		
		return row_to_elggannotation(get_data_row("select o.* from {$CONFIG->dbprefix}annotations where id=$annotation_id and (o.access_id in {$access} or (o.access_id = 0 and o.owner_id = {$_SESSION['id']}))"));			
	}
	
	/**
	 * Get a list of annotations for a given object/user/annotation type.
	 *
	 * @param int $entity_id
	 * @param string $entity_type
	 * @param string $name
	 * @param mixed $value
	 * @param int $owner_id
	 * @param string $order_by
	 * @param int $limit
	 * @param int $offset
	 */
	function get_annotations($entity_id = 0, $entity_type = "", $name = "", $value = "", $owner_id = 0, $order_by = "created desc", $limit = 10, $offset = 0)
	{
		global $CONFIG;
		
		$entity_id = (int)$entity_id;
		$entity_type = sanitise_string(trim($entity_type));
		$name = sanitise_string(trim($name));
		$value = sanitise_string(trim($value));
		
		$owner_id = (int)$owner_id;
		
		$limit = (int)$limit;
		$offset = (int)$offset;
		
		
		// Construct query
		$where = array();
		
		if ($entity_id != 0)
			$where[] = "entity_id=$entity_id";
			
		if ($entity_type != "")
			$where[] = "entity_type='$entity_type'";
		
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
		$query = "SELECT * from {$CONFIG->dbprefix}annotations where ";
		for ($n = 0; $n < count($where); $n++)
		{
			if ($n > 0) $query .= " and ";
			$query .= $where[$n];
		}
		
		$query .= " order by $order_by limit $offset,$limit";
		
		return get_data($query, "row_to_elggannotation");
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
	function detect_annotation_valuetype($value, $value_type = "")
	{
		if ($value_type!="")
			return $value_type;
			
		// This is crude
		if (is_int($value)) return 'integer';
		
		return 'tag';
	}
	
	/**
	 * Create a new annotation.
	 *
	 * @param int $entity_id
	 * @param string $entity_type
	 * @param string $name
	 * @param string $value
	 * @param string $value_type
	 * @param int $owner_id
	 * @param int $access_id
	 */
	function create_annotation($entity_id, $entity_type, $name, $value, $value_type, $owner_id, $access_id = 0)
	{
		global $CONFIG;

		$entity_id = (int)$entity_id;
		$entity_type = sanitise_string(trim($entity_type));
		$name = sanitise_string(trim($name));
		$value = sanitise_string(trim($value));
		$value_type = detect_annotation_valuetype($value, sanitise_string(trim($value_type)));
		
		$owner_id = (int)$owner_id;
		if ($owner_id==0) $owner_id = $_SESSION['id'];
		
		$access_id = (int)$access_id;
		
		$time = time();
		
		// Add the metastring
		$value = add_metastring($value);
		if (!$value) return false;
		
		// If ok then add it
		return insert_data("INSERT into {$CONFIG->dbprefix}annotations (entity_id, entity_type, name, value, value_type, owner_id, created, access_id) VALUES ($entity_id,'$entity_type','$name','$value','$value_type', $owner_id, $time, $access_id)");
	}
	
	/**
	 * Update an annotation.
	 *
	 * @param int $annotation_id
	 * @param string $name
	 * @param string $value
	 * @param string $value_type
	 * @param int $owner_id
	 * @param int $access_id
	 */
	function update_annotation($annotation_id, $name, $value, $value_type, $owner_id, $access_id)
	{
		global $CONFIG;

		$annotation_id = (int)$annotation_id;
		$name = sanitise_string(trim($name));
		$value = sanitise_string(trim($value));
		$value_type = detect_annotation_valuetype($value, sanitise_string(trim($value_type)));
		
		$owner_id = (int)$owner_id;
		if ($owner_id==0) $owner_id = $_SESSION['id'];
		
		$access_id = (int)$access_id;
		
		$access = get_access_list();
		
		// Add the metastring
		$value = add_metastring($value);
		if (!$value) return false;
		
		// If ok then add it		
		return update_data("UPDATE {$CONFIG->dbprefix}annotations set value='$value', value_type='$value_type', access_id=$access_id, owner_id=$owner_id where id=$annotation_id and name='$name' and (access_id in {$access} or (access_id = 0 and owner_id = {$_SESSION['id']}))");
	}
	
	/**
	 * Count the number of annotations based on search parameters
	 *
	 * @param int $entity_id
	 * @param string $entity_type
	 * @param string $name
	 * @param mixed $value
	 * @param string $value_type
	 * @param int $owner_id
	 */
	function count_annotations($entity_id = 0, $entity_type = "", $name = "", $value = "", $value_type = "", $owner_id = 0)
	{
		global $CONFIG;
		
		$entity_id = (int)$entity_id;
		$entity_type = sanitise_string($entity_type);
		$name = sanitise_string($name);
		$value = sanitise_string($value);
		$value_type = sanitise_string($value_type);
		$owner_id = (int)$owner_id;
		$access = get_access_list();
		
		$where = array();
		$where_q = "";
		
		if ($entity_id != 0)
			$where[] = "entity_id=$entity_id";
			
		if ($entity_type != "")
			$where[] = "entity_type='$entity_type'";
			
		if ($name != "")
			$where[] = "name='$name'";
			
		if ($value != "")
			$where[] = "value='$value'";
			
		if ($value_type != "")
			$where[] = "value_type='$value_type'";
		
		if ($owner_id != 0)
			$where[] = "owner_id=$owner_id";
			
		for ($n = 0; $n < count($where); $n++)
			$where_q .= $where[$n] ." and ";
		
		$result = get_data_row("SELECT count(*) as count from {$CONFIG->dbprefix}annotations WHERE $where_q (access_id in {$access} or (access_id = 0 and owner_id = {$_SESSION['id']}))");
		if ($result)
			return $result->count;
			
		return false;
	}

	/**
	 * Return the sum of a given integer annotation.
	 * 
	 * @param $entity_id int
	 * @param $entity_type string
	 * @param $name string
	 */
	function get_annotations_sum($entity_id, $entity_type, $name)
	{
		global $CONFIG;
		
		$entity_id = (int)$entity_id;
		$entity_type = sanitise_string($entity_type);
		$name = santitise_string($name);
		
		$row = get_data_row("SELECT sum(value) as sum from {$CONFIG->dbprefix}annotations where entity_id=$entity_id and entity_type='$entity_type' and value_type='integer' and name='$name' and (access_id in {$access} or (access_id = 0 and owner_id = {$_SESSION['id']}))");
		
		if ($row)
			return $row->sum;
			
		return false;
	}
	
	/**
	 * Return the max of a given integer annotation.
	 * 
	 * @param $entity_id int
	 * @param $entity_type string
	 * @param $name string
	 */
	function get_annotations_max($entity_id, $entity_type, $name)
	{
		global $CONFIG;
		
		$entity_id = (int)$entity_id;
		$entity_type = sanitise_string($entity_type);
		$name = santitise_string($name);
		
		$row = get_data_row("SELECT max(value) as max from {$CONFIG->dbprefix}annotations where entity_id=$entity_id and entity_type='$entity_type' and value_type='integer' and name='$name' and (access_id in {$access} or (access_id = 0 and owner_id = {$_SESSION['id']}))");
		
		if ($row)
			return $row->max;
			
		return false;
	}
	
	/**
	 * Return the minumum of a given integer annotation.
	 * 
	 * @param $entity_id int
	 * @param $entity_type string
	 * @param $name string
	 */
	function get_annotations_min($entity_id, $entity_type, $name)
	{
		global $CONFIG;
		
		$entity_id = (int)$entity_id;
		$entity_type = sanitise_string($entity_type);
		$name = santitise_string($name);
		
		$row = get_data_row("SELECT min(value) as min from {$CONFIG->dbprefix}annotations where entity_id=$entity_id and entity_type='$entity_type' and value_type='integer' and name='$name' and (access_id in {$access} or (access_id = 0 and owner_id = {$_SESSION['id']}))");
		
		if ($row)
			return $row->min;
			
		return false;
	}
	
	/**
	 * Return the average of a given integer annotation.
	 * 
	 * @param $entity_id int
	 * @param $entity_type string
	 * @param $name string
	 */
	function get_annotations_avg($entity_id, $entity_type, $name)
	{
		global $CONFIG;
		
		$entity_id = (int)$entity_id;
		$entity_type = sanitise_string($entity_type);
		$name = santitise_string($name);
		
		$row = get_data_row("SELECT avg(value) as avg from {$CONFIG->dbprefix}annotations where entity_id=$entity_id and entity_type='$entity_type' and value_type='integer' and name='$name' and (access_id in {$access} or (access_id = 0 and owner_id = {$_SESSION['id']}))");
		
		if ($row)
			return $row->avg;
			
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
		
		return delete_data("DELETE from {$CONFIG->dbprefix}annotations  where id=$id and (access_id in {$access} or (access_id = 0 and owner_id = {$_SESSION['id']}))");
	}
?>