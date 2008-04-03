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
	 * @class ElggExtender 
	 * @author Marcus Povey
	 */
	abstract class ElggExtender implements Exportable, Importable
	{
		/**
		 * This contains the site's main properties (id, etc)
		 * @var array
		 */
		protected $attributes;
		
		protected function get($name) {
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
		
		protected function set($name, $value) {
			$this->attributes[$name] = $value;
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
		
		public function export()
		{
			$tmp = new stdClass;
			$tmp->attributes = $this->attributes;
			$tmp->attributes['owner_uuid'] = guid_to_uuid($this->owner_guid);
			$tmp->attributes['entity_uuid'] = guid_to_uuid($this->entity_guid);
			return $tmp;
		}
		
		public function import(array $data, $version = 1)
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
	
	/** Register the hook */
	register_plugin_hook("import", "all", "import_extender_plugin_hook", 2);
	
?>