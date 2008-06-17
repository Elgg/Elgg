<?php

    // Last modified Ben Werdmuller May 19 2005

    // Check permissions
    // run("permissions:check", "object");
    
    // To add permission functionality to your own units, add code to
    // $function['permissions:check'] in your main.php
    
    // You can use this file as a template; $page_owner should already
    // be set - if the user is on a page specific to your unit, it should
    // be set in run("your_unit_name:init")
    
        global $messages,$profile_id;
        
        
        if ($parameter == "weblog") {
            if ($profile_id == $_SESSION['userid'] && logged_on && user_info("user_type",$profile_id) != "external") {
                $run_result = true;
            }
        }
       
        if (logged_on) {
            // $parameter[0] = context
            // $parameter1[1] = $post->owner
            if (is_array($parameter) && $parameter[0] == "weblog:edit") {
                if ($parameter[1] == $_SESSION['userid'] && logged_on && user_info("user_type",$profile_id) != "external") {
                    $run_result = true;
                }
            }
        }
?>