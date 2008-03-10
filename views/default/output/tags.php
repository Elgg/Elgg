<?php
    if (!empty($vars['tags']) && is_array($vars['tags'])) {
        
        $string = "";
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
               $tagstr .= "<a href=\"{$vars['url']}search/?tag=".urlencode($tag->tag) . "{$type}\">{$tag->tag}</a>";
           }
        }
        echo $tagstr;
        
    }
?>