<?php
	/**
	 * Elgg XML library.
	 * Contains functions for generating and parsing XML.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * This function serialises an object recursively into an XML representation.
	 * The function attempts to call $data->export() which expects a stdClass in return, otherwise it will attempt to
	 * get the object variables using get_object_vars (which will only return public variables!)
	 * @param $data object The object to serialise.
	 * @param $n int Level, only used for recursion.
	 * @return string The serialised XML output.
	 */
	function serialise_object_to_xml($data, $name = "", $n = 0)
	{
		$classname = ($name=="" ? get_class($data) : $name);
		
		$vars = method_exists($data, "export") ? get_object_vars($data->export()) : get_object_vars($data); 
		
		$output = "";
		
		if (($n==0) || ( is_object($data) && !($data instanceof stdClass))) $output = "<$classname>";

		foreach ($vars as $key => $value)
		{
			$output .= "<$key type=\"".gettype($value)."\">";
			
			if (is_object($value))
				$output .= serialise_object_to_xml($value, $key, $n+1);
			else if (is_array($value))
				$output .= serialise_array_to_xml($value, $n+1);
			else
				$output .= htmlentities($value);
			
			$output .= "</$key>\n";
		}
		
		if (($n==0) || ( is_object($data) && !($data instanceof stdClass))) $output .= "</$classname>\n";
		
		return $output;
	}

	/**
	 * Serialise an array.
	 *
	 * @param array $data
	 * @param int $n Used for recursion
	 * @return string
	 */
	function serialise_array_to_xml(array $data, $n = 0)
	{
		$output = "";
		
		if ($n==0) $output = "<array>\n";
		
		foreach ($data as $key => $value)
		{
			$item = "array_item";
			
			if (is_numeric($key))
				$output .= "<$item name=\"$key\" type=\"".gettype($value)."\">";
			else
			{
				$item = $key;
				$output .= "<$item type=\"".gettype($value)."\">";
			}
			
			if (is_object($value))
				$output .= serialise_object_to_xml($value, "", $n+1);
			else if (is_array($value))
				$output .= serialise_array_to_xml($value, $n+1);
			else
				$output .= htmlentities($value);
			
			$output .= "</$item>\n";
		}
		
		if ($n==0) $output = "</array>\n";
		
		return $output;
	}
	
	/**
	 * XML 2 Array function.
	 * Taken from http://www.bytemycode.com/snippets/snippet/445/
	 * @license UNKNOWN - Please contact if you are the original author of this code.
	 * @author UNKNOWN
	 */
	function xml2array($xml) 
	{
        $xmlary = array();
               
        $reels = '/<(\w+)\s*([^\/>]*)\s*(?:\/>|>(.*)<\/\s*\\1\s*>)/s';
        $reattrs = '/(\w+)=(?:"|\')([^"\']*)(:?"|\')/';

        preg_match_all($reels, $xml, $elements);

        foreach ($elements[1] as $ie => $xx) {
	        $xmlary[$ie]["name"] = $elements[1][$ie];
	       
	        if ($attributes = trim($elements[2][$ie])) {
	                preg_match_all($reattrs, $attributes, $att);
	                foreach ($att[1] as $ia => $xx)
	                        $xmlary[$ie]["attributes"][$att[1][$ia]] = $att[2][$ia];
	        }
	
	        $cdend = strpos($elements[3][$ie], "<");
	        if ($cdend > 0) {
	                $xmlary[$ie]["text"] = substr($elements[3][$ie], 0, $cdend - 1);
	        }
	
	        if (preg_match($reels, $elements[3][$ie]))
	                $xmlary[$ie]["elements"] = __xml2array($elements[3][$ie]);
	        else if ($elements[3][$ie]) {
	                $xmlary[$ie]["text"] = $elements[3][$ie];
	        }
        }

        return $xmlary;
	}
?>