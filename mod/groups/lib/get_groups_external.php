<?php

global $CFG;
// Gets all the groups owned by a particular user, as specified in $parameter[0],
// and return it in a data structure with the idents of all the users in each group

$ident = (int) $parameter[0];

//if (!isset($_SESSION['groups_cache']) || (time() - $_SESSION['groups_cache']->created > 60)) {

$where1 = run("users:access_level_sql_where",$ident);
$groupslist = array();

if ($groups = get_records_sql('SELECT g.name,g.ident,g.access,g.owner,u.name AS ownership,
                               u.ident AS owneruserid,u.username AS ownerusername
                               FROM '.$CFG->prefix.'groups_membership gm
                               LEFT JOIN '.$CFG->prefix.'groups g ON g.ident = gm.group_id
                               LEFT JOIN '.$CFG->prefix.'users u ON u.ident = g.owner
                               WHERE ('.$where1.') AND gm.userid = '.$ident)) {
    foreach($groups as $group) {
        $tempdata = "";
        // @unset($data);
        $tempdata->name = stripslashes($group->name);
        $tempdata->ident = $group->ident;
        $tempdata->access = $group->access;
        $tempdata->ownername = stripslashes($group->ownername);
        $tempdata->ownerusername = $group->ownerusername;
        $tempdata->owneruserid = $group->owneruserid;
        $groupslist[] = $tempdata;
        
    }
}

$_SESSION['groups_cache']->created = time();
$_SESSION['groups_cache']->data = $groupslist;

//}

$run_result = $_SESSION['groups_cache']->data;

?>