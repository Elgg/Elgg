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
        global $CFG, $PAGE, $page_owner, $profile_id;
    
    // Get the current user
        $username = trim(optional_param('user',''));
        $user_id = user_info_username("ident",$username);
        
        if (empty($username) || $user_id == false) {
            if (isloggedin()) {
                $user_id = $_SESSION['userid'];
                $username = $_SESSION['username'];
            } else {
                header("Location: " . $CFG->wwwroot);
                exit;
            }
        }
        
        $page_owner = $user_id;
        $profile_id = $page_owner;
        
    // Initialise page body and title
        $body = "";
        $title = run("profile:display:name", $user_id) . " :: " . __gettext("Dashboard");
        
        templates_page_setup();
        
    // Get widgets
        $widgets = adash_widgets_get($user_id);
        
    // If we have some widgets, iterate through them
    // (otherwise, suggest the user adds some if this is their dashboard,
    // or just say sorry if it isn't)
        if (is_array($widgets) && !empty($widgets)) {
            
            foreach($widgets as $widget) {
                $body .= "<div class=\"dashboard_widget\">\n";
                $body .= adash_widget_display($widget);
                $body .= "\n</div>\n";
            }
            
        } else if ($user_id == $_SESSION['userid']) {
            $body .= "<p>" . __gettext("This dashboard is currently empty. Why not add some widgets by clicking 'add new item' above?") . "</p>";
        } else {
            $body .= "<p>" . __gettext("This dashboard is currently empty.") . "</p>";
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