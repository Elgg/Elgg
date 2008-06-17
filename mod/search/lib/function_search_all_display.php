<?php
// Parse search query and send it to the search functions
        
global $search_exclusions;

if (isset($parameter)) {
    
    $tag = $parameter;
    foreach($data['search:tagtypes'] as $tagtype) {
        
        if (!isset($search_exclusions) || !in_array($tagtype,$search_exclusions)) {
            $run_result .= run("search:display_results", array($tagtype,$tag));
        }
        
    }
    if ($tag != "ref" && $tag != "owner" && $tag != user_session_name && $tag != "user_type" && $tag != "returned_items") {
        $run_result .= run("search:tags:suggest",$tag);
        $run_result .= run("search:users:suggest",$tag);
        $run_result .= run("search:rss:suggest",$tag);
    }
    
}

?>