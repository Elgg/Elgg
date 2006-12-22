<?php

    /*
    
        Elgg Dashboard
        http://elgg.org/
    
    */

    // Load Elgg framework
        @require_once("../../includes.php");

    // Define context
        define("context","dashboard");
            
    // Load global variables
        global $CFG, $PAGE, $page_owner;
    
    // Get the current user
        $user_id = optional_param('owner',$_SESSION['userid'],PARAM_INT); 
        $page_owner = $user_id;
        
    // Initialise page body and title
        $body = "";
        $title = __gettext("Add dashboard items");
        
        templates_page_setup();
        
    // Are we eligible to edit this?
        if (run("permissions:check","profile")) {
            
        // Load external data
            $action = trim(optional_param('action'));
            $widget_id = trim(optional_param('widget_id'));
            
        // If we've been asked to add an item, do that
            if (!empty($action) && $action == "dashboard:widgets:add" && !empty($widget_id)) {
                
                $widget = new stdClass;
                $widget->owner = $page_owner;
                $widget->widget_type = $widget_id;
                $widget->access = "user" . $_SESSION['userid'];                
                $widget_id = insert_record('dashboard_widgets',$widget);
                
                $messages[] = __gettext("Widget added.");
                $_SESSION['messages'] = $messages;
                
                adash_widgets_reorder($page_owner);
                
                $username = user_info("username",$page_owner);
                header("Location: " .$CFG->wwwroot."mod/adash/edit.php?widget=" . $widget_id);
                exit;
                
            }
            
        // Iterate through the types of widgets we can add
            if (is_array($CFG->widgets->list) && !empty($CFG->widgets->list)) {
                
                $body = "<p>" . __gettext("Select a widget type from the list below:") . "</p>";
                
                foreach($CFG->widgets->list as $widget) {
                    $body .= "<form action=\"" . $CFG->wwwroot . "mod/adash/add.php\" method=\"post\" >";
                    $body .= "<div class=\"dashboard_widget\">\n";
                    $body .= "<h2>" . $widget['name'] . "</h2>";
                    $body .= "<p>" . $widget['description'] . "</p>";
                    $body .= "<p><input type=\"hidden\" name=\"action\" value=\"dashboard:widgets:add\" />";
                    $body .= "<input type=\"hidden\" name=\"owner\" value=\"$page_owner\" />";
                    $body .= "<input type=\"hidden\" name=\"widget_id\" value=\"" . $widget['id'] . "\" />";
                    $body .= "<input type=\"submit\" value=\"" . __gettext("Add") . "\" /></p>";
                    $body .= "</div>";
                    $body .= "</form>";
                    
                }
                
            }                

        } else {
            $body = "<p>" . __gettext("You do not have permission to add widgets to this dashboard.") . "</p>";
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