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
            
            $searchline = "tagtype = " . $db->qstr($parameter[0]) . " AND tag = " . $db->qstr($parameter[1]) . "";
            $searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") AND " . $searchline;
            $searchline = str_replace("owner", "t.owner", $searchline);
            //$parameter[1] = stripslashes($parameter[1]);
            if ($result = get_records_sql('SELECT DISTINCT u.* FROM '.$CFG->prefix.'tags t
                                          JOIN '.$CFG->prefix.'users u ON u.ident = t.owner
                                          WHERE '.$searchline)) {
                foreach($result as $key => $info) {
                    $run_result .= "\t<item>\n";
                    $run_result .= "\t\t<title><![CDATA['" . htmlspecialchars($parameter[0], ENT_COMPAT, 'utf-8') . "' = " . htmlspecialchars($parameter[1], ENT_COMPAT, 'utf-8') . " :: " . htmlspecialchars(stripslashes(user_name($info->ident)), ENT_COMPAT, 'utf-8') . "]]></title>\n";
                    $run_result .= "\t\t<link>" . url . htmlspecialchars($info->username, ENT_COMPAT, 'utf-8') . "</link>\n";
                    $run_result .= "\t</item>\n";
                }
            }
        }

?>