<?php

    // Returns an SQL "where" clause containing all the access codes that the user can see
    
        if (logged_on) {
            
            $groupslist = array();
            
            if ($groups = run("groups:getmembership",array($_SESSION['userid']))) {
                foreach($groups as $group) {
                    $groupslist[] = $group->ident;
                }
                $run_result .= "or access IN ('group" . implode("', 'group", $groupslist) . "')";
            }
            
        }

?>