<?php

// Get communities

global $USER;
global $CFG;

if ($communities = get_records_select('users','owner = ? AND user_type = ?',array($USER->ident,'community'))) {
    foreach($communities as $community) {
        $data['access'][] = array(__gettext("Community") .": " . $community->name, "community" . $community->ident);
    }
}

if ($communities = get_records_sql("SELECT u.* FROM ".$CFG->prefix."friends f
                                    JOIN ".$CFG->prefix.'users u ON u.ident = f.friend 
                                    WHERE u.user_type = ? AND u.owner <> ? AND f.owner = ?',
                                   array('community',$USER->ident,$USER->ident))) {
    foreach($communities as $community) {
        $data['access'][] = array(__gettext("Community") . ": " . $community->name, "community" . $community->ident);
        
    }
}

?>