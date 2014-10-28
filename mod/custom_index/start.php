<?php
/**
 * Elgg demo custom index page plugin
 *
 */

elgg_register_event_handler('init', 'system', 'custom_index_init');

function custom_index_init() {

	// Extend system CSS with our own styles
	elgg_extend_view('css/elgg', 'custom_index/css');

	// Replace the default index page
	elgg_register_page_handler('', 'custom_index');
}

/**
 * Serve the front page
 *
 * @return bool Whether the page was sent.
 */
function custom_index() {
	if (!include_once(dirname(__FILE__) . "/index.php")) {
		return false;
	}

	return true;
}
