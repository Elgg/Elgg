<?php

    // Parse search query and send it to the search functions
    
        global $search_exclusions;
        $search_exclusions = array();
        if (isset($_GET) || (isset($parameter) && sizeof($parameter) == 2)) {
            
            if ((isset($parameter) && sizeof($parameter) == 2)) {
                $query[$parameter[0]] = $parameter[1];
            } else {
                $query = $_GET;
            }
            
    // A flag to see if we've actually had any results displayed
    
            foreach($query as $field => $value) {
                if ($field != user_session_name && $field != "ref" && $field != "owner" 
                    && $field != "psaContext" && $field != "PHPSESSID" 
                    && $field != "user_type" && $field != "returned_items") {
                    // $searchline = "tagtype = '".addslashes($field)."' and tag = '".addslashes($value)."'";
                    $run_result .= run("search:display_results", array($field,$value));
                    $search_exclusions[] = $field;
                    $run_result .= run("search:all:display",$value);
                    $run_result .= run("search:tags:suggest",$value);
                }
                
            }
            
        }

?>