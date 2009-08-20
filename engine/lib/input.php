<?php
	/**
	 * Parameter input functions.
	 * This file contains functions for getting input from get/post variables.
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd <info@elgg.com>

	 * @link http://elgg.org/
	 */

	/**
	 * Get some input from variables passed on the GET or POST line.
	 * 
	 * @param $variable string The variable we want to return.
	 * @param $default mixed A default value for the variable if it is not found.
	 * @param $filter_result If true then the result is filtered for bad tags.
	 */
	function get_input($variable, $default = "", $filter_result = true)
	{

		global $CONFIG;
		
		if (isset($CONFIG->input[$variable])) {
			$var = $CONFIG->input[$variable];
			
			if ($filter_result)
				$var = filter_tags($var);
				
			return $var;
		}
		
		if (isset($_REQUEST[$variable])) {
			
			if (is_array($_REQUEST[$variable])) {
				$var = $_REQUEST[$variable];
			} else {
				$var = trim($_REQUEST[$variable]);
			}
			
			if ($filter_result)
				$var = filter_tags($var);

			return $var;
			
		}

		return $default;

	}
	
	/**
	 * Sets an input value that may later be retrieved by get_input
	 *
	 * @param string $variable The name of the variable
	 * @param string $value The value of the variable
	 */
	function set_input($variable, $value) {
		
		global $CONFIG;
		if (!isset($CONFIG->input))
			$CONFIG->input = array();
					
		if (is_array($value))
		{
			foreach ($value as $key => $val)
				$value[$key] = trim($val);
			
			$CONFIG->input[trim($variable)] = $value;
		}
		else
			$CONFIG->input[trim($variable)] = trim($value);
			
	}
	
	/**
	 * Filter tags from a given string based on registered hooks.
	 * @param $var
	 * @return mixed The filtered result
	 */
	function filter_tags($var)
	{
		return trigger_plugin_hook('validate', 'input', null, $var);
	}
	
	/**
	 * Sanitise file paths for input, ensuring that they begin and end with slashes etc.
	 *
	 * @param string $path The path
	 * @return string
	 */
	function sanitise_filepath($path)
	{
		// Convert to correct UNIX paths
		$path = str_replace('\\', '/', $path);
		
		// Sort trailing slash
		$path = trim($path);
		$path = rtrim($path, " /");
		$path = $path . "/";
		
		return $path;
	}
	
	
    /**
     * Takes a string and turns any URLs into formatted links
     * 
     * @param string $text The input string
     * @return string The output stirng with formatted links
     **/
    function parse_urls($text) {
       
       	return preg_replace_callback('/(?<!=["\'])((ht|f)tps?:\/\/[^\s\r\n\t<>"\'\!\(\)]+)/i', 
       	create_function(
            '$matches',
            '
            	$url = $matches[1];
            	$urltext = str_replace("/", "/<wbr />", $url);
            	return "<a href=\"$url\" style=\"text-decoration:underline;\">$urltext</a>";
            '
        ), $text);
    }
	
	function autop($pee, $br = 1) {
		$pee = $pee . "\n"; // just to make things a little easier, pad the end
		$pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);
		// Space things out a little
		$allblocks = '(?:table|thead|tfoot|caption|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|map|area|blockquote|address|math|style|input|p|h[1-6]|hr)';
		$pee = preg_replace('!(<' . $allblocks . '[^>]*>)!', "\n$1", $pee);
		$pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);
		$pee = str_replace(array("\r\n", "\r"), "\n", $pee); // cross-platform newlines
		if ( strpos($pee, '<object') !== false ) {
			$pee = preg_replace('|\s*<param([^>]*)>\s*|', "<param$1>", $pee); // no pee inside object/embed
			$pee = preg_replace('|\s*</embed>\s*|', '</embed>', $pee);
		}
		$pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
		$pee = preg_replace('/\n?(.+?)(?:\n\s*\n|\z)/s', "<p>$1</p>\n", $pee); // make paragraphs, including one at the end
		$pee = preg_replace('|<p>\s*?</p>|', '', $pee); // under certain strange conditions it could create a P of entirely whitespace
		$pee = preg_replace('!<p>([^<]+)\s*?(</(?:div|address|form)[^>]*>)!', "<p>$1</p>$2", $pee);
		$pee = preg_replace( '|<p>|', "$1<p>", $pee );
		$pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee); // don't pee all over a tag
		$pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
		$pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
		$pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
		$pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);
		$pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);
		if ($br) {
			$pee = preg_replace_callback('/<(script|style).*?<\/\\1>/s', create_function('$matches', 'return str_replace("\n", "<WPPreserveNewline />", $matches[0]);'), $pee);
			$pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
			$pee = str_replace('<WPPreserveNewline />', "\n", $pee);
		}
		$pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);
		$pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
		if (strpos($pee, '<pre') !== false)
			$pee = preg_replace_callback('!(<pre.*?>)(.*?)</pre>!is', 'clean_pre', $pee );
		$pee = preg_replace( "|\n</p>$|", '</p>', $pee );
	
		return $pee;
	}
        
	function input_init() {
		
		if (ini_get_bool('magic_quotes_gpc') ) {
		    
		    //do keys as well, cos array_map ignores them
		    function stripslashes_arraykeys($array) {
		        if (is_array($array)) {
		            $array2 = array();
		            foreach ($array as $key => $data) {
		                if ($key != stripslashes($key)) {
		                    $array2[stripslashes($key)] = $data;
		                } else {
		                    $array2[$key] = $data;
		                }
		            }
		            return $array2;
		        } else {
		            return $array;
		        }
		    }
		    
		    function stripslashes_deep($value) {
		        if (is_array($value)) {
		            $value = stripslashes_arraykeys($value);
		            $value = array_map('stripslashes_deep', $value);
		        } else {
		            $value = stripslashes($value);
		        }
		        return $value;
		    }
		    
		    $_POST = stripslashes_arraykeys($_POST);
		    $_GET = stripslashes_arraykeys($_GET);
		    $_COOKIE = stripslashes_arraykeys($_COOKIE);
		    $_REQUEST = stripslashes_arraykeys($_REQUEST);
		    
		    $_POST = array_map('stripslashes_deep', $_POST);
		    $_GET = array_map('stripslashes_deep', $_GET);
		    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
		    $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
		    if (!empty($_SERVER['REQUEST_URI'])) {
		        $_SERVER['REQUEST_URI'] = stripslashes($_SERVER['REQUEST_URI']);
		    }
		    if (!empty($_SERVER['QUERY_STRING'])) {
		        $_SERVER['QUERY_STRING'] = stripslashes($_SERVER['QUERY_STRING']);
		    }
		    if (!empty($_SERVER['HTTP_REFERER'])) {
		        $_SERVER['HTTP_REFERER'] = stripslashes($_SERVER['HTTP_REFERER']);
		    }
		    if (!empty($_SERVER['PATH_INFO'])) {
		        $_SERVER['PATH_INFO'] = stripslashes($_SERVER['PATH_INFO']);
		    }
		    if (!empty($_SERVER['PHP_SELF'])) {
		        $_SERVER['PHP_SELF'] = stripslashes($_SERVER['PHP_SELF']);
		    }
		    if (!empty($_SERVER['PATH_TRANSLATED'])) {
		        $_SERVER['PATH_TRANSLATED'] = stripslashes($_SERVER['PATH_TRANSLATED']);
		    }
		    
		}
		
	}
	
	register_elgg_event_handler('init','system','input_init');
        
	
?>