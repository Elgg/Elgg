<?php

// Get groups
    
if ($groups = run("groups:get",array($_SESSION['userid']))) {
    foreach($groups as $group) {
        
        $data['access'][] = array(__gettext("Group") . ": " . $group->name, "group" . $group->ident);
        
    }
}

?>