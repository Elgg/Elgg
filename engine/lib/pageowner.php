<?php
/**
 * Elgg page owner library
 * Contains functions for managing page ownership
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * Gets the page owner for the current page.
 * @uses $CONFIG
 * @return int|false The current page owner guid (0 if none).
 */

function page_owner() {
	global $CONFIG;

	$returnval = NULL;

	$setpageowner = set_page_owner();
	if ($setpageowner !== false) {
		return $setpageowner;
	}

	if ((!isset($returnval)) && ($username = get_input("username"))) {
		if (substr_count($username,'group:')) {
			preg_match('/group\:([0-9]+)/i',$username,$matches);
			$guid = $matches[1];
			if ($entity = get_entity($guid)) {
				$returnval = $entity->getGUID();
			}
		}
		if ((!isset($returnval)) && ($user = get_user_by_username($username))) {
			$returnval = $user->getGUID();
		}
	}


	if ((!isset($returnval)) && ($owner = get_input("owner_guid"))) {
		if ($user = get_entity($owner)) {
			$returnval = $user->getGUID();
		}
	}


	if ((!isset($returnval)) && (!empty($CONFIG->page_owner_handlers) && is_array($CONFIG->page_owner_handlers))) {
		foreach($CONFIG->page_owner_handlers as $handler) {
			if ((!isset($returnval)) && ($guid = $handler())) {
				$returnval = $guid;
			}
		}
	}

	if (isset($returnval)) {
		// Check if this is obtainable, forwarding if not.
		/*
		 * If the owner entity has been set, but is inaccessible then we forward to the dashboard. This
		 * catches a bunch of WSoDs. It doesn't have much of a performance hit since 99.999% of the time the next thing
		 * a page does after calling this function is to retrieve the owner entity - which is of course cashed.
		 */
		$owner_entity = get_entity($returnval);
		if (!$owner_entity) {

			// Log an error
			error_log(sprintf(elgg_echo('pageownerunavailable'), $returnval));

			// Forward
			forward();
		}

		// set the page owner so if we're called again we don't have to think.
		set_page_owner($returnval);
		return $returnval;
	}

	return 0;
}

/**
 * Gets the page owner for the current page.
 * @uses $CONFIG
 * @return ElggUser|false The current page owner (false if none).
 */
function page_owner_entity() {
	global $CONFIG;
	$page_owner = page_owner();
	if ($page_owner > 0) {
		return get_entity($page_owner);
	}

	return false;
}

/**
 * Adds a page owner handler - a function that will
 * return the page owner if required
 * (Such functions are required to return false if they don't know)
 * @uses $CONFIG
 * @param string $functionname The name of the function to call
 * @return mixed The guid of the owner or false
 */

function add_page_owner_handler($functionname) {
	global $CONFIG;
	if (empty($CONFIG->page_owner_handlers)) {
		$CONFIG->page_owner_handlers = array();
	}
	if (is_callable($functionname)) {
		$CONFIG->page_owner_handlers[] = $functionname;
	}
}

/**
 * Allows a page to manually set a page owner
 *
 * @param int $entitytoset The GUID of the page owner
 * @return int|false Either the page owner we've just set, or false if unset
 */
function set_page_owner($entitytoset = -1) {
	static $entity;

	if (!isset($entity)) {
		$entity = false;
	}

	if ($entitytoset > -1) {
		$entity = $entitytoset;
	}

	return $entity;

}

/**
 * Sets the functional context of a page
 *
 * @param string $context The context of the page
 * @return string|false Either the context string, or false on failure
 */
function set_context($context) {
	global $CONFIG;
	if (!empty($context)) {
		$context = trim($context);
		$context = strtolower($context);
		$CONFIG->context = $context;
		return $context;
	} else {
		return false;
	}
}

/**
 * Returns the functional context of a page
 *
 * @return string The context, or 'main' if no context has been provided
 */
function get_context() {
	global $CONFIG;
	if (isset($CONFIG->context) && !empty($CONFIG->context)) {
		return $CONFIG->context;
	}
	if (preg_match("/\/pg\/([\w\-\_]+)/", $_SERVER['REQUEST_URI'], $matches)) {
		return $matches[1];
	}
	if ($context = get_plugin_name(true)) {
		return $context;
	}
	return "main";
}
