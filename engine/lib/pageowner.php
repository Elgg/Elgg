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
 * @see default_page_owner_handler() Used to guess the page owner if it's not been set.
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
		$page_owner_guid = (int) $guid;
	}

	if (isset($page_owner_guid)) {
		return $page_owner_guid;
	}

	$route = _elgg_services()->request->getRoute();
	if ($route) {
		$page_owner = $route->resolvePageOwner();
		if ($page_owner) {
			$page_owner_guid = $page_owner->guid;
			return $page_owner_guid;
		}
	}

	// return guid of page owner entity
	// Note: core registers default_page_owner_handler() to handle this hook.
	$guid = (int) elgg_trigger_plugin_hook('page_owner', 'system', null, 0);

	if ($guid) {
		$page_owner_guid = $guid;
	}

	return $guid;
}

/**
 * Gets the owner entity for the current page.
 *
 * @return \ElggEntity|false The current page owner or false if none.
 *
 * @since 1.8.0
 */
function elgg_get_page_owner_entity() {
	$guid = elgg_get_page_owner_guid();
	if (!$guid) {
		return false;
	}

	return get_entity($guid);
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
	if ($user = get_user_by_username($username)) {
		elgg_set_ignore_access($ia);
		return $user->getGUID();
	}

	$owner = get_input("owner_guid");
	if ($owner) {
		if ($user = get_entity($owner)) {
			elgg_set_ignore_access($ia);
			return $user->getGUID();
		}
	}

	// @todo feels hacky
	$segments = _elgg_services()->request->getUrlSegments();
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
	return _elgg_services()->request->getContextStack()->set($context);
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
	return _elgg_services()->request->getContextStack()->peek();
}

/**
 * Push a context onto the top of the stack
 *
 * @param string $context The context string to add to the context stack
 * @return void
 * @since 1.8.0
 */
function elgg_push_context($context) {
	_elgg_services()->request->getContextStack()->push($context);
}

/**
 * Removes and returns the top context string from the stack
 *
 * @return string|null
 * @since 1.8.0
 */
function elgg_pop_context() {
	return _elgg_services()->request->getContextStack()->pop();
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
	return _elgg_services()->request->getContextStack()->contains($context);
}

/**
 * Get the entire context stack (e.g. for backing it up)
 *
 * @return string[]
 * @since 1.11
 */
function elgg_get_context_stack() {
	return _elgg_services()->request->getContextStack()->toArray();
}

/**
 * Set the entire context stack
 *
 * @param string[] $stack All contexts to be placed on the stack
 * @return void
 * @since 1.11
 */
function elgg_set_context_stack(array $stack) {
	_elgg_services()->request->getContextStack()->fromArray($stack);
}

/**
 * Set up default page owner default
 *
 * @return void
 * @access private
 */
function _elgg_pageowner_init() {
	elgg_register_plugin_hook_handler('page_owner', 'system', 'default_page_owner_handler');
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_pageowner_init');
};
