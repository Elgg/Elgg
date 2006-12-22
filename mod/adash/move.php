<?php

    /*
    
        Elgg Dashboard
        http://elgg.org/
    
    */

    // Load Elgg framework
        @require_once("../../includes.php");

    // We need to be logged on for this!
        
        if (isloggedin()) {
    
        // Define context
            define("context","dashboard");
                
        // Load global variables
            global $CFG, $PAGE, $db, $page_owner, $messages;    
    
        // Get widget details
            $ident = optional_param('widget',PARAM_INT,0);
            $ident = $db->qstr($ident);
            $widget = get_record_sql("select * from ".$CFG->prefix."dashboard_widgets where ident = $ident");
            
        // Get moving instructions
            $move = optional_param('move');
            
        // Page owner = where the widget resides
            $page_owner = $widget->owner;
            
        // Do we have permission to touch this?
        // If so, move it
            if (run("permissions:check","profile")) {
                
                if ($move == "up") {
                    adash_widget_moveup($widget);
                } else if ($move == "down") {
                    adash_widget_movedown($widget);
                }
                
            }
            
        // Get the username of the widget owner
            $username = user_info("username",$widget->owner);
            
        // Add a message
            $messages[] = __gettext("Widget moved.");
            $_SESSION['messages'] = $messages;
            
        // Redirect back to the dashboard
            header("Location: " . $CFG->wwwroot . $username . "/dashboard/");
            exit;
        
        }
        
?>