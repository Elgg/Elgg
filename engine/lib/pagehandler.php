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
		
		$query = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], '?')+1);//parse_url($_SERVER['REQUEST_URI']);
		if (isset($query)) {
			parse_str($query, $query_arr);
			if (is_array($query_arr)) {
				foreach($query_arr as $name => $val) {
					set_input($name, $val);
				}
			}
		} 
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
		if ($result !== false) $result = true;		
		
		return $result;
		
	}
	
	/**
	 * Registers a page handler for a particular identifier
	 * 
	 * eg, you can register a function called 'blog_page_handler' for handler type 'blog'
	 * 
	 * Now for all URLs of type http://yoururl/blog/*, the blog_page_handler function will be called.
	 * The part of the URL marked with * above will be exploded on '/' characters and passed as an
	 * array to that function, eg:
	 * 
	 * For the URL http://yoururl/blog/username/friends/:
	 * blog_page_handler('blog', array('username','friends')); 
	 *
	 * @param string $handler The page type to handle
	 * @param string $function Your function name
	 * @return true|false Depending on success
	 */
	function register_page_handler($handler, $function) {
		
		global $CONFIG;
		if (!isset($CONFIG->pagehandler))
			$CONFIG->pagehandler = array();
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
		if (($questionmark = strripos($page, '?')))
			$page = substr($page, 0, $questionmark);

		$script = str_replace("..","",$script);
		$callpath = $CONFIG->path . $handler . "/" . $page;
		if (!file_exists($callpath) || is_dir($callpath) || substr_count($callpath,'.php') == 0) {
				if (substr($callpath,strlen($callpath) - 1, 1) != "/")
					$callpath .= "/";
				$callpath .= "index.php";
				if (!include($callpath))
					return false; 
		} else {
			 include($callpath);
		}
		
		return true;
		
	}

?>