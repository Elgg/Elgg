<?php
/**
 * Elgg page handler functions
 *
 * @package    Elgg.Core
 * @subpackage Routing
 */

/**
 * Registers a page handler for a particular identifier
 *
 * For example, you can register a function called 'blog_page_handler' for the identifier 'blog'
 * For all URLs  http://yoururl/blog/*, the blog_page_handler() function will be called.
 * The part of the URL marked with * above will be exploded on '/' characters and passed as an
 * array to that function.
 * For example, the URL http://yoururl/blog/username/friends/ would result in the call:
 * blog_page_handler(array('username','friends'), blog);
 *
 * A request to register a page handler with the same identifier as previously registered
 * handler will replace the previous one.
 *
 * The context is set to the identifier before the registered
 * page handler function is called. For the above example, the context is set to 'blog'.
 *
 * Page handlers should return true to indicate that they handled the request.
 * Requests not handled are forwarded to the front page with a reason of 404.
 * Plugins can register for the 'forward', '404' plugin hook. @see forward()
 *
 * @param string $identifier The page type identifier
 * @param string $function   Your function name
 *
 * @return bool Depending on success
 */
function elgg_register_page_handler($identifier, $function) {
	return _elgg_services()->router->registerPageHandler($identifier, $function);
}

/**
 * Unregister a page handler for an identifier
 *
 * Note: to replace a page handler, call elgg_register_page_handler()
 *
 * @param string $identifier The page type identifier
 *
 * @since 1.7.2
 * @return void
 */
function elgg_unregister_page_handler($identifier) {
	_elgg_services()->router->unregisterPageHandler($identifier);
}

/**
 * Front page handler
 * 
 * @return bool
 */
function elgg_front_page_handler() {
	elgg_set_context('main');

	// this plugin hook is deprecated. Use elgg_register_page_handler() to 
	// register for the '' (empty string) handler
	// allow plugins to override the front page (return true to stop this front page code)
	$result = elgg_trigger_plugin_hook('index', 'system', null, false);
	if ($result === true) {
		elgg_deprecated_notice("The 'index', 'system' plugin has been deprecated. See elgg_front_page_handler()", 1.9);
		exit;
	}

	if (elgg_is_logged_in()) {
		forward('activity');
	}

	$content = elgg_view_title(elgg_echo('content:latest'));
	$content .= elgg_list_river();

	$login_box = elgg_view('core/account/login_box');

	$params = array(
			'content' => $content,
			'sidebar' => $login_box
	);
	$body = elgg_view_layout('one_sidebar', $params);
	echo elgg_view_page(null, $body);
	return true;
}

/**
 * Serve an error page
 *
 * @todo not sending status codes yet
 *
 * @param string $hook   The name of the hook
 * @param string $type   The type of the hook
 * @param bool   $result The current value of the hook
 * @param array  $params Parameters related to the hook
 * @return void
 */
function elgg_error_page_handler($hook, $type, $result, $params) {
	if (elgg_view_exists("errors/$type")) {
		$title = elgg_echo("error:$type:title");
		if ($title == "error:$type:title") {
			// use default if there is no title for this error type
			$title = elgg_echo("error:default:title");
		}
		
		$content = elgg_view("errors/$type", $params);
	} else {
		$title = elgg_echo("error:default:title");
		$content = elgg_view("errors/default", $params);
	}
	
	$body = elgg_view_layout('error', array(
		'title' => $title,
		'content' => $content
	));
	echo elgg_view_page($title, $body, 'error');
	exit;
}

/**
 * Initializes the page handler/routing system
 *
 * @return void
 * @access private
 */
function _elgg_page_handler_init() {
	elgg_register_page_handler('', 'elgg_front_page_handler');
	// Registered at 600 so that plugins can register at the default 500 and get to run first
	elgg_register_plugin_hook_handler('forward', '404', 'elgg_error_page_handler', 600);
}

elgg_register_event_handler('init', 'system', '_elgg_page_handler_init');
