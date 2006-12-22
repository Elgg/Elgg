<?php

    // Last modified Ben Werdmuller Sept 24 2005

    // Check permissions
    // run("permissions:check", "object");
    
    // To add permission functionality to your own units, add code to
    // $function['permissions:check'] in your main.php
    
    // You can use this file as a template; $page_owner should already
    // be set - if the user is on a page specific to your unit, it should
    // be set in run("your_unit_name:init")
    
        global $page_owner;
        
        if (logged_on && user_flag_get("admin", $_SESSION['userid'])) {
            $run_result = true;
        }

?>