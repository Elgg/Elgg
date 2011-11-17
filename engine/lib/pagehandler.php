<?php
/**
 * Elgg page handler functions
 *
 * @package Elgg.Core
 * @subpackage Routing
 */

/**
 * Routes the request to a registered page handler
 *
 * This function sets the context based on the handler name (first segment of the
 * URL). It also triggers a plugin hook 'route', $handler so that plugins can
 * modify the routing or handle a request.
 *
 * @param string $handler The name of the handler type (eg 'blog')
 * @param array  $page    The parameters to the page, as an array (exploded by '/' slashes)
 *
 * @return bool
 * @access private
 */
function page_handler($handler, $page) {
	global $CONFIG;

	elgg_set_context($handler);

	$page = explode('/', $page);
	// remove empty array element when page url ends in a / (see #1480)
	if ($page[count($page) - 1] === '') {
		array_pop($page);
	}

	// return false to stop processing the request (because you handled it)
	// return a new $params array if you want to route the request differently
	$params = array(
		'handler' => $handler,
		'segments' => $page,
	);
	$params = elgg_trigger_plugin_hook('route', $handler, NULL, $params);
	if ($params === false) {
		return true;
	}

	$handler = $params['handler'];
	$page = $params['segments'];

	$result = false;
	if (isset($CONFIG->pagehandler) && !empty($handler) && isset($CONFIG->pagehandler[$handler])) {
		$function = $CONFIG->pagehandler[$handler];
		$result = call_user_func($function, $page, $handler);
	}

	return $result || headers_sent();
}

/**
 * Registers a page handler for a particular identifier
 *
 * For example, you can register a function called 'blog_page_handler' for handler type 'blog'
 * For all URLs  http://yoururl/blog/*, the blog_page_handler() function will be called.
 * The part of the URL marked with * above will be exploded on '/' characters and passed as an
 * array to that function.
 * For example, the URL http://yoururl/blog/username/friends/ would result in the call:
 * blog_page_handler(array('username','friends'), blog);
 *
 * A request to register a page handler with the same identifier as previously registered
 * handler will replace the previous one.
 *
 * The context is set to the page handler identifier before the registered
 * page handler function is called. For the above example, the context is set to 'blog'.
 *
 * Page handlers should return true to indicate that they handled the request.
 * Requests not handled are forwarded to the front page with a reason of 404.
 * Plugins can register for the 'forward', '404' plugin hook. @see forward()
 *
 * @param string $handler  The page type to handle
 * @param string $function Your function name
 *
 * @return true|false Depending on success
 */
function elgg_register_page_handler($handler, $function) {
	global $CONFIG;
	if (!isset($CONFIG->pagehandler)) {
		$CONFIG->pagehandler = array();
	}
	if (is_callable($function)) {
		$CONFIG->pagehandler[$handler] = $function;
		return true;
	}

	return false;
}

/**
 * Unregister a page handler for an identifier
 *
 * Note: to replace a page handler, call elgg_register_page_handler()
 *
 * @param string $handler The page type identifier
 *
 * @since 1.7.2
 * @return void
 */
function elgg_unregister_page_handler($handler) {
	global $CONFIG;

	if (!isset($CONFIG->pagehandler)) {
		return;
	}

	unset($CONFIG->pagehandler[$handler]);
}
