<?php

/**
 * Basic Elgg search hooks.
 */

/**
 * Function to start a search
 *
 * @see elgg_get_entities()
 * @see elgg_get_entities_from_metadata()
 * @see elgg_get_entities_from_relationship()
 *
 * @param array $params Search parameters
 *                      Accepts all options supported by the elgg_get_entities(), and:
 *                         - query         STR    Search query
 *                         - fields        ARRAY  Metadata/attribute fields to search in
 *                         - sort          STRING Field to sort by
 *                         - order         STRING Sorting order (ASC|DESC)
 *                         - partial_match BOOL   Allow partial matches (e.g. find 'elgg' for 'el' search)
 *                         - tokenize      BOOL   Break down search query into tokens (e.g. find 'elgg has been released' for 'elgg released')
 *
 * @return \ElggEntity|false
 */
function elgg_search(array $params = []) {
	return _elgg_services()->search->search($params);
}

/**
 * Initializes default search hooks
 * @return void
 * @since 3.0
 */
function _elgg_search_init() {

	elgg_register_plugin_hook_handler('search:fields', 'user', \Elgg\Search\UserSearchFieldsHandler::class);
	elgg_register_plugin_hook_handler('search:fields', 'object', \Elgg\Search\ObjectSearchFieldsHandler::class);
	elgg_register_plugin_hook_handler('search:fields', 'group', \Elgg\Search\GroupSearchFieldsHandler::class);
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_search_init');
};
