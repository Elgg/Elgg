<?php
/**
 * Provides support for URLs no longer used in Elgg for those who bookmarked or
 * linked to them
 */

elgg_register_event_handler('init', 'system', 'legacy_urls_init');

function legacy_urls_init() {
	elgg_register_page_handler('tag', 'legacy_urls_tag_handler');
	elgg_register_page_handler('pg', 'legacy_urls_pg_handler');
}

/**
 * Send a permanent redirect to browser
 * 
 * @param string $url Relative or absolute URL
 */
function legacy_urls_redirect($url) {
	$url = elgg_normalize_url($url);
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: $url");
	exit;
}

function legacy_urls_tag_handler($segments) {
	$tag = $segments[0];
	legacy_urls_redirect("search?q=$tag");
}

function legacy_urls_pg_handler($segments) {

	$url = implode('/', $segments);

	// this is needed because Elgg's htaccess urls munge the request
	$query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
	if ($query) {
		$url .= "?$query";
	}

	legacy_urls_redirect($url);
}
