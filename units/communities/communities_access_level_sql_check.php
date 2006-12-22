<?php
global $USER,$CFG;
// Returns an SQL "where" clause containing all the access codes that the user can see
    
if (logged_on) {
    $communitieslist = array();
    if ($communities = get_records_sql("SELECT u.* FROM ".$CFG->prefix."friends f
                                   JOIN ".$CFG->prefix."users u ON u.ident = f.friend
                                   WHERE u.user_type = 'community' 
                                   AND u.owner <> ".$USER->ident." 
                                   AND f.owner = ".$USER->ident)) {

        foreach($communities as $community) {
            $communitieslist[] = $community->ident;
        }
    }
    if ($communities = get_records('users','owner',$USER->ident)) {
        foreach($communities as $community) {
            $communitieslist[] = $community->ident;
        }
    }
    if (count($communitieslist) > 0) {
        $communitieslist = array_unique($communitieslist);
        $run_result .= " or access IN ('community" . implode("', 'community", $communitieslist) . "') ";
    }
}

?>