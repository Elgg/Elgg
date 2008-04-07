<?php
    if (!empty($vars['tags'])) {
        
        $string = "";
        if (!is_array($vars['tags']))
        	$vars['tags'] = array($vars['tags']);

        foreach($vars['tags'] as $tag) {
            if (!empty($tagstr)) {
                $tagstr .= ", ";
            }
           if (!empty($vars['type'])) {
               $type = "&type={$vars['type']}";
           } else {
               $type = "";
           }
           if (is_string($tag)) {
               $tagstr .= "<a href=\"{$vars['url']}search/?tag=".urlencode($tag) . "{$type}\">{$tag}</a>";
           } else {
               $tagstr .= "<a href=\"{$vars['url']}search/?tag=".urlencode($tag->value) . "{$type}\">{$tag->value}</a>";
           }
        }
        echo $tagstr;
        
    }
?>