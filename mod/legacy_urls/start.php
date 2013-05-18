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
 * Redirect the requestor to the new URL
 * 
 * @param string $url Relative or absolute URL
 */
function legacy_urls_redirect($url) {
	$method = elgg_get_plugin_setting('redirect_method', 'legacy_urls');
	switch ($method) {
		case 'landing':
			$content = elgg_view('legacy_urls/message', array('url' => $url));
			$body = elgg_view_layout('error', array('content' => $content));
			echo elgg_view_page('', $body, 'error');
			return true;
			break;
		case 'immediate_error':
			// drop through after setting error message
			register_error(elgg_echo('changebookmark'));
		case 'immediate':
		default:
			$url = elgg_normalize_url($url);
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: $url");
			exit;
			break;
	}
}

/**
 * Handle requests for /tag/<tag string>
 */
function legacy_urls_tag_handler($segments) {
	$tag = $segments[0];
	return legacy_urls_redirect("search?q=$tag");
}

/**
 * Handle requests for URLs that start with /pg/
 */
function legacy_urls_pg_handler($segments) {

	$url = implode('/', $segments);

	// this is needed because Elgg's htaccess urls munge the request
	$query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
	if ($query) {
		$url .= "?$query";
	}

	return legacy_urls_redirect($url);
}
