<?php

namespace Elgg\Search;

/**
 * Event callbacks for views
 *
 * @since 4.0
 * @internal
 */
class Views {

	/**
	 * Adds search 'href' to output/tag view vars
	 *
	 * @param \Elgg\Event $event 'view_vars' 'output/tag'
	 *
	 * @return void|array
	 */
	public static function setSearchHref(\Elgg\Event $event) {
		$vars = $event->getValue();
		if (isset($vars['href'])) {
			// leave unaltered
			return;
		}
	
		$query_params = [
			'q' => elgg_extract('value', $vars),
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
}
