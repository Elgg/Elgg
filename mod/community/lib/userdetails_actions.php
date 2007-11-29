<?php

// Userdetails actions
global $USER, $messages, $page_owner;

$id = optional_param('id',0,PARAM_INT);
$action = optional_param('action');

if (logged_on && !empty($action) && user_info("user_type",$id) == "community" && run("permissions:check", array("userdetails:change",$id))) {
    
    switch($action) {
        
        case "userdetails:update":
        
        $community_owner = trim(optional_param('community_owner'));
        if (!empty($community_owner)) {
            if ($new_owner = user_info_username("ident",$community_owner)) {
                if (user_info("user_type",$new_owner) != "community") {
                    if ($info = get_record('users','ident',$id)) {
                        
                        $info->owner = $new_owner;
                        update_record('users',$info);
                        $messages[] = sprintf(__gettext("Community ownership transferred to %s."),$community_owner);
                        
                    } else {
                        $messages[] = __gettext("Could not retrieve community details.");
                    }
                } else {
                    $messages[] = sprintf(__gettext("Could not find new owner %s. Community owner not changed."),$community_owner);
                }
            }
        }
        break;
        
    }
    
}
