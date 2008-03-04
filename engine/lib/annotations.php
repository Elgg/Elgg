<?php
	/**
	 * Elgg annotations
	 * Functions to manage object annotations.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@dushka.co.uk
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
		 * Construct a new site object, optionally from a given id value.
		 *
		 * @param int $id
		 */
		function __construct($id = null) 
		{
			if (!empty($id)) {
				if ($annotation = get_annotation($id)) {
					$objarray = (array) $annotation;
					foreach($objarray as $key => $value) {
						$this->attributes[$key] = $value;
					}
				}
			}
		}
		
		function __get($name) {
			if (isset($attributes[$name])) {
				return $attributes[$name];
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
			if (isset($this->id))
				return update_annotation($this->id, $this->name, $this->value, $this->value_type, $this->owner_id, $this->access_id);
			else
			{ 
				$this->id = create_annotation($this->object_id, $this->object_type, $this->name, $this->value, $this->value_type, $this->owner_id, $this->access_id);
				return $this->id;
			}
		}
		
		/**
		 * Delete a given site.
		 */
		function delete() { return delete_annotation($this->id); }
		
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
		
		return get_data_row("select o.* from {$CONFIG->dbprefix}annotations where id=$annotation_id and (o.access_id in {$access} or (o.access_id = 0 and o.owner_id = {$_SESSION['id']}))");			
	}
	
	/**
	 * Get a list of annotations for a given object/user/annotation type.
	 *
	 * @param int $object_id
	 * @param string $annotation_type
	 * @param int $owner_id
	 * @param string $order_by
	 * @param int $limit
	 * @param int $offset
	 */
	function get_annotations($object_id = 0, $annotation_type = "", $owner_id = 0, $order_by = "created desc", $limit = 10, $offset = 0)
	{
		$object_id = (int)$object_id;
		$annotation_type = mysql_real_escape_string(trim($annotation_type));
		$name = mysql_real_escape_string(trim($name));
		$value = mysql_real_escape_string(trim($value));
		$owner_id = (int)$owner_id;
		$limit = (int)$limit;
		$offset = (int)$offset;
		
		$access = get_access_list();
		
		
		// construct query.
		
		
		
		
		return get_data($query);
	}
	
	/**
	 * Create a new annotation.
	 *
	 * @param int $object_id
	 * @param string $object_type
	 * @param string $name
	 * @param string $value
	 * @param string $value_type
	 * @param int $owner_id
	 * @param int $access_id
	 */
	function create_annotation($object_id, $object_type, $name, $value, $value_type, $owner_id, $access_id = 0)
	{
		global $CONFIG;

		$object_id = (int)$object_id;
		$object_type = mysql_real_escape_string(trim($object_type));
		$name = mysql_real_escape_string(trim($name));
		$value = mysql_real_escape_string(trim($value));
		$value_type = mysql_real_escape_string(trim($value_type));
		$owner_id = (int)$owner_id;
		$access_id = (int)$access_id;
		
		return insert_data("INSERT into {$CONFIG->dbprefix}annotations (object_id, object_type, name, value, value_type, owner_id, created, access_id) VALUES ($object_id,'$object_type','$name','$value','$value_type', $owner_id, $access_id)");
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
		$name = mysql_real_escape_string(trim($name));
		$value = mysql_real_escape_string(trim($value));
		$value_type = mysql_real_escape_string(trim($value_type));
		$owner_id = (int)$owner_id;
		$access_id = (int)$access_id;
		
		$access = get_access_list();
		
		return update_data("UPDATE {$CONFIG->dbprefix}annotations set value='$value', value_type='$value_type', access_id=$access_id where id=$annotation_id and name='$name' and (access_id in {$access} or (access_id = 0 and owner_id = {$_SESSION['id']}))");
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