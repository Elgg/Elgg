<?php

global $CFG;

// Gets all the groups owned by a particular user, as specified in $parameter[0],
// and return it in a data structure with the idents of all the users in each group

$ident = (int) $parameter[0];

//if (!isset($_SESSION['groups_cache']) || (time() - $_SESSION['groups_cache']->created > 60)) {

$tempdata = "";
$groupslist = array();

if ($ident) {
    if ($groups = get_records('groups','owner',$ident)) {
        foreach($groups as $group) {
            
            $tempdata = "";
            
            // @unset($data);
            $tempdata->name = stripslashes($group->name);
            $tempdata->ident = $group->ident;
            $tempdata->access = $group->access;
            $members = get_records_sql("SELECT gm.user_id,
                                        u.name FROM ".$CFG->prefix."group_membership gm
                                        JOIN ".$CFG->prefix."users u ON u.ident = gm.user_id
                                        WHERE gm.group_id = ? ORDER BY u.name", array($tempdata->ident));
            $tempdata->members = $members;
            
            $groupslist[] = $tempdata;
            
        }
    }
}
$_SESSION['groups_cache']->created = time();
$_SESSION['groups_cache']->data = $groupslist;

//}

$run_result = $_SESSION['groups_cache']->data;

?>