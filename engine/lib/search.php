<?php
/**
 * Basic Elgg search hooks.
 */

/**
 * Function to start a search
 *
 * @param array $params Array of search parameters
 *
 * @return array
 */
function elgg_search(array $params = []) {
	return _elgg_services()->searchService->search($params);
}

/**
 * Exclude robots from indexing search pages
 *
 * This is good for performance since search is slow and there are many pages all
 * with the same content.
 *
 * @param string $hook Hook name
 * @param string $type Hook type
 * @param string $text robots.txt content for plugins
 *
 * @return string
 */
function elgg_search_exclude_robots($hook, $type, $text) {
	$text .= <<<TEXT
User-agent: *
Disallow: /search/

TEXT;

	return $text;
}

/**
 * Initialiases the default search hooks
 *
 * @return void
 *
 * @since 3.0
 */
function _elgg_search_init() {
	elgg_register_plugin_hook_handler('robots.txt', 'site', 'elgg_search_exclude_robots');
		
	// register some default search hooks
	// register with high priority to make it easier for plugin devs to provide search results from the plugin
	elgg_register_plugin_hook_handler('search', 'object', '\Elgg\Search\SearchHandler::findObjects', 999);
	elgg_register_plugin_hook_handler('search', 'user', '\Elgg\Search\SearchHandler::findUsers', 999);
	elgg_register_plugin_hook_handler('search', 'group', '\Elgg\Search\SearchHandler::findGroups', 999);

	// tags and comments are a bit different.
	// register a search types and a hooks for them.
	elgg_register_plugin_hook_handler('search_types', 'get_types', '\Elgg\Search\SearchHandler::customTypeTags', 999);
	elgg_register_plugin_hook_handler('search', 'tags', '\Elgg\Search\SearchHandler::findEntitiesByTag');
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_search_init');
};