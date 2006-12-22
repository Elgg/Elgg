<?php

    // Parse search query and send it to the search functions
        
        global $search_exclusions;
        
        $queries = explode("&", $_SERVER['QUERY_STRING']);

        $tagtypes = array();
        
        foreach($queries as $query) {
            $pair = explode("=", $query);
            if ($pair[0] == "category") {
                $tagtypes[] = $pair[1];
            } else if ($pair[0] == "tag") {
                $tag = $pair[1];
            }
        }
        
        if (!empty($tagtypes)) {
            
            $tag = $parameter;
            $displaytag = htmlspecialchars($parameter, ENT_COMPAT, 'utf-8');
            $sitename = sitename;
            $url = url . "tag/" . $displaytag;
            
            $run_result .= "<result serverName=\"".htmlspecialchars(sitename, ENT_COMPAT, 'utf-8')."\" serverUrl=\"".url."\">\n";
            
            foreach($tagtypes as $tagtype) {
                
                if (!isset($search_exclusions) || !in_array($tagtype,$search_exclusions)) {
                    $run_result .= run("search:display_results:ecl", array($tagtype,$tag));
                }
                
            }
            // $run_result .= run("search:display_results:ecl", array("onceonly",$tag));
            
            $run_result .= "</result>\n";
        }

?>