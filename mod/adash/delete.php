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
            $ident = optional_param('widget',0,PARAM_INT);
            $ident = $db->qstr($ident);
            $widget = get_record_sql("select * from ".$CFG->prefix."dashboard_widgets where ident = $ident");
            
        // Page owner = where the widget resides
            $page_owner = $widget->owner;
            
        // Do we have permission to touch this?
        // If so, wipe it!
            if (run("permissions:check","profile")) {
                
                adash_widget_destroy($widget->ident);
                adash_widgets_reorder($page_owner);
                
            }
            
        // Get the username of the widget owner
            $username = user_info("username",$widget->owner);
            
        // Add a message
            $messages[] = __gettext("Widget deleted.");
            $_SESSION['messages'] = $messages;
            
        // Redirect back to the dashboard
            header("Location: " . $CFG->wwwroot . $username . "/dashboard/");
            exit;
        
        }
        
?>