<?php
/**
 * Elgg page owner library
 * Contains functions for managing page ownership and context
 *
 * @package Elgg.Core
 * @subpackage PageOwner
 */

/**
 * Gets the guid of the entity that owns the current page.
 *
 * @param int $guid Optional parameter used by elgg_set_page_owner_guid().
 *
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

	$guid = elgg_trigger_plugin_hook('page_owner', 'system', NULL, 0);

	$page_owner_guid = $guid;

	return $guid;
}

/**
 * Gets the guid of the entity that owns the current page.
 *
 * @deprecated 1.8  Use elgg_get_page_owner_guid()
 *
 * @return int The current page owner guid (0 if none).
 */
function page_owner() {
	elgg_deprecated_notice('page_owner() was deprecated by elgg_get_page_owner_guid().', 1.8);
	return elgg_get_page_owner_guid();
}

/**
 * Gets the owner entity for the current page.
 *
 * @return ElggEntity|false The current page owner or false if none.
 *
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
 * Gets the owner entity for the current page.
 *
 * @deprecated 1.8  Use elgg_get_page_owner()
 * @return ElggEntity|false The current page owner or false if none.
 *
 * @since 1.8
 */
function page_owner_entity() {
	elgg_deprecated_notice('page_owner_entity() was deprecated by elgg_get_page_owner().', 1.8);
	return elgg_get_page_owner();
}

/**
 * Set the guid of the entity that owns this page
 *
 * @param int $guid The guid of the page owner
 *
 * @since 1.8
 */
function elgg_set_page_owner_guid($guid) {
	elgg_get_page_owner_guid($guid);
}


/**
 * Registers a page owner handler function
 *
 * @param string $functionname The callback function
 *
 * @deprecated 1.8  Use the 'page_owner', 'system' plugin hook
 * @return void
 */
function add_page_owner_handler($functionname) {
	elgg_deprecated_notice("add_page_owner_handler() was deprecated by the plugin hook 'page_owner', 'system'.", 1.8);
}

/**
 * Set a page owner entity
 *
 * @param int $entitytoset The GUID of the entity
 *
 * @deprecated 1.8  Use elgg_set_page_owner_guid()
 * @return void
 */
function set_page_owner($entitytoset = -1) {
	elgg_deprecated_notice('set_page_owner() was deprecated by elgg_set_page_owner_guid().', 1.8);
	elgg_set_page_owner_guid($entitytoset);
}

/**
 * Handles default page owners
 *
 * @param string $hook        page_owner
 * @param string $entity_type system
 * @param mixed  $returnvalue Previous function's return value
 * @param mixed  $params      Params
 *
 * @return int
 */
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

/**
 * Sets the page context
 *
 * Views can modify their output based on the local context. You may want to
 * display a list of blogs on a blog page or in a small widget. The rendered
 * output could be different for those two contexts ('blog' vs 'widget').
 *
 * Pages that pass through the page handling system set the context to the
 * first string after 'pg'. Example: http://elgg.org/pg/bookmarks/ results in
 * the initial context being set to 'bookmarks'.
 *
 * The context is a stack so that for a widget on a profile, the context stack
 * may contain first 'profile' and then 'widget'.
 *
 * If no context was been set, the default context returned is 'main'.
 *
 * @warning The context is not available until the page_handler runs (after
 * the 'init, system' event processing has completed).
 *
 * @param  string $context The context of the page
 * @return bool
 * @since 1.8
 */
function elgg_set_context($context) {
	global $CONFIG;

	$context = trim($context);

	if (empty($context)) {
		return false;
	}

	$context = strtolower($context);

	array_pop($CONFIG->context);
	array_push($CONFIG->context, $context);

	return true;
}

/**
 * Get the current context.
 *
 * Since context is a stack, this is equivalent to a peek.
 *
 * @return string|NULL
 * @since 1.8
 */
function elgg_get_context() {
	global $CONFIG;

	return $CONFIG->context[count($CONFIG->context) - 1];
}

/**
 * Push a context onto the top of the stack
 *
 * @param string $context The context string to add to the context stack
 * @since 1.8
 */
function elgg_push_context($context) {
	global $CONFIG;

	array_push($CONFIG->context, $context);
}

/**
 * Removes and returns the top context string from the stack
 *
 * @return string|NULL
 * @since 1.8
 */
function elgg_pop_context() {
	global $CONFIG;

	return array_pop($CONFIG->context);
}

/**
 * Check if this context exists anywhere in the stack
 *
 * This is useful for situations with more than one element in the stack. For
 * example, a widget has a context of 'widget'. If a widget view needs to render
 * itself differently based on being on the dashboard or profile pages, it
 * can check the stack.
 *
 * @param  string $context The context string to check for
 * @return bool
 * @since 1.8
 */
function elgg_in_context($context) {
	global $CONFIG;

	return in_array($context, $CONFIG->context);
}

/**
 * Sets the functional context of a page
 *
 * @deprecated 1.8  Use elgg_set_context()
 *
 * @param string $context The context of the page
 *
 * @return mixed Either the context string, or false on failure
 */
function set_context($context) {
	elgg_deprecated_notice('set_context() was deprecated by elgg_set_context().', 1.8);
	elgg_set_context($context);
	if (empty($context)) {
		return false;
	}
	return $context;
}

/**
 * Returns the functional context of a page
 *
 * @deprecated 1.8  Use elgg_get_context()
 *
 * @return string The context, or 'main' if no context has been provided
 */
function get_context() {
	elgg_deprecated_notice('get_context() was deprecated by elgg_get_context().', 1.8);
	return elgg_get_context();

	// @todo - used to set context based on calling script
	// $context = get_plugin_name(true)
}


/**
 * Initializes the page owner functions
 *
 * @note This is on the 'boot, system' event so that the context is set up quickly.
 *
 * @return void
 */
function page_owner_boot() {
	global $CONFIG;
	
	elgg_register_plugin_hook_handler('page_owner', 'system', 'default_page_owner_handler');
	
	// initial context - will be replaced by page handler
	$CONFIG->context = array('main');
}

elgg_register_event_handler('boot', 'system', 'page_owner_boot');