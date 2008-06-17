<?php

    // Returns an SQL "where" clause containing all the access codes that the user can see
    
    
        if (isloggedin() && isadmin($_SESSION['userid'])) {
            $run_result = ' 1=1 '; // allow admins to access any object
        }
        elseif (isloggedin()) {
            
            if (empty($run_result)) {
                $run_result = '';
            } else {
                $run_result .= ' OR ';
            }
            
            $run_result .= " owner = " . $_SESSION['userid'] . " ";
            $run_result .= " OR access IN ('PUBLIC', 'LOGGED_IN', 'user" . $_SESSION['userid'] . "') ";

        } else {
            
            $run_result = " access = 'PUBLIC' ";
            
         }
        
?>