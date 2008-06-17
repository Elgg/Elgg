<?php
global $CFG, $db, $PAGE;

if (isset($parameter) && $parameter) {
    
    if (!empty($PAGE->search_type_unformatted)) {
                $typeline = " AND user_type = " . $PAGE->search_type;
            } else {
                $typeline = "";
            }
            
    if (FALSE && $CFG->dbtype == 'mysql') {
        // FIXME: full-text indexing turned off because it will only search for whole words and not parts
        $dbname = $db->qstr($parameter);
        $searchline = "SELECT DISTINCT name,username,MATCH(name) AGAINST (" . $dbname . ") AS score
                       FROM ".$CFG->prefix."users WHERE (MATCH(name) AGAINST (" . $dbname . ") > 0) $typeline LIMIT 20";
    } else {
        $dbname = $db->qstr("%" . $parameter . "%");
        $searchline = "SELECT DISTINCT username,name,ident 
                       FROM ".$CFG->prefix."users WHERE (name LIKE " . $dbname . " OR username LIKE " . $dbname . ") $typeline LIMIT 20";
    }

    if ($results = get_records_sql($searchline)) {
        if ($PAGE->search_type_unformatted == "person") {
            $run_result .= "<h2>" . __gettext("Matching users:") . "</h2><p>";
        } else if ($PAGE->search_type_unformatted == "community") {
            $run_result .= "<h2>" . __gettext("Matching communities:") . "</h2><p>";
        } else {
            $run_result .= "<h2>" . __gettext("Matching users and communities:") . "</h2><p>";
        }
	/*
        foreach($results as $returned_name) {
            $run_result .= "<a href=\"" . url . $returned_name->username . '/">' . htmlspecialchars($returned_name->name) . "</a> <br />";
        }
	*/

	$body .= <<< END
    <table class="userlist">
        <tr>
END;
	$i = 1;
	$w = 100;
	if (sizeof($result) > 4) {
	  $w = 50;
	}

	foreach($results as $key => $info) {
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


        $run_result .= "</p>";
        
    }
    
}

?>