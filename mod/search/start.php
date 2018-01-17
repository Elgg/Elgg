<?php
/**
 * Elgg search plugin
 *
 */

/**
 * Initialize search plugin
 *
 * @return void
 */
function search_init() {

	// exclude /search routes from indexing
	elgg_register_plugin_hook_handler('robots.txt', 'site', 'search_exclude_robots');

	// add in CSS for search elements
	elgg_extend_view('elgg.css', 'search/search.css');

	elgg_register_plugin_hook_handler('search:format', 'entity', \Elgg\Search\FormatComentEntityHook::class);

	elgg_register_plugin_hook_handler('view_vars', 'output/tag', 'search_output_tag');
}

/**
 * Exclude robots from indexing search pages
 *
 * This is good for performance since search is slow and there are many pages all
 * with the same content.
 *
 * @elgg_plugin_hook robots.txt search
 *
 * @param \Elgg\Hook $hook Hook
 * @return string
 */
function search_exclude_robots(\Elgg\Hook $hook) {
	$rules = [
		'',
		'User-agent: *',
		'Disallow: /search/',
		''
	];

	$text = $hook->getValue();
	$text .= implode("\r\n", $rules);
	return $text;
}

/**
 * Adds search 'href' to output/tag view vars
 *
 * @elgg_plugin_hook view_vars output/tag
 *
 * @param \Elgg\Hook $hook Hook
 *
 * @return void|array
 */
function search_output_tag(\Elgg\Hook $hook) {
	$vars = $hook->getValue();
	if (isset($vars['href'])) {
		// leave unaltered
		return;
	}

	$query_params = [
		'q' => elgg_extract('value', $vars),
		'search_type' => 'tags',
		'type' => elgg_extract('type', $vars, null, false),
		'subtype' => elgg_extract('subtype', $vars, null, false),
	];

	$url = elgg_extract('base_url', $vars, 'search');

	unset($vars['base_url']);
	unset($vars['type']);
	unset($vars['subtype']);

	$vars['href'] = elgg_http_add_url_query_elements($url, $query_params);

	return $vars;
}

return function() {
	elgg_register_event_handler('init', 'system', 'search_init');
};