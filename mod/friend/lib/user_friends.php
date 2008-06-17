<?php
global $CFG,$USER;

if (logged_on) {
    $friends = array();
    if ($result = get_records_sql('SELECT u.ident,1 FROM '.$CFG->prefix.'friends
                                   JOIN '.$CFG->prefix.'users u ON u.ident = f.friend
                                   WHERE owner = ? AND u.user_type = ? LIMIT 8'
                                  ,array($USER->ident,'person'))) {
        foreach($result as $row) {
            $friends[] = $row->ident;
        }
    }
    run("users:infobox",array(".". __gettext("Your Friends") ."",array($friends),"<a href=\"friends/\">". __gettext("Friends Screen") ."</a>"));
    
}

?>