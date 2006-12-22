<?php

    // Returns an SQL "where" clause containing all the access codes that the user can see
    
        $run_result = " access = 'PUBLIC' ";
    
        if (logged_on) {
            
            $run_result = " owner = " . $_SESSION['userid'] . " ";
            $run_result .= " OR access IN ('PUBLIC', 'LOGGED_IN', 'user" . $_SESSION['userid'] . "') ";

        } else {
            
            $run_result = " access = 'PUBLIC' ";
            
         }
        
?>