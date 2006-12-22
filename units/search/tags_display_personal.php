<?php
global $CFG;
// Display a user's tags

global $page_owner; // logged in user
$parameter = (int) $parameter; // user whose tags we're looking at

$searchline = "(" . run("users:access_level_sql_where",$page_owner) . ")";
$searchline = str_replace("access","t.access", $searchline);
if ($tags = get_records_sql('SELECT tag, count(ident) AS number 
                             FROM '.$CFG->prefix.'tags t
                             WHERE '.$searchline.' AND owner = '.$parameter.'
                             GROUP BY tag ASC')) {
    $max = 0;
    foreach($tags as $tag) {
        if ($tag->number > $max) {
            $max = $tag->number;
        }
    }
    
    $tag_count = 0;
    foreach($tags as $tag) {
        
        if ($max > 1) {
            $size = round((log($tag->number) / log($max)) * 300) + 100;
        } else {
            $size = 100;
        }
        
        $tag->tag = stripslashes($tag->tag);
        $run_result .= "<a href=\"".url."search/index.php?all=".urlencode(htmlspecialchars((($tag->tag)), ENT_COMPAT, 'utf-8'))."&amp;owner=$parameter\" style=\"font-size: $size%\" title=\"".htmlspecialchars($tag->tag, ENT_COMPAT, 'utf-8')." (" .$tag->number. ")\">";
        $run_result .= $tag->tag . "</a>";
        if ($tag_count < sizeof($tags) - 1) {
            $run_result .= ", ";
        }
        $tag_count++;
    }
    
} else {
    $run_result = "<p>" . __gettext("No tags found for this user.") . "</p>";
}

?>