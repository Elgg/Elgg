<?php

    //    ELGG profile search page

        global $PAGE, $db, $search_exclusions;
    
    // Run includes
        require_once(dirname(dirname(__FILE__))."/includes.php");
        
        run("search:init");
        run("search:all:tagtypes");

        define("context","search");
                
        $tag = optional_param('tag');
        $title = __gettext("Searching") . " : " . $tag;
        templates_page_setup();
        
        $PAGE->search_type_unformatted = optional_param('user_type','');
        $PAGE->search_type = $db->qstr($PAGE->search_type_unformatted);
        
        $PAGE->returned_items = optional_param('returned_items','');
        
        $querystring = "";
        if (isset($_GET) && !empty($_GET)) {
            foreach($_GET as $key => $value) {
                if ($key != 'user_type') {
                    if (!empty($querystring)) {
                        $querystring .= "&";
                    }
                    $querystring .= urlencode($key);
                    $querystring .= "=";
                    $querystring .= urlencode(optional_param($key,''));
                }
            }
        }
        $querystring = $CFG->wwwroot . "search/index.php?" . $querystring;
        
        $body = "<p>" . __gettext("Search for results from:") . " ";
        
        if ($PAGE->search_type  == "''") {
            $body .= "<b class=\"selected_search_type\">";
        }
        $body .= "<a href=\"$querystring\">" . __gettext("All") . "</a> ";
        if ($PAGE->search_type  == "''") {
            $body .= "</b>";
        }
              
        if (isset($PAGE->search_menu) && is_array($PAGE->search_menu) && !empty($PAGE->search_menu)) {
            foreach($PAGE->search_menu as $search_type) {
                if ($PAGE->search_type_unformatted == $search_type['user_type']) {
                    $body .= "<b class=\"selected_search_type\">";
                }
                $body .= "<a href=\"$querystring&user_type=" . $search_type['user_type'] . "\">" . $search_type['name'] . "</a> ";
                if ($PAGE->search_type_unformatted == $search_type['user_type']) {
                    $body .= "</b>";
                }
            }
        }
        $body .= "</p>";
        
        
        
        $querystring = "";
        if (isset($_GET) && !empty($_GET)) {
            foreach($_GET as $key => $value) {
                if ($key != 'returned_items') {
                    if (!empty($querystring)) {
                        $querystring .= "&";
                    }
                    $querystring .= urlencode($key);
                    $querystring .= "=";
                    $querystring .= urlencode(optional_param($key,''));
                }
            }
        }
        $querystring = $CFG->wwwroot . "search/index.php?" . $querystring;
        
        $body .= "<p>" . __gettext("Filter by content type:") . " ";
        if (empty($PAGE->returned_items)) {
            $body .= "<b class=\"search_displaying_type\">";
        }
        $body .= "<a href=\"$querystring\">" . __gettext("Everything") . "</a> ";
        if (empty($PAGE->returned_items)) {
            $body .= "</b>";
        }
        if ($PAGE->returned_items == 'accounts') {
            $body .= "<b class=\"search_displaying_type\">";
        }
        $body .= "<a href=\"$querystring&returned_items=accounts\">" . __gettext("Users and communities") . "</a> ";
        if ($PAGE->returned_items == 'accounts') {
            $body .= "</b>";
        }
        if ($PAGE->returned_items == 'resources') {
            $body .= "<b class=\"search_displaying_type\">";
        }
        $body .= "<a href=\"$querystring&returned_items=resources\">" . __gettext("Resources") . "</a> ";
        if ($PAGE->returned_items == 'resources') {
            $body .= "</b>";
        }
        
        
        $body .= run("search:display");
        
        $body = templates_draw(array(
                        'context' => 'contentholder',
                        'title' => $title,
                        'body' => $body
                    )
                    );
                    
        echo templates_page_draw( array(
                    $title, $body
                )
                );

?>