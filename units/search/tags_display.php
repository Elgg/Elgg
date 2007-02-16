<?php
global $USER,$CFG;
global $db;

// Display popular tags

$run_result .= "<p>" . __gettext("The following is a selection of keywords used within this site. Click one to see related users, weblog posts or objects.") . "</p>";

$searchline = "(" . run("users:access_level_sql_where",$USER->ident) . ")";

if ($tags = get_records_sql('SELECT DISTINCT tag,count(ident) AS number, '.$db->random.' AS rand 
                             FROM '.$CFG->prefix."tags WHERE access = ?
                             GROUP BY tag having number > 1 order by rand limit 200",array('PUBLIC'))) {
    $max = 0;
    foreach($tags as $tag) {
        if ($tag->number > $max) {
            $max = $tag->number;
        }
    }
    
    $tag_count = 0;
    $run_result .= "<div id=\"tagcloud\"><p>";
    foreach($tags as $tag) {
        
        if ($max > 1) {
            $size = round((log($tag->number) / log($max)) * 200) + 100;
        } else {
            $size = 100;
        }
        
        $tag->tag = stripslashes($tag->tag);
        $run_result .= "<a href=\"".url."tag/".urlencode(htmlspecialchars(strtolower(($tag->tag)), ENT_COMPAT, 'utf-8'))."\" style=\"font-size: $size%\" title=\"".htmlspecialchars($tag->tag, ENT_COMPAT, 'utf-8')." (" .$tag->number. ")\">";
        $run_result .= $tag->tag . "</a>";
        if ($tag_count < sizeof($tags) - 1) {
            $run_result .= ", ";
        }
        $tag_count++;
    }
    
    $run_result .= "</p></div>";
    
}

?>