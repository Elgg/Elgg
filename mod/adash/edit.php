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

        // Load external variables
            $action = trim(optional_param('action'));
        // Get widget details
            $ident = optional_param('widget',0,PARAM_INT);
            $ident = $db->qstr($ident);
            $widget = get_record_sql("select * from ".$CFG->prefix."dashboard_widgets where ident = $ident");
        // Page owner = where the widget resides
            $page_owner = $widget->owner;
            
        // Initialise page
            $body = "";
            $title = __gettext("Edit widget");
            templates_page_setup();
            $page_owner = $widget->owner;
        
        // If action is specified
        
            if (!empty($action) && $action == "dashboard:widget:save") {
                
                // Get the username of the widget owner
                    $username = user_info("username",$widget->owner);
                    
                // Get any data
                    $dashboard_data = optional_param('dashboard_data');
                    
                    if (!empty($dashboard_data)) {
                        
                        adash_remove_data($widget->ident);
                        
                        if (is_array($dashboard_data)) {
                            foreach($dashboard_data as $key => $data) {
                                adash_set_data($key, $widget->ident, $data);
                            }
                        }
                    }
                    
                // Save access
                    $widget->access = optional_param('dashboard_access',"user".$_SESSION['userid']);
                    update_record('dashboard_widgets',$widget);
                    
                // Add a message
                    $messages[] = __gettext("Widget edited.");
                    $_SESSION['messages'] = $messages;
                    
                // Redirect back to the dashboard
                    header("Location: " . $CFG->wwwroot . $username . "/dashboard/");
                    exit;
                
            }
            
        // Do we have permission to touch this?
        // If so, display edit screen
            if (run("permissions:check","profile")) {
                
                $body = adash_widget_edit($widget);
                
            } else {
                
                $body = "<p>" . __gettext("You do not have permission to edit this widget.") . "</p>";
            }
        
        }
        
        // Output to the screen
        $body = templates_draw(array(
                        'context' => 'contentholder',
                        'title' => $title,
                        'body' => $body
                    )
                    );
        
        echo templates_page_draw( array(
                        $title, $body
                    )
                    );
        
?>