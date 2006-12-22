<?php
global $CFG,$USER;
global $db;

if (isset($parameter)) {
    
    if ($CFG->dbtype == 'mysql') {
        $dbtag = $db->qstr($parameter);
        $searchline = "SELECT DISTINCT tag, MATCH(tag) AGAINST (" . $dbtag . ") AS score 
                       FROM ".$CFG->prefix."tags 
                       WHERE (" . run("users:access_level_sql_where",$USER->ident) . ")
                       AND (MATCH(tag) AGAINST(" . $dbtag . ") > 0) LIMIT 10";
    } else {
        $dbtag = $db->qstr("%" . $parameter . "%");
        $searchline = "SELECT DISTINCT tag,1 
                       FROM ".$CFG->prefix."tags 
                       WHERE (" . run("users:access_level_sql_where",$USER->ident) . ")
                       AND (tag LIKE " . $dbtag . ") LIMIT 10";            
    }
    
    if (($results = get_records_sql($searchline)) && count($results) > 1) {
        $run_result .= "<h2>" . __gettext("Automatic tag suggestion:") . "</h2><p>";
        foreach($results as $returned_tag) {
            if ($returned_tag->tag != $parameter) {
                $run_result .= "<a href=\"".url."tag/".urlencode($returned_tag->tag)."\">" . htmlspecialchars($returned_tag->tag) . "</a> <br />";
            }
        }
        $run_result .= "</p>";
        
    }
    
}

?>