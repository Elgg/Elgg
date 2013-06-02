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
 * Determines whether or not the specified user can edit the specified piece of extender
 *
 * @param int    $extender_id The ID of the piece of extender
 * @param string $type        'metadata' or 'annotation'
 * @param int    $user_guid   The GUID of the user
 *
 * @return bool
 */
function can_edit_extender($extender_id, $type, $user_guid = 0) {
	// @todo Since Elgg 1.0, Elgg has returned false from can_edit_extender()
	// if no user was logged in. This breaks the access override. This is a
	// temporary work around. This function needs to be rewritten in Elgg 1.9 
	if (!elgg_check_access_overrides($user_guid)) {
		if (!elgg_is_logged_in()) {
			return false;
		}
	}

	$user_guid = (int)$user_guid;
	$user = get_user($user_guid);
	if (!$user) {
		$user = elgg_get_logged_in_user_entity();
		$user_guid = elgg_get_logged_in_user_guid();
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
	if ($extender->getOwnerGUID() == $user_guid) {
		return true;
	}

	// If the user can edit the entity this is attached to, great! They can edit.
	$entity = $extender->getEntity();
	if ($entity->canEdit($user->getGUID())) {
		return true;
	}

	// Trigger plugin hook - note that $user may be null
	$params = array('entity' => $entity, 'user' => $user);
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
