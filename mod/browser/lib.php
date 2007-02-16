<?php

    function browser_pagesetup() {
        // register links -- 
        global $profile_id;
        global $PAGE;
        global $CFG;
    
        $page_owner = $profile_id;
    
        /*
        if (defined("context") && context == "network") {
            $PAGE->menu_sub[] = array( 'name' => 'friend',
                                           'html' => a_href("{$CFG->wwwroot}mod/browser/index.php" ,
                                                              __gettext("Browse users and communities"))); 
        }
        */
    }


    // Advanced search function
    function browser_advanced_search($type, $display, $filter) {
        
        global $CFG, $db;
        $sql = "";
        
        $filter = preg_replace("/([A-Za-z_0-9]*)\:\:/i", "", $filter);
        
        $sort = optional_param('sort', '');
        $sortcriteria = optional_param('sortcriteria', '');
        $recent = optional_param('recent','');
        
        switch($display) {
            
            case "users":
                                $where = " u.user_type = 'person' ";
                                break;
            case "communities":
                                $where = " u.user_type = 'community' ";
                                break;
            default:
                                $where = " 1 ";
                                break;
            
        }
        
        $having = '';
        if ($sort == "yes") {
            
            switch($sortcriteria) {
                case "1-10":
                                $having = " HAVING members > 0 AND members < 11";
                                break;
                case "11-100":
                                $having = " HAVING members > 10 AND members < 101";
                                break;
                case "101-1000":
                                $having = " HAVING members > 100 AND members < 1001";
                                break;
                case "1000":
                                $having = " HAVING members > 1000";
                                break;
            }
            
        }
        
        $formatted_filter = $db->qstr($filter);
        $formatted_filter_wild = $db->qstr("%" . $filter . "%");
        
        $sql = "SELECT u.ident, u.username, u.name, u.icon, u.user_type, COUNT(m.ident) AS members "; // assumes GROUP BY u.ident
        switch($type) {
            
            //wildcards
            case "name":        $sql .= "FROM ".$CFG->prefix."users u JOIN ".$CFG->prefix."friends m ON m.friend = u.ident WHERE $where AND u.name LIKE " . $formatted_filter_wild . " GROUP BY u.ident $having ORDER BY members DESC, name DESC";
                                break;
            case "schools":     $sql .= "FROM ".$CFG->prefix."users u JOIN ".$CFG->prefix."profile_data p ON u.ident = p.owner JOIN ".$CFG->prefix."friends m ON m.friend = u.ident WHERE $where AND p.value LIKE " . $formatted_filter_wild . " AND p.name IN ('highschool', 'university') GROUP BY u.ident $having ORDER BY members DESC, name DESC";
                                break;
            
            // equals
            case "email":       $sql .= "FROM ".$CFG->prefix."users u JOIN ".$CFG->prefix."tags t ON u.ident = t.owner JOIN ".$CFG->prefix."friends m ON m.friend = u.ident WHERE $where AND t.tag = " . $formatted_filter . " AND t.tagtype = 'emailaddress' GROUP BY u.ident $having ORDER BY members DESC, name DESC";
                                break;
            case "interests":   $sql .= "FROM ".$CFG->prefix."users u JOIN ".$CFG->prefix."tags t ON u.ident = t.owner JOIN ".$CFG->prefix."friends m ON m.friend = u.ident WHERE $where AND t.tag = " . $formatted_filter . " AND t.tagtype = 'interests' GROUP BY u.ident $having ORDER BY members DESC, name DESC";
                                break;
            case "expertise":   $sql .= "FROM ".$CFG->prefix."users u JOIN ".$CFG->prefix."tags t ON u.ident = t.owner JOIN ".$CFG->prefix."friends m ON m.friend = u.ident WHERE $where AND t.tag = " . $formatted_filter . " AND t.tagtype = 'skills' GROUP BY u.ident $having ORDER BY members DESC, name DESC";
                                break;
            case "language":    $sql .= "FROM ".$CFG->prefix."users u JOIN ".$CFG->prefix."tags t ON u.ident = t.owner JOIN ".$CFG->prefix."friends m ON m.friend = u.ident WHERE $where AND t.tag = " . $formatted_filter . " AND t.tagtype = 'languages' GROUP BY u.ident $having ORDER BY members DESC, name DESC";
                                break;
            
        }

        return $sql;
    }

?>