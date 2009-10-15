<?php
/**
 * Elgg Entity Extender.
 * This file contains ways of extending an Elgg entity in custom ways.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

/**
 * ElggExtender
 *
 * @author Curverider Ltd
 * @package Elgg
 * @subpackage Core
 */
abstract class ElggExtender implements
	Exportable,
	Loggable,	// Can events related to this object class be logged
	Iterator,	// Override foreach behaviour
	ArrayAccess // Override for array access
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
			if ($name=='value') {
				switch ($this->attributes['value_type']) {
					case 'integer' :
						return (int)$this->attributes['value'];

					//case 'tag' :
					//case 'file' :
					case 'text' :
						return ($this->attributes['value']);

					default :
						throw new InstallationException(sprintf(elgg_echo('InstallationException:TypeNotSupported'), $this->attributes['value_type']));
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
		if ($name == 'value') {
			$this->attributes['value_type'] = detect_extender_valuetype($value, $value_type);
		}

		return true;
	}

	/**
	 * Return the owner of this annotation.
	 *
	 * @return mixed
	 */
	public function getOwner() {
		return $this->owner_guid;
	}

	/**
	 * Return the owner entity
	 *
	 * @return mixed
	 */
	public function getOwnerEntity() {
		return get_user($this->owner_guid);
	}

	/**
	 * Returns the entity this is attached to
	 *
	 * @return ElggEntity The enttiy
	 */
	public function getEntity() {
		return get_entity($this->entity_guid);
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
	public function canEdit($user_guid = 0) {
		return can_edit_extender($this->id,$this->type,$user_guid);
	}

	/**
	 * Return a url for this extender.
	 *
	 * @return string
	 */
	public abstract function getURL();

	// EXPORTABLE INTERFACE ////////////////////////////////////////////////////////////

	/**
	 * Return an array of fields which can be exported.
	 */
	public function getExportableValues() {
		return array(
			'id',
			'entity_guid',
			'name',
			'value',
			'value_type',
			'owner_guid',
			'type',
		);
	}

	/**
	 * Export this object
	 *
	 * @return array
	 */
	public function export() {
		$uuid = get_uuid_from_object($this);

		$meta = new ODDMetadata($uuid, guid_to_uuid($this->entity_guid), $this->attributes['name'], $this->attributes['value'], $this->attributes['type'], guid_to_uuid($this->owner_guid));
		$meta->setAttribute('published', date("r", $this->time_created));

		return $meta;
	}

	// SYSTEM LOG INTERFACE ////////////////////////////////////////////////////////////

	/**
	 * Return an identification for the object for storage in the system log.
	 * This id must be an integer.
	 *
	 * @return int
	 */
	public function getSystemLogID() {
		return $this->id;
	}

	/**
	 * Return the class name of the object.
	 */
	public function getClassName() {
		return get_class($this);
	}

	/**
	 * Return the GUID of the owner of this object.
	 */
	public function getObjectOwnerGUID() {
		return $this->owner_guid;
	}

	/**
	 * Return a type of the object - eg. object, group, user, relationship, metadata, annotation etc
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Return a subtype. For metadata & annotations this is the 'name' and
	 * for relationship this is the relationship type.
	 */
	public function getSubtype() {
		return $this->name;
	}


	// ITERATOR INTERFACE //////////////////////////////////////////////////////////////
	/*
	 * This lets an entity's attributes be displayed using foreach as a normal array.
	 * Example: http://www.sitepoint.com/print/php5-standard-library
	 */

	private $valid = FALSE;

	function rewind() {
		$this->valid = (FALSE !== reset($this->attributes));
	}

	function current() {
		return current($this->attributes);
	}

	function key() {
		return key($this->attributes);
	}

	function next() {
		$this->valid = (FALSE !== next($this->attributes));
	}

	function valid() {
		return $this->valid;
	}

	// ARRAY ACCESS INTERFACE //////////////////////////////////////////////////////////
	/*
	 * This lets an entity's attributes be accessed like an associative array.
	 * Example: http://www.sitepoint.com/print/php5-standard-library
	 */

	function offsetSet($key, $value) {
		if ( array_key_exists($key, $this->attributes) ) {
			$this->attributes[$key] = $value;
		}
	}

	function offsetGet($key) {
		if ( array_key_exists($key, $this->attributes) ) {
			return $this->attributes[$key];
		}
	}

	function offsetUnset($key) {
		if ( array_key_exists($key, $this->attributes) ) {
			// Full unsetting is dangerious for our objects
			$this->attributes[$key] = "";
		}
	}

	function offsetExists($offset) {
		return array_key_exists($offset, $this->attributes);
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
function detect_extender_valuetype($value, $value_type = "") {
	if ($value_type!="") {
		return $value_type;
	}

	// This is crude
	if (is_int($value)) {
		return 'integer';
	}
	// Catch floating point values which are not integer
	if (is_numeric($value)) {
		return 'text';
	}

	return 'text';
}

/**
 * Utility function used by import_extender_plugin_hook() to process an ODDMetaData and add it to an entity.
 * This function does not hit ->save() on the entity (this lets you construct in memory)
 *
 * @param ElggEntity The entity to add the data to.
 * @param ODDMetaData $element The OpenDD element
 * @return bool
 */
function oddmetadata_to_elggextender(ElggEntity $entity, ODDMetaData $element) {
	// Get the type of extender (metadata, type, attribute etc)
	$type = $element->getAttribute('type');
	$attr_name = $element->getAttribute('name');
	$attr_val = $element->getBody();

	switch ($type) {
		// Ignore volatile items
		case 'volatile' :
			break;
		case 'annotation' :
			$entity->annotate($attr_name, $attr_val);
			break;
		case 'metadata' :
			$entity->setMetaData($attr_name, $attr_val, "", true);
			break;
		default : // Anything else assume attribute
			$entity->set($attr_name, $attr_val);
	}

	// Set time if appropriate
	$attr_time = $element->getAttribute('published');
	if ($attr_time) {
		$entity->set('time_updated', $attr_time);
	}

	return true;
}

/**
 *  Handler called by trigger_plugin_hook on the "import" event.
 */
function import_extender_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	$element = $params['element'];

	$tmp = NULL;

	if ($element instanceof ODDMetaData) {
		// Recall entity
		$entity_uuid = $element->getAttribute('entity_uuid');
		$entity = get_entity_from_uuid($entity_uuid);
		if (!$entity) {
			throw new ImportException(sprintf(elgg_echo('ImportException:GUIDNotFound'), $entity_uuid));
		}

		oddmetadata_to_elggextender($entity, $element);

		// Save
		if (!$entity->save()) {
			throw new ImportException(sprintf(elgg_echo('ImportException:ProblemUpdatingMeta'), $attr_name, $entity_uuid));
		}

		return true;
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
	if (!isloggedin()) {
		return false;
	}

	$user_guid = (int)$user_guid;
	$user = get_entity($user_guid);
	if (!$user) {
		$user = get_loggedin_user();
	}

	$functionname = "get_{$type}";
	if (is_callable($functionname)) {
		$extender = $functionname($extender_id);
	} else {
		return false;
	}

	if (!is_a($extender,"ElggExtender")) {
		return false;
	}

	// If the owner is the specified user, great! They can edit.
	if ($extender->getOwner() == $user->getGUID()) {
		return true;
	}

	// If the user can edit the entity this is attached to, great! They can edit.
	if (can_edit_entity($extender->entity_guid,$user->getGUID())) {
		return true;
	}

	// Trigger plugin hooks
	return trigger_plugin_hook('permissions_check',$type,array('entity' => $entity, 'user' => $user),false);
}

/**
 * Sets the URL handler for a particular extender type and name.
 * It is recommended that you do not call this directly, instead use one of the wrapper functions in the
 * subtype files.
 *
 * @param string $function_name The function to register
 * @param string $extender_type Extender type
 * @param string $extender_name The name of the extender
 * @return true|false Depending on success
 */
function register_extender_url_handler($function_name, $extender_type = "all", $extender_name = "all") {
	global $CONFIG;

	if (!is_callable($function_name)) {
		return false;
	}

	if (!isset($CONFIG->extender_url_handler)) {
		$CONFIG->extender_url_handler = array();
	}
	if (!isset($CONFIG->extender_url_handler[$extender_type])) {
		$CONFIG->extender_url_handler[$extender_type] = array();
	}
	$CONFIG->extender_url_handler[$extender_type][$extender_name] = $function_name;

	return true;
}

/**
 * Get the URL of a given elgg extender.
 * Used by get_annotation_url and get_metadata_url.
 *
 * @param ElggExtender $extender
 */
function get_extender_url(ElggExtender $extender) {
	global $CONFIG;

	$view = elgg_get_viewtype();

	$guid = $extender->entity_guid;
	$type = $extender->type;

	$url = "";

	$function = "";
	if (isset($CONFIG->extender_url_handler[$type][$extender->name])) {
		$function = $CONFIG->extender_url_handler[$type][$extender->name];
	}

	if (isset($CONFIG->extender_url_handler[$type]['all'])) {
		$function = $CONFIG->extender_url_handler[$type]['all'];
	}

	if (isset($CONFIG->extender_url_handler['all']['all'])) {
		$function = $CONFIG->extender_url_handler['all']['all'];
	}

	if (is_callable($function)) {
		$url = $function($extender);
	}

	if ($url == "") {
		$nameid = $extender->id;
		if ($type == 'volatile') {
			$nameid== $extender->name;
		}
		$url = $CONFIG->wwwroot  . "export/$view/$guid/$type/$nameid/";
	}
	return $url;
}

/** Register the hook */
register_plugin_hook("import", "all", "import_extender_plugin_hook", 2);