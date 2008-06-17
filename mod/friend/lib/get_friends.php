<?php
global $CFG;
// Gets all the friends of a particular user, as specified in $parameter[0],
// and return it in a data structure with the idents of all the users
    
$ident = (int) $parameter[0];
/*
        if (!isset($_SESSION['friends_cache'][$ident]) || (time() - $_SESSION['friends_cache'][$ident]->created > 120)) {
            $_SESSION['friends_cache'][$ident]->created = time();
            $_SESSION['friends_cache'][$ident]->data =  get_records_sql('SELECT f.friend AS user_id,u.name FROM '.$CFG->prefix.'friends f
                               JOIN '.$CFG->prefix.'users u ON u.ident = f.friend
                               WHERE f.owner = ?',array($ident));
        }
        $run_result = $_SESSION['friends_cache'][$ident]->data;
*/

$usertype = user_type($ident);
if ($usertype == "community") {

    $run_result = get_records_sql('SELECT u.ident AS user_id, u.name FROM '.$CFG->prefix.'friends f
                                   JOIN '.$CFG->prefix.'users u ON u.ident = f.owner
                                   WHERE friend = ? ORDER BY u.name',array($ident));

} else {

    $run_result = get_records_sql('SELECT f.friend AS user_id,u.name FROM '.$CFG->prefix.'friends f
                                   JOIN '.$CFG->prefix.'users u ON u.ident = f.friend
                                   WHERE f.owner = ? ORDER BY u.name',array($ident));

}

?>