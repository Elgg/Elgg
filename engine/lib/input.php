<?php
	/**
	 * Parameter input functions.
	 * This file contains functions for getting input from get/post variables.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
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
		
		if (isset($CONFIG->input[$variable]))
			return $CONFIG->input[$variable];
		
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
	 * Kses filtering of tags, called on a plugin hook
	 *
	 * @param mixed $var Variable to filter
	 * @return mixed
	 */
	function kses_filter_tags($hook, $entity_type, $returnvalue, $params)
	{
		$return = $returnvalue;
		$var = $returnvalue;
		
		if (@include_once(dirname(dirname(dirname(__FILE__)))) . "/vendors/kses/kses.php") {
			
			global $CONFIG;
			
			$allowedtags = $CONFIG->allowedtags; 
			$allowedprotocols = $CONFIG->allowedprotocols;
			
			if (!is_array($var)) {
				$return = "";
				$return = kses($var, $allowedtags, $allowedprotocols);
			} else {
				$return = array();
				
				foreach($var as $key => $el) {
					$return[$key] = kses($el, $allowedtags, $allowedprotocols);
				}
			}
		}
	
		return $return;
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
		
		
		global $CONFIG;
		$CONFIG->allowedtags = array(
		'address' => array(),
		'a' => array(
			'class' => array (),
			'href' => array (),
			'id' => array (),
			'title' => array (),
			'rel' => array (),
			'rev' => array (),
			'name' => array (),
			'target' => array()),
		'abbr' => array(
			'class' => array (),
			'title' => array ()),
		'acronym' => array(
			'title' => array ()),
		'b' => array(),
		'big' => array(),
		'blockquote' => array(
			'id' => array (),
			'cite' => array (),
			'class' => array(),
			'lang' => array(),
			'xml:lang' => array()),
		'br' => array (
			'class' => array ()),
		'button' => array(
			'disabled' => array (),
			'name' => array (),
			'type' => array (),
			'value' => array ()),
		'caption' => array(
			'align' => array (),
			'class' => array ()),
		'cite' => array (
			'class' => array(),
			'dir' => array(),
			'lang' => array(),
			'title' => array ()),
		'code' => array (
			'style' => array()),
		'col' => array(
			'align' => array (),
			'char' => array (),
			'charoff' => array (),
			'span' => array (),
			'dir' => array(),
			'style' => array (),
			'valign' => array (),
			'width' => array ()),
		'del' => array(
			'datetime' => array ()),
		'dd' => array(),
		'div' => array(
			'align' => array (),
			'class' => array (),
			'dir' => array (),
			'lang' => array(),
			'style' => array (),
			'xml:lang' => array()),
		'dl' => array(),
		'dt' => array(),
		'em' => array(),
		'fieldset' => array(),
		'font' => array(
			'color' => array (),
			'face' => array (),
			'size' => array ()),
//		'form' => array(
//			'action' => array (),
//			'accept' => array (),
//			'accept-charset' => array (),
//			'enctype' => array (),
//			'method' => array (),
//			'name' => array (),
//			'target' => array ()),
		'h1' => array(
			'align' => array (),
			'class' => array ()),
		'h2' => array(
			'align' => array (),
			'class' => array ()),
		'h3' => array(
			'align' => array (),
			'class' => array ()),
		'h4' => array(
			'align' => array (),
			'class' => array ()),
		'h5' => array(
			'align' => array (),
			'class' => array ()),
		'h6' => array(
			'align' => array (),
			'class' => array ()),
		'hr' => array(
			'align' => array (),
			'class' => array (),
			'noshade' => array (),
			'size' => array (),
			'width' => array ()),
		'i' => array(),
		'img' => array(
			'alt' => array (),
			'align' => array (),
			'border' => array (),
			'class' => array (),
			'height' => array (),
			'hspace' => array (),
			'longdesc' => array (),
			'vspace' => array (),
			'src' => array (),
			'style' => array (),
			'width' => array ()),
		'ins' => array(
			'datetime' => array (),
			'cite' => array ()),
		'kbd' => array(),
		'label' => array(
			'for' => array ()),
		'legend' => array(
			'align' => array ()),
		'li' => array (
			'align' => array (),
			'class' => array ()),
		'p' => array(
			'class' => array (),
			'align' => array (),
			'dir' => array(),
			'lang' => array(),
			'style' => array (),
			'xml:lang' => array()),
		'pre' => array(
			'style' => array(),
			'width' => array ()),
		'q' => array(
			'cite' => array ()),
		's' => array(),
		'span' => array (
			'class' => array (),
			'dir' => array (),
			'align' => array (),
			'lang' => array (),
			'style' => array (),
			'title' => array (),
			'xml:lang' => array()),
		'strike' => array(),
		'strong' => array(),
		'sub' => array(),
		'sup' => array(),
		'table' => array(
			'align' => array (),
			'bgcolor' => array (),
			'border' => array (),
			'cellpadding' => array (),
			'cellspacing' => array (),
			'class' => array (),
			'dir' => array(),
			'id' => array(),
			'rules' => array (),
			'style' => array (),
			'summary' => array (),
			'width' => array ()),
		'tbody' => array(
			'align' => array (),
			'char' => array (),
			'charoff' => array (),
			'valign' => array ()),
		'td' => array(
			'abbr' => array (),
			'align' => array (),
			'axis' => array (),
			'bgcolor' => array (),
			'char' => array (),
			'charoff' => array (),
			'class' => array (),
			'colspan' => array (),
			'dir' => array(),
			'headers' => array (),
			'height' => array (),
			'nowrap' => array (),
			'rowspan' => array (),
			'scope' => array (),
			'style' => array (),
			'valign' => array (),
			'width' => array ()),
		'textarea' => array(
			'cols' => array (),
			'rows' => array (),
			'disabled' => array (),
			'name' => array (),
			'readonly' => array ()),
		'tfoot' => array(
			'align' => array (),
			'char' => array (),
			'class' => array (),
			'charoff' => array (),
			'valign' => array ()),
		'th' => array(
			'abbr' => array (),
			'align' => array (),
			'axis' => array (),
			'bgcolor' => array (),
			'char' => array (),
			'charoff' => array (),
			'class' => array (),
			'colspan' => array (),
			'headers' => array (),
			'height' => array (),
			'nowrap' => array (),
			'rowspan' => array (),
			'scope' => array (),
			'valign' => array (),
			'width' => array ()),
		'thead' => array(
			'align' => array (),
			'char' => array (),
			'charoff' => array (),
			'class' => array (),
			'valign' => array ()),
		'title' => array(),
		'tr' => array(
			'align' => array (),
			'bgcolor' => array (),
			'char' => array (),
			'charoff' => array (),
			'class' => array (),
			'style' => array (),
			'valign' => array ()),
		'tt' => array(),
		'u' => array(),
		'ul' => array (
			'class' => array (),
			'style' => array (),
			'type' => array ()),
		'ol' => array (
			'class' => array (),
			'start' => array (),
			'style' => array (),
			'type' => array ()),
		'var' => array ());
		
		$CONFIG->allowedprotocols = array('http', 'https', 'ftp', 'news', 'mailto', 'rtsp', 'teamspeak', 'gopher', 'mms',
                           'color', 'callto', 'cursor', 'text-align', 'font-size', 'font-weight', 'font-style', 
                           'border', 'margin', 'padding', 'float');
		
		// For now, register the kses for processing
		register_plugin_hook('validate', 'input', 'kses_filter_tags', 1);
	}
	
	register_elgg_event_handler('init','system','input_init');
        
	
?>