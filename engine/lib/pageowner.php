<?php
/**
 * Elgg page owner library
 * Contains functions for managing page ownership and context
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
	$page_owner = _elgg_services()->pageOwner;
	if ($guid === 0) {
		return $page_owner->getPageOwnerGuid();
	}
	
	elgg_deprecated_notice(__METHOD__ . ' should not be used to set the page owner. Use elgg_set_page_owner_guid().', '3.1');
	
	// calling function for BC
	elgg_set_page_owner_guid($guid);
	
	return $page_owner->getPageOwnerGuid();
}

/**
 * Gets the owner entity for the current page.
 *
 * @return \ElggEntity|false The current page owner or false if none.
 *
 * @since 1.8.0
 */
function elgg_get_page_owner_entity() {
	return _elgg_services()->pageOwner->getPageOwnerEntity();
}

/**
 * Set the guid of the entity that owns this page
 *
 * @param int $guid The guid of the page owner
 * @return void
 * @since 1.8.0
 */
function elgg_set_page_owner_guid($guid) {
	$page_owner = _elgg_services()->pageOwner;
	
	if ((int) $guid >= 0) {
		$page_owner->setPageOwnerGuid((int) $guid);
		return;
	}
	
	// removes page owner
	$page_owner->setPageOwnerGuid(0);
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
