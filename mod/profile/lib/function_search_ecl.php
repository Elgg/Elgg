<?php
global $CFG;
global $db;
    // Search criteria are passed in $parameter from run("search:display")
    
        $handle = 0;
        foreach($data['profile:details'] as $profiletype) {
            if ($profiletype->internal_name == $parameter[0] && $profiletype->field_type == "keywords") {
                $handle = 1;
            }
        }
    
        if ($handle) {
            
            $sub_result = "";
            
            $searchline = "tagtype = " . $db->qstr($parameter[0]) ." AND tag = " . $db->qstr($parameter[1]) . "";
            $searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") and " . $searchline;
            $searchline = str_replace("owner", "t.owner", $searchline);
            //$parameter[1] = stripslashes($parameter[1]);
            if ($result = get_record_sql('SELECT DISTINCT u.* FROM '.$CFG->prefix.'tags t
                                          JOIN '.$CFG->prefix.'users u ON u.ident = t.owner
                                          WHERE '.$searchline)) {
                foreach($result as $key => $info) {
                    
                    $icon = user_icon_html($info->ident,100,true);
                    
                    $sub_result .= "\t\t\t<item>\n";
                    $sub_result .= "\t\t\t\t<name><![CDATA[" . htmlspecialchars(stripslashes(user_name($info->ident)), ENT_COMPAT, 'utf-8') . "]]></name>\n";
                    $sub_result .= "\t\t\t\t<link>" . url . htmlspecialchars($info->username, ENT_COMPAT, 'utf-8') . "</link>\n";
                    $sub_result .= "\t\t\t\t<link>$icon</link>\n";
                    $sub_result .= "\t\t\t</item>\n";
                }
            }
            
            if ($sub_result != "") {
                
                $run_result .= "\t\t<profiles tagtype=\"".addslashes(htmlspecialchars($parameter[0], ENT_COMPAT, 'utf-8'))."\">\n" . $sub_result . "\t\t</profiles>\n";
                
            }
            
        }

?>