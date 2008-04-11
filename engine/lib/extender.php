<?php
	/**
	 * Elgg Entity Extender.
	 * This file contains ways of extending an Elgg entity in custom ways.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * ElggExtender 
	 * 
	 * @author Marcus Povey
	 * @package Elgg
	 * @subpackage Core
	 */
	abstract class ElggExtender implements Exportable, Importable
	{
		/**
		 * This contains the site's main properties (id, etc)
		 * @var array
		 */
		protected $attributes;
		
		/**
		 * Get an attribute
		 *
		 * @param string $name
		 * @return mixed
		 */
		protected function get($name) {
			if (isset($this->attributes[$name])) {
				
				// Sanitise value if necessary
				if ($name=='value')
				{
					switch ($this->attributes['value_type'])
					{
						case 'integer' :  return (int)$this->attributes['value'];
						//case 'tag' :
						//case 'file' :
						case 'text' : return sanitise_string($this->attributes['value']);
							
						default : throw new InstallationException("Type {$this->attributes['value_type']} is not supported. This indicates an error in your installation, most likely caused by an incomplete upgrade.");
					}
				}
				
				return $this->attributes[$name];
			}
			return null;
		}
		
		/**
		 * Set an attribute
		 *
		 * @param string $name
		 * @param mixed $value
		 * @param string $value_type
		 * @return boolean
		 */
		protected function set($name, $value, $value_type = "") {

			$this->attributes[$name] = $value;
			$this->attributes['value_type'] = detect_extender_valuetype($value, $value_type);
			
			return true;
		}	
		
		/**
		 * Return the owner of this annotation.
		 *
		 * @return mixed
		 */
		public function getOwner() 
		{ 
			return get_user($this->owner_guid); 
		}
		
		/**
		 * Save this data to the appropriate database table.
		 */
		abstract public function save();
		
		/**
		 * Delete this data.
		 */
		abstract public function delete();
		
		/**
		 * Determines whether or not the specified user can edit this
		 *
		 * @param int $user_guid The GUID of the user (defaults to currently logged in user)
		 * @return true|false
		 */
		function canEdit($user_guid = 0) {
			return can_edit_extender($this->id,$this->type,$user_guid);
		}
		
		/**
		 * Export this object
		 *
		 * @return array
		 */
		public function export()
		{
			$tmp = new stdClass;
			$tmp->attributes = $this->attributes;
			$tmp->attributes['owner_uuid'] = guid_to_uuid($this->owner_guid);
			$tmp->attributes['entity_uuid'] = guid_to_uuid($this->entity_guid);
			return $tmp;
		}
		
		/**
		 * Import an object
		 *
		 * @param array $data
		 * @param int $version
		 * @return ElggExtender
		 * @throws ImportException
		 */
		public function import(ODD $data)
		{
			if ($version == 1)
			{
				$entity_uuid = NULL; 
				
				// Get attributes
				foreach ($data['elements'][0]['elements'] as $attr)
				{
					$name = strtolower($attr['name']);
					$text = $attr['text'];
					
					switch ($name)
					{
						case 'id' : break;
						case 'entity_uuid' : $entity_uuid = $text; break;
						default : $this->attributes[$name] = $text;
					}

				}
				// See if this entity has already been imported, if so then we need to link to it
				$entity = get_entity_from_uuid($entity_uuid);
				if (!$entity)
					throw new ImportException("Sorry $entity_uuid was not found. Could not import annotation.");
				
				// Set the item ID
				$this->attributes['entity_guid'] = $entity->getGUID();
				
				// Set owner
				$this->attributes[$name] = $entity->getOwner(); 
				
				// save
				$result = $this->save(); 
				if (!$result)
					throw new ImportException("There was a problem saving the ElggExtender");
				
				return $this;
				
			}
			else
				throw new ImportException("Unsupported version ($version) passed to ElggAnnotation::import()");
		}
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
	function detect_extender_valuetype($value, $value_type = "")
	{
		if ($value_type!="")
			return $value_type;
			
		// This is crude
		if (is_int($value)) return 'integer';
		if (is_numeric($value)) return 'integer';
		
		return 'text';
	}
	
	/**
	 *  Handler called by trigger_plugin_hook on the "import" event.
	 */
	function import_extender_plugin_hook($hook, $entity_type, $returnvalue, $params)
	{
		$name = $params['name'];
		$element = $params['element'];
		
		$tmp = NULL;
		
		switch ($name)
		{
			case 'ElggAnnotation' : $tmp = new ElggAnnotation(); break;
			case 'ElggMetadata' : $tmp = new ElggMetadata(); break;
		}
		
		if ($tmp)
		{
			$tmp->import($element);
			return $tmp;
		}
	}
	
	/**
	 * Determines whether or not the specified user can edit the specified piece of extender
	 *
	 * @param int $extender_id The ID of the piece of extender
	 * @param string $type 'metadata' or 'annotation'
	 * @param int $user_guid The GUID of the user
	 * @return true|false
	 */
	function can_edit_extender($extender_id, $type, $user_guid = 0) {
		
		if ($user_guid == 0) {
			$user = $_SESSION['user'];
		} else {
			$user = get_entity($user_guid);
		}
		$functionname = "get_{$type}";
		if (is_callable($functionname)) {
			$extender = $functionname($extender_id);
		} else return false;
		
		if ($extender->getOwner() == $user->getGUID()) return true;
		
		return trigger_plugin_hook('permissions_check',$type,array('entity' => $entity, 'user' => $user),false);
		
	}
	
	/** Register the hook */
	register_plugin_hook("import", "all", "import_extender_plugin_hook", 2);
	
?>