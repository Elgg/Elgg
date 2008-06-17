<?php

    /*
    
        Elgg Dashboard
        http://elgg.org/
    
    */

    // Load Elgg framework
        @require_once("../../includes.php");

    // Define context
        define("context","profile");
            
    // Load global variables
        global $CFG, $PAGE, $page_owner;
    
    // Get the current user
        $user_id = optional_param('owner',$_SESSION['userid'],PARAM_INT); 
        $page_owner = $user_id;
        
    // Initialise page body and title
        $body = "";
        $title = __gettext("Add widgets");
        
        templates_page_setup();
        
    // Are we eligible to edit this?
        if (run("permissions:check","profile")) {
            
        // Load external data
            $action = trim(optional_param('action'));
            $widget_type = trim(optional_param('widget_type'));
            
        // If we've been asked to add an item, do that
            if (!empty($action) && $action == "widgets:add" && !empty($widget_type)) {
                
                $widget = new stdClass;
                $widget->owner = $page_owner;
                $widget->type = $widget_type;
                $widget->access = $CFG->default_access;
                $widget->location = "profile";
                $widget->location_id = 0;               
                $widget_id = insert_record('widgets',$widget);
                
                $messages[] = __gettext("Widget added.");
                $_SESSION['messages'] = $messages;
                
                widget_reorder($page_owner,'profile',0);
                
                $username = user_info("username",$page_owner);
                header("Location: " .$CFG->wwwroot."mod/widget/edit.php?wid=" . $widget_id);
                exit;
                
            }
            
        // Iterate through the types of widgets we can add
            if (is_array($CFG->widgets->list) && !empty($CFG->widgets->list)) {
                
                $body = "<p>" . __gettext("Select a widget type from the list below:") . "</p>";
                
                foreach($CFG->widgets->list as $widget) {
	                if (!$widget['type']) {
		                $widget['type'] = $widget['id'];
	                }
                    $body .= "<form action=\"" . $CFG->wwwroot . "mod/profile/add.php\" method=\"post\" >";
                    $body .= "<div class=\"widget\">\n";
                    $body .= "<h2>" . $widget['name'] . "</h2>";
                    $body .= "<p>" . $widget['description'] . "</p>";
                    $body .= "<p><input type=\"hidden\" name=\"action\" value=\"widgets:add\" />";
                    $body .= "<input type=\"hidden\" name=\"owner\" value=\"$page_owner\" />";
                    $body .= "<input type=\"hidden\" name=\"widget_type\" value=\"" . $widget['type'] . "\" />";
                    $body .= "<input type=\"submit\" value=\"" . __gettext("Add") . "\" /></p>";
                    $body .= "</div>";
                    $body .= "</form>";
                    
                    if ($i % 2 == 1 ||
                        $i == (sizeof($CFG->widgets->list)-1)) {
                        $body .= "</div>\n";
                    }
                }
                
            }                

        } else {
            $body = "<p>" . __gettext("You do not have permission to add widgets to this profile.") . "</p>";
        }
        
        templates_page_output($title, $body);

?>