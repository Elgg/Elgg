<?php
	/**
	 * Parameter input functions.
	 * This file contains functions for getting input from get/post variables.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Get some input from variables passed on the GET or POST line.
	 * 
	 * @param $variable string The variable we want to return.
	 * @param $default mixed A default value for the variable if it is not found.
	 */
	function get_input($variable, $default = "")
	{

		if (isset($_REQUEST[$variable])) {
			
			if (is_array($_REQUEST[$variable])) {
				$var = $_REQUEST[$variable];
			} else {
				$var = trim($_REQUEST[$variable]);
			}
			
			global $CONFIG;
			if (@include_once(dirname(dirname(dirname(__FILE__)))) . "/vendors/kses/kses.php") {
				$var = kses($var, $CONFIG->allowedtags);
			}
			
			return $var;
			
		}
		
		global $CONFIG;
		
		if (isset($CONFIG->input[$variable]))
			return $CONFIG->input[$variable];

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
		$CONFIG->input[trim($variable)] = trim($value);
			
	}
	
	    /**
        * This is a function to make url clickable
        * @param string text
        * @return string text
        **/
        
       function parse_urls($text) {
           
            if (preg_match_all('/(?<!href=["\'])((ht|f)tps?:\/\/[^\s\r\n\t<>"\'\!\(\)]+)/ie', $text, $urls))   {
               
                foreach (array_unique($urls[1]) AS $url){
                    $urltext = $url;
                    $text = str_replace($url, '<a href="'. $url .'" style="text-decoration:underline;">view link</a>', $text);
                }
            }
            
            return $text;
        }
	
	function input_init() {
		global $CONFIG;
		$CONFIG->allowedtags = array ('address' => array (), 'a' => array ('href' => array (), 'title' => array (), 'rel' => array (), 'rev' => array (), 'name' => array ()), 'abbr' => array ('title' => array ()), 'acronym' => array ('title' => array ()), 'b' => array (), 'big' => array (), 'blockquote' => array ('cite' => array ()), 'br' => array (), 'button' => array ('disabled' => array (), 'name' => array (), 'type' => array (), 'value' => array ()), 'caption' => array ('align' => array ()), 'code' => array (), 'col' => array ('align' => array (), 'char' => array (), 'charoff' => array (), 'span' => array (), 'valign' => array (), 'width' => array ()), 'del' => array ('datetime' => array ()), 'dd' => array (), 'div' => array ('align' => array ()), 'dl' => array (), 'dt' => array (), 'em' => array (), 'fieldset' => array (), 'font' => array ('color' => array (), 'face' => array (), 'size' => array ()), 'form' => array ('action' => array (), 'accept' => array (), 'accept-charset' => array (), 'enctype' => array (), 'method' => array (), 'name' => array (), 'target' => array ()), 'h1' => array ('align' => array ()), 'h2' => array ('align' => array ()), 'h3' => array ('align' => array ()), 'h4' => array ('align' => array ()), 'h5' => array ('align' => array ()), 'h6' => array ('align' => array ()), 'hr' => array ('align' => array (), 'noshade' => array (), 'size' => array (), 'width' => array ()), 'i' => array (), 'img' => array ('alt' => array (), 'align' => array (), 'border' => array (), 'height' => array (), 'hspace' => array (), 'longdesc' => array (), 'vspace' => array (), 'src' => array (), 'width' => array ()), 'ins' => array ('datetime' => array (), 'cite' => array ()), 'kbd' => array (), 'label' => array ('for' => array ()), 'legend' => array ('align' => array ()), 'li' => array (), 'p' => array ('align' => array ()), 'pre' => array ('width' => array ()), 'q' => array ('cite' => array ()), 's' => array (), 'strike' => array (), 'strong' => array (), 'sub' => array (), 'sup' => array (), 'table' => array ('align' => array (), 'bgcolor' => array (), 'border' => array (), 'cellpadding' => array (), 'cellspacing' => array (), 'rules' => array (), 'summary' => array (), 'width' => array ()), 'tbody' => array ('align' => array (), 'char' => array (), 'charoff' => array (), 'valign' => array ()), 'td' => array ('abbr' => array (), 'align' => array (), 'axis' => array (), 'bgcolor' => array (), 'char' => array (), 'charoff' => array (), 'colspan' => array (), 'headers' => array (), 'height' => array (), 'nowrap' => array (), 'rowspan' => array (), 'scope' => array (), 'valign' => array (), 'width' => array ()), 'textarea' => array ('cols' => array (), 'rows' => array (), 'disabled' => array (), 'name' => array (), 'readonly' => array ()), 'tfoot' => array ('align' => array (), 'char' => array (), 'charoff' => array (), 'valign' => array ()), 'th' => array ('abbr' => array (), 'align' => array (), 'axis' => array (), 'bgcolor' => array (), 'char' => array (), 'charoff' => array (), 'colspan' => array (), 'headers' => array (), 'height' => array (), 'nowrap' => array (), 'rowspan' => array (), 'scope' => array (), 'valign' => array (), 'width' => array ()), 'thead' => array ('align' => array (), 'char' => array (), 'charoff' => array (), 'valign' => array ()), 'title' => array (), 'tr' => array ('align' => array (), 'bgcolor' => array (), 'char' => array (), 'charoff' => array (), 'valign' => array ()), 'tt' => array (), 'u' => array (), 'ul' => array (), 'ol' => array (), 'var' => array () );
	}
	
	register_elgg_event_handler('init','system','input_init');
        
	
?>