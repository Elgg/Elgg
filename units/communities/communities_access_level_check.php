<?php

global $USER,$CFG;

if (substr_count($parameter, "community") > 0 && logged_on) {
    $commnum = (int) substr($parameter, 9, 15);
    if ($result = get_record_sql('SELECT f.owner FROM '.$CFG->prefix."friends f 
                                  JOIN ".$CFG->prefix.'users u ON u.ident = f.friend
                                  WHERE u.user_type = ? AND u.ident = ? AND f.owner = ?',
                                 array('community',$commnum,$USER->ident))) {  
        $run_result = true;
    } else {
        // $run_result = record_exists('users','user_type','community','owner',$USER->ident);
        $run_result = record_exists('users','user_type','community','ident',$commnum,'owner',$USER->ident);
    }
}

?>