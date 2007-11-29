<?php

    /*
    
        Elgg Widgets
        http://elgg.org/
    
    */

    // Load Elgg framework
        @require_once("../../includes.php");

    // We need to be logged on for this!
        
    if (isloggedin()) {

        // Define context
            define("context","widget");
                
        // Load global variables
            global $CFG, $PAGE, $db, $page_owner, $messages;

        // Load external variables
            $action = trim(optional_param('_widget_action'));
        // Get widget details
            $ident = optional_param('wid',0,PARAM_INT);
            $widget = get_record("widgets", "ident", $ident);
        // Page owner = where the widget resides
            $page_owner = $widget->owner;
            
        // Initialise page
            $body = "";
            $title = __gettext("Edit widget");
            templates_page_setup();
        
        // If action is specified
        
        if (!empty($action) && ($action == "widget:save" || $action == "widget:save:ajax")) {
            
            // Get the username of the widget owner
            // KJ - why?
            $username = user_info("username",$widget->owner);
            
            // Get any data
            $js_override = optional_param('widget_allcontent');
            if ($js_override == "yes"
                && in_array($widget->type, $CFG->widgets->allcontent)) {
                $widget_data = optional_param('widget_data','',null);
            } else {
                $widget_data = optional_param('widget_data','',PARAM_CLEAN);
            }
            
            if (!empty($widget_data)) {
                
                widget_remove_data($widget->ident);
                
                if (is_array($widget_data)) {
                    foreach($widget_data as $key => $value) {
                        widget_set_data($key, $widget->ident, $value);
                    }
                }
            }
            
            // Save access
            $widget->access = optional_param('_widget_access',"user".$_SESSION['userid']);
            update_record('widgets',$widget);
            
            if ($action == "widget:save") {
                
                // Add a message
                $messages[] = __gettext("Widget edited.");
                $_SESSION['messages'] = $messages;
                    
                // Redirect back to the relevant location 
                switch( $widget->location ) {
                    case 'profile':
                    case '':
                        $redirect_url = $CFG->wwwroot . $username . "/profile/";
                        break;
                    default:  
                         // get module from the widget type
                        $module = '';
                        $mod_pos = strpos($widget->type,"::");
                        if ($mod_pos) {
                            $module = substr($widget->type,0,$mod_pos);
                        }               
                        $redirect_url = widget_get_display_url($module).'?'.widget_get_display_query();
                        break;
                }
                
                header("Location: " . $redirect_url);
                exit;
                
            } else {
                
                $result = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';
                $result .= '<answer>';
                $result .= '<result>'.$widget->ident.'</result>';
                $result .= '</answer>';
                header('Content-Type: application/xml');
                print $result;
                
            }
            
        } else {
            
        // Do we have permission to touch this?
        // If so, display edit screen
            if (run("permissions:check","profile")) {
                
                $body = widget_edit($widget);
                
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
    }
    
?>