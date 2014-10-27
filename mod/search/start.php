<?php
/**
 * Elgg search plugin
 *
 */

elgg_register_event_handler('init','system','search_init');

/**
 * Initialize search plugin
 */
function search_init() {
	require_once 'search_hooks.php';
	
	// page handler for search actions and results
	elgg_register_page_handler('search', 'search_page_handler');

	// add tags as custom search to be displayed in search results
	elgg_register_plugin_hook_handler('search_types', 'get_types', 'search_custom_types_tags_hook');

	// add in CSS for search elements
	elgg_extend_view('css/elgg', 'search/css');

	// extend view for elgg topbar search box
	elgg_extend_view('page/elements/header', 'search/header');

	elgg_register_plugin_hook_handler('robots.txt', 'site', 'search_exclude_robots');
}

/**
 * Page handler for search
 *
 * @param array $page Page elements from core page handler
 * @return bool
 */
function search_page_handler($page) {

	// if there is no q set, we're being called from a legacy installation
	// it expects a search by tags.
	// actually it doesn't, but maybe it should.
	// maintain backward compatibility
	if(!get_input('q', get_input('tag', NULL))) {
		set_input('q', $page[0]);
		//set_input('search_type', 'tags');
	}

	$base_dir = elgg_get_plugins_path() . 'search/pages/search';

	include_once("$base_dir/index.php");
	return true;
}

/**
 * Passes results, and original params to the view functions for
 * search type.
 *
 * @param array $results
 * @param array $params
 * @param string $view_type = list, entity or layout
 * @return string
 */
function search_get_search_view($params, $view_type) {
	switch ($view_type) {
		case 'list':
		case 'entity':
		case 'layout':
			break;

		default:
			return FALSE;
	}

	$view_order = array();

	// check if there's a special search list view for this type:subtype
	if (isset($params['type']) && $params['type'] && isset($params['subtype']) && $params['subtype']) {
		$view_order[] = "search/{$params['type']}/{$params['subtype']}/$view_type";
	}

	// also check for the default type
	if (isset($params['type']) && $params['type']) {
		$view_order[] = "search/{$params['type']}/$view_type";
	}

	// check search types
	if (isset($params['search_type']) && $params['search_type']) {
		$view_order[] = "search/{$params['search_type']}/$view_type";
	}

	// finally default to a search list default
	$view_order[] = "search/$view_type";

	foreach ($view_order as $view) {
		if (elgg_view_exists($view)) {
			return $view;
		}
	}

	return FALSE;
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
 * @return string
 */
function search_exclude_robots($hook, $type, $text) {
	$text .= <<<TEXT
User-agent: *
Disallow: /search/

TEXT;

	return $text;
}
