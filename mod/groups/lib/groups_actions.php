<?php
global $USER;
// Actions to perform on the groups screen
$action = optional_param('action');

if (!empty($action)) {
    switch ($action) {
        // Create a new group
    case "group:create":
        $g = new StdClass;
        $g->name = trim(optional_param('name'));
        if (logged_on && !empty($g->name)) {
            $g->owner = $USER->ident;
            insert_record('groups',$g);
            unset($_SESSION['groups_cache']);
        }
        break;
        // Edit a group
    case "group:edit":
        $g = new StdClass;
        $g->name = trim(optional_param('groupname'));
        $g->owner = $USER->ident;
        $g->ident = optional_param('groupid',0,PARAM_INT);
        if (logged_on && !empty($g->ident) && !empty($g->name)) {
            if (update_record('groups',$g)) {
                unset($_SESSION['groups_cache']);
                $messages[] = __gettext("Your group was updated.");
            }
            foreach($data['access'] as $key => $accessarray) {
                if ($accessarray[1] == "group" . $g->ident) {
                    $data['access'][$key] = array(stripslashes($g->name),"group" . $g->ident);
                }
            }
        }
        break;
        // Delete a group
    case "group:delete":
        $groupid = optional_param('groupid',0,PARAM_INT);
        if (logged_on && !empty($groupid)) {
            $ident = $USER->ident;
            run("groups:delete",$groupid);
            // check before we delete the memberships, because of the owner clause.
            if (record_exists('groups','ident',$groupid,'owner',$ident)) { 
                delete_records('groups','ident',$groupid,'owner',$ident);
                delete_records('group_membership','group_id',$group_id);
            }
            unset($_SESSION['groups_cache']);
        } else {
            // var_export($_POST);
        }
        break;
        // Add someone to a group
    case "group:addmember":
        $groupid = optional_param('groupid',0,PARAM_INT);
        if (logged_on && !empty($groupid)) {
            $ident = $USER->ident;
            if (record_exists('groups','ident',$groupid,'owner',$ident)) {
                $friends = optional_param('friends',array(),PARAM_INT);
                foreach($friends as $newmember) {
                    $newmember = $newmember;
                    if (!record_exists('group_membership','user_id',$newmember,'group_id',$groupid)) {
                        $gm->user_id = $newmember;
                        $gm->group_id = $groupid;
                        insert_record('group_membership',$gm);
                    }
                } 
            } 
            unset($_SESSION['groups_cache']);
        }
        break;
        // Remove someone from a group
    case "group:removemember":
        $groupid = optional_param('groupid',0,PARAM_INT);
        if (logged_on && !empty($groupid)) {
            $ident = $USER->ident;
            if (record_exists('groups','ident',$groupid,'owner',$ident)) {
                $members = optional_param('members',array(),PARAM_INT);
                foreach($members as $newmember) {
                    delete_records('group_membership','user_id',$newmember,'group_id',$groupid);
                }
            }
            unset($_SESSION['groups_cache']);
        }
        break;
                
    }

}
            

