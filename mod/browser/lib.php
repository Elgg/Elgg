<?php

    function browser_pagesetup() {
        // register links -- 
        global $profile_id;
        global $PAGE;
        global $CFG;
    
        $page_owner = $profile_id;
    
        if (defined("context") && context == "network") {
            /*
            $PAGE->menu_sub[] = array( 'name' => 'friend',
                                           'html' => a_href("{$CFG->wwwroot}mod/browser/index.php" ,
                                                              __gettext("Browse users and communities"))); 
            */
        }
    }


    // Advanced search function
    
        function browser_advanced_search($type, $display, $filter) {
            
            $sql = "";
            $filter = addslashes($filter);
            
            $filter = preg_replace("/([A-Za-z_0-9]*)\:\:/i","",$filter);
            
            $sort = optional_param('sort', '');
            $sortcriteria = optional_param('sortcriteria', '');
            $recent = optional_param('recent','');
            
            switch($display) {
                
                case "users":
                                    $where = "u.user_type = 'person'";
                                    break;
                case "groups":
                                    $where = "u.user_type = 'community'";
                                    break;
                default:
                                    $where = "1 = 1";
                                    break;
                
            }
            
            if ($sort == "yes") {
                
                switch($sortcriteria) {
                    case "1-10":
                                                $where .= " and members > 0 and members < 11";
                                                break;
                    case "11-100":
                                                $where .= " and members > 10 and members < 101";
                                                break;
                    case "101-1000":
                                                $where .= " and members > 100 and members < 1001";
                                                break;
                    case "1000":
                                                $where .= " and members > 1000";
                                                break;
                }
                
            }
            
            
            switch($type) {
                
                case "name":
                                    $sql = "SELECT distinct u.*, count(distinct m.ident) as members from elggusers u join elggfriends m on m.friend = u.ident where $where and u.name like \"%$filter%\" group by u.ident order by members desc, name desc";
                                    break;
                case "email":       $sql = "SELECT distinct u.*, count(distinct m.ident) as members from elggtags t join elggusers u on u.ident = t.owner join elggfriends m on m.friend = u.ident where $where and t.tag = \"$filter\" and t.tagtype = \"emailaddress\" group by u.ident order by members desc, name desc";
                                    break;
                case "interests":   $sql = "SELECT distinct u.*, count(distinct m.ident) as members from elggtags t join elggusers u on u.ident = t.owner join elggfriends m on m.friend = u.ident where $where and t.tag = \"$filter\" and t.tagtype = \"interests\" group by u.ident order by members desc, name desc";
                                    break;
                case "schools":     $sql = "SELECT distinct u.*, count(distinct m.ident) as members from elggprofile_data p join elggusers u on u.ident = p.owner join elggfriends m on m.friend = u.ident where $where and p.value like \"%$filter%\" and (p.name = \"highschool\" or p.name = \"university\") group by u.ident order by members desc, name desc";
                                    break;
                case "expertise":   $sql = "SELECT distinct u.*, count(distinct m.ident) as members from elggtags t join elggusers u on u.ident = t.owner join elggfriends m on m.friend = u.ident where $where and t.tag = \"$filter\" and t.tagtype = \"skills\" group by u.ident order by members desc, name desc";
                                    break;
                case "language":    $sql = "SELECT distinct u.*, count(distinct m.ident) as members from elggtags t join elggusers u on u.ident = t.owner join elggfriends m on m.friend = u.ident where $where and t.tag = \"$filter\" and t.tagtype = \"languages\" group by u.ident order by members desc, name desc";
                                    break;
                
            }
            

            return $sql;
            
        }


?>