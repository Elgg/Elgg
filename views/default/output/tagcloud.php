<?php

    /** 
     * This is a generic view that will display a tag cloud for any
     * section; photos, services, resources and a user or group
     **/
    
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
            $cloud .= "<a href=\"" . $vars['url'] . "search/?tag=". urlencode($tag->tag) . "&type=".urlencode($tag->tag_type)."\" style=\"font-size: {$size}%\" title=\"".addslashes($tag->tag)." ({$tag->total})\" style=\"text-decoration:none;\">" .$tag->tag . "</a>";
        }
        echo $cloud;

    }
     
?>