<?php
global $CFG;
// Gets all the groups owned by a particular user, as specified in $parameter[0],
// and return it in a data structure with the idents of all the users in each group

$ident = (int) $parameter[0];

if (!isset($_SESSION['groups_membership_cache'][$ident]) || (time() - $_SESSION['groups_membership_cache'][$ident]->created > 60)) {
    
    $membership = array();

    if ($groups = get_records_sql('SELECT g.* FROM '.$CFG->prefix.'group_membership gm 
                                      JOIN '.$CFG->prefix.'groups g ON g.ident = gm.group_id 
                                      WHERE user_id = ?',array($ident))) {
        foreach($groups as $group) {
            $tempdata = "";
            
            // @unset($data);
            $tempdata->name = stripslashes($group->name);
            $tempdata->ident = $group->ident;
            /* $members = get_records_sql('SELECT gm.user_id,u.name FROM '.$CFG->prefix.'groups_membership gm
                                           JOIN '.$CFG->prefix.'users u ON u.ident = gm.user_id
                                           WHERE gm.group_id = ?',array($tempdata->ident));
                    $tempdata->members = $members; */
            
            $membership[] = $tempdata;
            
        }
    }
    
    $_SESSION['groups_membership_cache'][$ident]->created = time();
    $_SESSION['groups_membership_cache'][$ident]->data = $membership;
    
}

$run_result = $_SESSION['groups_membership_cache'][$ident]->data;

?>