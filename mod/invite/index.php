<?php
    //    ELGG invite-a-friend page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/../includes.php");
        
     //error_log ("Aver");   
        run("profile:init");
        run("invite:init");
     
     //error_log ("Aver 2");   
        define("context", "network");
        templates_page_setup();

    // You must be logged on to view this!
        if (logged_on && $CFG->publicinvite == true) {
            
            $title = __gettext("Invite a Friend");
            
            $body = run("content:invite:invite");
            $body .= run("invite:invite");
            
            templates_page_output($title, $body);

        } else {
            header("Location: " . $CFG->wwwroot);
        }

?>
