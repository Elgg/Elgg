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
	if ($entity->canEdit($user_guid)) {
		return true;
	}

	// Trigger plugin hook - note that $user may be null
	$params = array('entity' => $entity, 'user' => $user);
	return elgg_trigger_plugin_hook('permissions_check', $type, $params, false);
}
