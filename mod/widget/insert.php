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
            global $CFG, $page_owner;
            
        // Get widget details
            $insert = optional_param('insert',0,PARAM_INT);
            $before = optional_param('before',0,PARAM_INT);
            $insert_widget = get_record("widgets", "ident", $insert);
            $before_widget = get_record("widgets", "ident", $before);
            
            if (is_object($insert_widget) && is_object($before_widget)) {
                // Page owner = where the widget resides
                $page_owner = $insert_widget->owner;
                
                // Do we have permission to touch this?
                // If so, reorder widgets!
                if (run("permissions:check","profile")) {
                    
                    $insert_widget->display_order = $before_widget->display_order - 5;
                    update_record('widgets',$insert_widget);
                    widget_reorder($page_owner,$insert_widget->location,$insert_widget->location_id);
                    
                }
            }
            
        // no real need to return a response, but just in case
        // someone looks for one
            
        header("Content-Type: application/xml");
        print '<ajax-response>Success!</ajax-response>';
        
        }
        
?>