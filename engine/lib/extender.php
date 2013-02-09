<?php
/**
 * Elgg Entity Extender.
 * This file contains ways of extending an Elgg entity in custom ways.
 *
 * @package Elgg.Core
 * @subpackage DataModel.Extender
 */

/**
 * Detect the value_type for a given value.
 * Currently this is very crude.
 *
 * @todo Make better!
 *
 * @param mixed  $value      The value
 * @param string $value_type If specified, overrides the detection.
 *
 * @return string
 */
function detect_extender_valuetype($value, $value_type = "") {
	if ($value_type != "" && ($value_type == 'integer' || $value_type == 'text')) {
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
 * Utility function used by import_extender_plugin_hook() to process
 * an ODDMetaData and add it to an entity. This function does not
 * hit ->save() on the entity (this lets you construct in memory)
 *
 * @param ElggEntity  $entity  The entity to add the data to.
 * @param ODDMetaData $element The OpenDD element
 *
 * @return bool
 * @access private
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
 *
 * @param string $hook        volatile
 * @param string $entity_type metadata
 * @param string $returnvalue Return value from previous hook
 * @param array  $params      The parameters
 *
 * @return null
 * @elgg_plugin_hook_handler volatile metadata
 * @todo investigate more.
 * @throws ImportException
 * @access private
 */
function import_extender_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	$element = $params['element'];

	$tmp = NULL;

	if ($element instanceof ODDMetaData) {
		/* @var ODDMetaData $element */
		// Recall entity
		$entity_uuid = $element->getAttribute('entity_uuid');
		$entity = get_entity_from_uuid($entity_uuid);
		if (!$entity) {
			throw new ImportException(elgg_echo('ImportException:GUIDNotFound', array($entity_uuid)));
		}

		oddmetadata_to_elggextender($entity, $element);

		// Save
		if (!$entity->save()) {
			$attr_name = $element->getAttribute('name');
			$msg = elgg_echo('ImportException:ProblemUpdatingMeta', array($attr_name, $entity_uuid));
			throw new ImportException($msg);
		}

		return true;
	}
}

/**
 * Determines whether or not the specified user can edit the specified piece of extender
 *
 * @param int    $extender_id The ID of the piece of extender
 * @param string $type        'metadata' or 'annotation'
 * @param int    $user_guid   The GUID of the user
 *
 * @return bool
 */
function can_edit_extender($extender_id, $type, $user_guid = 0) {
	if (!elgg_is_logged_in()) {
		return false;
	}

	$user_guid = (int)$user_guid;
	$user = get_entity($user_guid);
	if (!$user) {
		$user = elgg_get_logged_in_user_entity();
	}

	$functionname = "elgg_get_{$type}_from_id";
	if (is_callable($functionname)) {
		$extender = call_user_func($functionname, $extender_id);
	} else {
		return false;
	}

	if (!($extender instanceof ElggExtender)) {
		return false;
	}
	/* @var ElggExtender $extender */

	// If the owner is the specified user, great! They can edit.
	if ($extender->getOwnerGUID() == $user->getGUID()) {
		return true;
	}

	// If the user can edit the entity this is attached to, great! They can edit.
	if (can_edit_entity($extender->entity_guid, $user->getGUID())) {
		return true;
	}

	// Trigger plugin hooks
	$params = array('entity' => $extender->getEntity(), 'user' => $user);
	return elgg_trigger_plugin_hook('permissions_check', $type, $params, false);
}

/**
 * Sets the URL handler for a particular extender type and name.
 * It is recommended that you do not call this directly, instead use
 * one of the wrapper functions such as elgg_register_annotation_url_handler().
 *
 * @param string $extender_type Extender type ('annotation', 'metadata')
 * @param string $extender_name The name of the extender
 * @param string $function_name The function to register
 *
 * @return bool
 */
function elgg_register_extender_url_handler($extender_type, $extender_name, $function_name) {

	global $CONFIG;

	if (!is_callable($function_name, true)) {
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
 * @param ElggExtender $extender An extender object
 *
 * @return string
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
		$url = call_user_func($function, $extender);
	}

	if ($url == "") {
		$nameid = $extender->id;
		if ($type == 'volatile') {
			$nameid = $extender->name;
		}
		$url = "export/$view/$guid/$type/$nameid/";
	}

	return elgg_normalize_url($url);
}

/** Register the hook */
elgg_register_plugin_hook_handler("import", "all", "import_extender_plugin_hook", 2);
