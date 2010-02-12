<?php
/**
 * Elgg page handler functions
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

/**
 * Turns the current page over to the page handler, allowing registered handlers to take over
 *
 * @param string $handler The name of the handler type (eg 'blog')
 * @param array $page The parameters to the page, as an array (exploded by '/' slashes)
 * @return true|false Depending on whether a registered page handler was found
 */
function page_handler($handler, $page) {
	global $CONFIG;

	set_context($handler);

	// if there are any query parameters, make them available from get_input
	if (strpos($_SERVER['REQUEST_URI'], '?') !== FALSE) {
		$query = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], '?') + 1);
		if (isset($query)) {
			$query_arr = elgg_parse_str($query);
			if (is_array($query_arr)) {
				foreach($query_arr as $name => $val) {
					set_input($name, $val);
				}
			}
		}
	}

	// if page url ends in a / then last element of $page is an empty string
	$page = explode('/',$page);

	if (!isset($CONFIG->pagehandler) || empty($handler)) {
		$result = false;
	} else if (isset($CONFIG->pagehandler[$handler]) && is_callable($CONFIG->pagehandler[$handler])) {
		$function = $CONFIG->pagehandler[$handler];
		$result = $function($page, $handler);
		if ($result !== false) {
			$result = true;
		}
	} else {
		$result = false;
	}

	if (!$result) {
		$result = default_page_handler($page, $handler);
	}
	if ($result !== false) {
		$result = true;
	}

	return $result;
}

/**
 * Registers a page handler for a particular identifier
 *
 * For example, you can register a function called 'blog_page_handler' for handler type 'blog'
 * Now for all URLs of type http://yoururl/pg/blog/*, the blog_page_handler() function will be called.
 * The part of the URL marked with * above will be exploded on '/' characters and passed as an
 * array to that function.
 * For example, the URL http://yoururl/blog/username/friends/ would result in the call:
 * blog_page_handler(array('username','friends'), blog);
 *
 * Page handler functions should return true or the default page handler will be called.
 *
 * A request to register a page handler with the same identifier as previously registered
 * handler will replace the previous one.
 *
 * The context is set to the page handler identifier before the registered
 * page handler function is called. For the above example, the context is set to 'blog'.
 *
 * @param string $handler The page type to handle
 * @param string $function Your function name
 * @return true|false Depending on success
 */
function register_page_handler($handler, $function) {
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
 * A default page handler that attempts to load the actual file at a given page handler location
 *
 * @param array $page The page URL elements
 * @param string $handler The base handler
 * @return true|false Depending on success
 */
function default_page_handler($page, $handler) {
	global $CONFIG;
	$script = "";

	$page = implode('/',$page);
	if (($questionmark = strripos($page, '?'))) {
		$page = substr($page, 0, $questionmark);
	}
	$script = str_replace("..","",$script);
	$callpath = $CONFIG->path . $handler . "/" . $page;
	if (!file_exists($callpath) || is_dir($callpath) || substr_count($callpath,'.php') == 0) {
			if (substr($callpath,strlen($callpath) - 1, 1) != "/") {
				$callpath .= "/";
			}
			$callpath .= "index.php";
			if (!include($callpath)) {
				return false;
			}
	} else {
		include($callpath);
	}

	return true;
}