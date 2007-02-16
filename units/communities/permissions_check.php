<?php
global $USER;
global $CFG;
global $page_owner;
    
if (isset($parameter) && $page_owner != -1) {
    if (!is_array($parameter)) {
        switch($parameter) {
            
        case    "profile":  
            if (record_exists('users','ident',$page_owner,'owner',$USER->ident,'user_type','community')) {
                $run_result = true;
            }
            break;
        case    "files":
        case    "weblog":
            if (record_exists('users','ident',$page_owner,'owner',$USER->ident,'user_type','community')) {
                $run_result = true;
            }
            if (empty($run_result)) {
                if (count_records_sql('SELECT count(u.ident) FROM '.$CFG->prefix.'friends f
                                                 JOIN '.$CFG->prefix.'users u ON u.ident = f.friend
                                                 WHERE u.ident = ? AND f.owner = ? AND u.user_type = ?',
                                      array($page_owner,$USER->ident,'community'))) {
                    $run_result = true;
                }
            }
            break;
        case     "uploadicons":
            if (record_exists('users','ident',$page_owner,'owner',$USER->ident,'user_type','community')) {
                $run_result = true;
            }
            break;
        case    "userdetails:change":
            if (record_exists('users','ident',$page_owner,'owner',$USER->ident,'user_type','community')) {
                $run_result = true;
            }
            break;
        }
    } else {
        switch($parameter[0]) {
        case    "files:edit":
        case    "weblog:edit":    
            $owner = $parameter[1];
            if (record_exists('users','ident',$owner,'owner',$USER->ident,'user_type','community')) {
                $run_result = true;
            }
            if (empty($run_result)) {
                if (count_records_sql('SELECT count(u.ident) FROM '.$CFG->prefix.'friends f
                                                 JOIN '.$CFG->prefix.'users u ON u.ident = f.friend
                                                 WHERE u.ident = ? AND f.owner = ? AND u.user_type = ?',
                                      array($owner,$USER->ident,'community'))) {
                    $run_result = true;
                }
            }
            break;
        case    "userdetails:change":
            if (record_exists('users','ident',$parameter[1],'owner',$USER->ident,'user_type','community')) {
                $run_result = true;
            }
            break;
        }
    }
}

?>