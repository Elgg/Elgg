<?php

function lazy_hover_init() {
	elgg_extend_view("js/elgg", "js/lazy_hover/site");

	elgg_register_page_handler("lazy_hover", "lazy_hover_page_handler");

	// extend public pages
	elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'lazy_hover_public_pages');

}

/**
 * @param string[] $page URL segments
 * @return bool
 */
function lazy_hover_page_handler($page) {
	require dirname(__FILE__) . "/pages/lazy_hover.php";
	return true;
}

/**
 * Extend public pages
 *
 * @param string   $hook_name    "public_pages"
 * @param string   $entity_type  "walled_garden"
 * @param string[] $return_value array of public pages
 * @param mixed    $params       unused
 *
 * @return string[]
 */
function lazy_hover_public_pages($hook_name, $entity_type, $return_value, $params) {
	$return = $return_value;
	if (is_array($return)) {
		$return[] = "lazy_hover";
	}
	return $return;
}

// register default elgg events
elgg_register_event_handler("init", "system", "lazy_hover_init");
