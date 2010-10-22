<?php
/**
 * Elgg page owner library
 * Contains functions for managing page ownership and context
 *
 * @package Elgg
 * @subpackage Core
 */


/**
 * Gets the guid of the entity that owns the current page.
 * @param int $guid Optional parameter used by elgg_set_page_owner_guid().
 * @return int The current page owner guid (0 if none).
 * @since 1.8
 */
function elgg_get_page_owner_guid($guid = 0) {
	static $page_owner_guid;

	if ($guid) {
		$page_owner_guid = $guid;
	}

	if (isset($page_owner_guid)) {
		return $page_owner_guid;
	}

	$guid = trigger_plugin_hook('page_owner', 'system', NULL, 0);

	$page_owner_guid = $guid;

	return $guid;
}

/**
 * @deprecated 1.8  Use get_page_owner_guid()
 */
function page_owner() {
	elgg_deprecated_notice('page_owner() was deprecated by elgg_get_page_owner_guid().', 1.8);
	return elgg_get_page_owner_guid();
}

/**
 * Gets the owner entity for the current page.
 * @return ElggEntity|false The current page owner or false if none.
 * @since 1.8
 */
function elgg_get_page_owner() {
	$guid = elgg_get_page_owner_guid();
	if ($guid > 0) {
		return get_entity($guid);
	}

	return FALSE;
}

/**
 * @deprecated 1.8  Use elgg_get_page_owner()
 */
function page_owner_entity() {
	elgg_deprecated_notice('page_owner_entity() was deprecated by elgg_get_page_owner().', 1.8);
	return elgg_get_page_owner();
}

/**
 * Set the guid of the entity that owns this page
 * @param int $guid
 * @since 1.8
 */
function elgg_set_page_owner_guid($guid) {
	elgg_get_page_owner_guid($guid);
}


/**
 * @deprecated 1.8  Use the 'page_owner', 'system' plugin hook
 */
function add_page_owner_handler($functionname) {
	elgg_deprecated_notice("add_page_owner_handler() was deprecated by the plugin hook 'page_owner', 'system'.", 1.8);
}

/**
 * @deprecated 1.8  Use elgg_set_page_owner_guid()
 */
function set_page_owner($entitytoset = -1) {
	elgg_deprecated_notice('set_page_owner() was deprecated by elgg_set_page_owner_guid().', 1.8);
	elgg_set_page_owner_guid($entitytoset);
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
	if ($context = get_plugin_name(true)) {
		return $context;
	}
	return "main";
}

function default_page_owner_handler($hook, $entity_type, $returnvalue, $params) {

	if ($returnvalue) {
		return $returnvalue;
	}
	
	$username = get_input("username");
	if ($username) {
		if (substr_count($username, 'group:')) {
			preg_match('/group\:([0-9]+)/i', $username, $matches);
			$guid = $matches[1];
			if ($entity = get_entity($guid)) {
				return $entity->getGUID();
			}
		}

		if ($user = get_user_by_username($username)) {
			return $user->getGUID();
		}
	}

	$owner = get_input("owner_guid");
	if ($owner) {
		if ($user = get_entity($owner)) {
			return $user->getGUID();
		}
	}

	return $returnvalue;
}

function page_owner_init() {
	register_plugin_hook('page_owner', 'system', 'default_page_owner_handler');
}

register_elgg_event_handler('init', 'system', 'page_owner_init');