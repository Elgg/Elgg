<?php

global $CFG, $db, $PAGE;

    // Search criteria are passed in $parameter from run("search:display")
    
        $handle = 0;
        foreach($data['profile:details'] as $profiletype) {
            if ($profiletype->internal_name == $parameter[0] && $profiletype->field_type == "keywords") {
                $handle = 1;
            } else {
                $icon = "default.png";
            }
        }
        
        if (!empty($PAGE->returned_items) && $PAGE->returned_items == "resources") {
            $handle = 0;
        }
        
        if ($handle) {
            
            $searchline = "tagtype = " . $db->qstr($parameter[0]) . " AND tag = " . $db->qstr($parameter[1]) . "";
            $searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") and " . $searchline;
            if (!empty($PAGE->search_type_unformatted)) {
                $searchline .= " AND u.user_type = " . $PAGE->search_type;
            }
            
            $searchline = str_replace("owner","t.owner",$searchline);
            
            $parameter[1] = stripslashes($parameter[1]);

            if ($result = get_records_sql('SELECT DISTINCT u.* FROM '.$CFG->prefix.'tags t
                                          JOIN '.$CFG->prefix.'users u ON u.ident = t.owner
                                          WHERE '.$searchline)) {
            $profilesMsg = __gettext("Profiles where");
$body = <<< END
            
    <h2>
        $profilesMsg
END;
                $body .= " '".__gettext($parameter[0])."' = '".$parameter[1]."':";
                $body .= <<< END
    </h2>
END;
                $body .= <<< END
    <table class="userlist">
        <tr>
END;
                $i = 1;
                $w = 100;
                if (sizeof($result) > 4) {
                    $w = 50;
                }
                foreach($result as $key => $info) {
                    $friends_username = $info->username;
                    $friends_name = run("profile:display:name",$info->ident);
                    $info->icon = run("icons:get",$info->ident);
                    $friends_icon = user_icon_html($info->ident,$w);
                    $body .= <<< END
        <td align="center">
            <p>
            <a href="{$CFG->wwwroot}{$friends_username}/">
            {$friends_icon}</a><br />
            <span class="userdetails">
                <a href="{$CFG->wwwroot}{$friends_username}/">{$friends_name}</a>
            </span>
            </p>
        </td>
END;
                    if ($i % 5 == 0) {
                        $body .= "</tr><tr>";
                    }
                    $i++;
                }
                $body .= <<< END
    </tr>
    </table>
END;
                $run_result .= $body;
            }
        }

?>