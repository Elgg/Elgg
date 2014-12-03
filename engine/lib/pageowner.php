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
 * @since 1.8.0
 */
function elgg_get_page_owner_guid($guid = 0) {
	static $page_owner_guid;

	if ($guid === false || $guid === null) {
		$page_owner_guid = 0;
		return $page_owner_guid;
	}
	
	if ($guid) {
		$page_owner_guid = (int)$guid;
	}

	if (isset($page_owner_guid)) {
		return $page_owner_guid;
	}

	// return guid of page owner entity
	$guid = (int)elgg_trigger_plugin_hook('page_owner', 'system', null, 0);

	if ($guid) {
		$page_owner_guid = $guid;
	}

	return $guid;
}

/**
 * Gets the owner entity for the current page.
 *
 * @note Access is disabled when getting the page owner entity.
 *
 * @return ElggUser|ElggGroup|false The current page owner or false if none.
 *
 * @since 1.8.0
 */
function elgg_get_page_owner_entity() {
	$guid = elgg_get_page_owner_guid();
	if ($guid > 0) {
		$ia = elgg_set_ignore_access(true);
		$owner = get_entity($guid);
		elgg_set_ignore_access($ia);

		return $owner;
	}

	return false;
}

/**
 * Set the guid of the entity that owns this page
 *
 * @param int $guid The guid of the page owner
 * @return void
 * @since 1.8.0
 */
function elgg_set_page_owner_guid($guid) {
	elgg_get_page_owner_guid($guid);
}

/**
 * Sets the page owner based on request
 *
 * Tries to figure out the page owner by looking at the URL or a request
 * parameter. The request parameters used are 'username' and 'owner_guid'.
 * Otherwise, this function attempts to figure out the owner if the url
 * fits the patterns of:
 *   <identifier>/owner/<username>
 *   <identifier>/friends/<username>
 *   <identifier>/view/<entity guid>
 *   <identifier>/add/<container guid>
 *   <identifier>/edit/<entity guid>
 *   <identifier>/group/<group guid>
 *
 * @note Access is disabled while finding the page owner for the group gatekeeper functions.
 *
 *
 * @param string $hook        'page_owner'
 * @param string $entity_type 'system'
 * @param int    $returnvalue Previous function's return value
 * @param array  $params      no parameters
 *
 * @return int GUID
 * @access private
 */
function default_page_owner_handler($hook, $entity_type, $returnvalue, $params) {

	if ($returnvalue) {
		return $returnvalue;
	}

	$ia = elgg_set_ignore_access(true);

	$username = get_input("username");
	if ($username) {
		// @todo using a username of group:<guid> is deprecated
		if (substr_count($username, 'group:')) {
			preg_match('/group\:([0-9]+)/i', $username, $matches);
			$guid = $matches[1];
			if ($entity = get_entity($guid)) {
				elgg_set_ignore_access($ia);
				return $entity->getGUID();
			}
		}

		if ($user = get_user_by_username($username)) {
			elgg_set_ignore_access($ia);
			return $user->getGUID();
		}
	}

	$owner = get_input("owner_guid");
	if ($owner) {
		if ($user = get_entity($owner)) {
			elgg_set_ignore_access($ia);
			return $user->getGUID();
		}
	}

	// ignore root and query
	$uri = current_page_url();
	$path = str_replace(elgg_get_site_url(), '', $uri);
	$path = trim($path, "/");
	if (strpos($path, "?")) {
		$path = substr($path, 0, strpos($path, "?"));
	}

	// @todo feels hacky
	$segments = explode('/', $path);
	if (isset($segments[1]) && isset($segments[2])) {
		switch ($segments[1]) {
			case 'owner':
			case 'friends':
				$user = get_user_by_username($segments[2]);
				if ($user) {
					elgg_set_ignore_access($ia);
					return $user->getGUID();
				}
				break;
			case 'view':
			case 'edit':
				$entity = get_entity($segments[2]);
				if ($entity) {
					elgg_set_ignore_access($ia);
					return $entity->getContainerGUID();
				}
				break;
			case 'add':
			case 'group':
				$entity = get_entity($segments[2]);
				if ($entity) {
					elgg_set_ignore_access($ia);
					return $entity->getGUID();
				}
				break;
		}
	}

	elgg_set_ignore_access($ia);
}

/**
 * Sets the page context
 *
 * Views can modify their output based on the local context. You may want to
 * display a list of blogs on a blog page or in a small widget. The rendered
 * output could be different for those two contexts ('blog' vs 'widget').
 *
 * Pages that pass through the page handling system set the context to the
 * first string after the root url. Example: http://example.org/elgg/bookmarks/
 * results in the initial context being set to 'bookmarks'.
 *
 * The context is a stack so that for a widget on a profile, the context stack
 * may contain first 'profile' and then 'widget'.
 *
 * If no context was been set, the default context returned is 'main'.
 *
 * @warning The context is not available until the page_handler runs (after
 * the 'init, system' event processing has completed).
 *
 * @param string $context The context of the page
 * @return bool
 * @since 1.8.0
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
 * @return string|null
 * @since 1.8.0
 */
function elgg_get_context() {
	global $CONFIG;

	if (!$CONFIG->context) {
		return null;
	}

	return $CONFIG->context[count($CONFIG->context) - 1];
}

/**
 * Push a context onto the top of the stack
 *
 * @param string $context The context string to add to the context stack
 * @return void
 * @since 1.8.0
 */
function elgg_push_context($context) {
	global $CONFIG;

	array_push($CONFIG->context, $context);
}

/**
 * Removes and returns the top context string from the stack
 *
 * @return string|null
 * @since 1.8.0
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
 * @param string $context The context string to check for
 * @return bool
 * @since 1.8.0
 */
function elgg_in_context($context) {
	global $CONFIG;

	return in_array($context, $CONFIG->context);
}

/**
 * Initializes the page owner functions
 *
 * @note This is on the 'boot, system' event so that the context is set up quickly.
 *
 * @return void
 * @access private
 */
function page_owner_boot() {

	elgg_register_plugin_hook_handler('page_owner', 'system', 'default_page_owner_handler');

	// Bootstrap the context stack by setting its first entry to the handler.
	// This is the first segment of the URL and the handler is set by the rewrite rules.
	// @todo this does not work for actions

	$request = _elgg_services()->request;

	// don't do this for *_handler.php, etc.
	if (basename($request->server->get('SCRIPT_FILENAME')) === 'index.php') {
		$context = $request->getFirstUrlSegment();
		if (!$context) {
			$context = 'main';
		}

		elgg_set_context($context);
	}
}

elgg_register_event_handler('boot', 'system', 'page_owner_boot');
