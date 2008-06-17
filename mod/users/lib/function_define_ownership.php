<?php

    // Initially define the current page as either being owned by the current user,
    // or the null user with ID -1
    
        global $page_owner;
    
        if (logged_on) {
            
            $page_owner = $_SESSION['userid'];
            
        } else {
            
            $page_owner = -1;
            
        }

?>