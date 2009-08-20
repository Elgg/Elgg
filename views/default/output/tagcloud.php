<?php

	/**
	 * Elgg tagcloud
	 * Displays a tagcloud
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

	 * @link http://elgg.org/
	 * 
	 * @uses $vars['tagcloud'] An array of stdClass objects with two elements: 'tag' (the text of the tag) and 'total' (the number of elements with this tag) 
	 * 
	 */
    
	if (!empty($vars['subtype'])) {
		$subtype = "&subtype=" . urlencode($vars['subtype']);
	} else {
		$subtype = "";
	}
	if (!empty($vars['object'])) {
		$object = "&object=" . urlencode($vars['object']);
	} else {
		$object = "";
	}
	
	if (empty($vars['tagcloud']) && !empty($vars['value']))
		$vars['tagcloud'] = $vars['value'];

    if (!empty($vars['tagcloud']) && is_array($vars['tagcloud'])) {
        
        $counter = 0;
        $cloud = "";
        $max = 0;
        foreach($vars['tagcloud'] as $tag) {
        	if ($tag->total > $max) {
        		$max = $tag->total;
        	}
        }
        foreach($vars['tagcloud'] as $tag) {
            if (!empty($cloud)) $cloud .= ", ";
            $size = round((log($tag->total) / log($max)) * 100) + 30;
            if ($size < 60) $size = 60;
            $cloud .= "<a href=\"" . $vars['url'] . "search/?tag=". urlencode($tag->tag) . $object . $subtype . "\" style=\"font-size: {$size}%\" title=\"".addslashes($tag->tag)." ({$tag->total})\" style=\"text-decoration:none;\">" . htmlentities($tag->tag, ENT_QUOTES, 'UTF-8') . "</a>";
        }
        echo $cloud;

    }
     
?>