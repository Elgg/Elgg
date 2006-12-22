<?php

    /*
    
        Elgg Dashboard
        http://elgg.org/
    
    */

    // Standard page setup function
    
        function adash_pagesetup() {
    
            global $PAGE, $CFG, $page_owner;
            
            if (isloggedin() && user_info("user_type",$_SESSION['userid']) != "external") {
                if (defined("context") && context == "dashboard" && $page_owner == $_SESSION['userid']) {
                    $PAGE->menu[] = array( 'name' => 'dashboard',
                                           'html' => "<li><a href=\"{$CFG->wwwroot}{$_SESSION['username']}/dashboard/\" class=\"selected\" >" .__gettext("Your Dashboard").'</a></li>');
                } else {
                    $PAGE->menu[] = array( 'name' => 'dashboard',
                                           'html' => "<li><a href=\"{$CFG->wwwroot}{$_SESSION['username']}/dashboard/\" >" .__gettext("Your Dashboard").'</a></li>');
                }
                if (defined("context") && context == "dashboard" && run("permissions:check", "profile") && isloggedin() && $page_owner == $_SESSION['userid']) {
                    $PAGE->menu_sub[] = array( 'name' => 'dashboard:edit',
                                               'html' => a_href( $CFG->wwwroot."mod/adash/add.php?owner=" . $_SESSION['userid'], 
                                                                  __gettext("Add new item")));
                }
        
                $dash_username = user_info("username",$page_owner);
            }
            
        }
        
    // Initialisation function
        
        function adash_init() {
            
            global $CFG, $function, $db, $METATABLES;
            
            $function['init'][] = $CFG->dirroot . "mod/adash/init.php";
            
            $CFG->widgets->display['text'] = "adash_text_widget_display";
            $CFG->widgets->edit['text'] = "adash_text_widget_edit";
            $CFG->widgets->list[] = array(
                                                'name' => __gettext("Text box"),
                                                'description' => __gettext("Displays the text of your choice."),
                                                'id' => "text"
                                        );
                                        
            if (!in_array($CFG->prefix . "dashboard_data", $METATABLES) || !in_array($CFG->prefix . "dashboard_widgets", $METATABLES)) {
                if (file_exists($CFG->dirroot . "mod/adash/$CFG->dbtype.sql")) {
                    modify_database($CFG->dirroot . "mod/adash/$CFG->dbtype.sql");
                } else {
                    error("Error: Your database ($CFG->dbtype) is not yet fully supported by the Elgg dashboard.  See the mod/adash directory.");
                }
                print_continue("index.php");
                exit;
            }
            
        }

    // Get widgets for a particular user
    
        function adash_widgets_get($user_id) {
            
            global $CFG;
            
            // Get access list for this user
                $where = run("users:access_level_sql_where",$user_id);
                
            // Get a list of widgets
                $widgets = get_records_sql("select * from ".$CFG->prefix."dashboard_widgets where ($where) and owner = $user_id order by display_order asc");
                
            // Return them
                return $widgets;
            
        }
        
    // Gets data with a particular name for a particular widget ID
    
        function adash_get_data($name, $ident) {
            
            global $db, $CFG;
            
            $name = $db->qstr($name);
            $ident = $db->qstr($ident);
            
            $value = get_record_sql("select * from ".$CFG->prefix."dashboard_data where name = $name and widget = $ident");
            if (empty($value) || $value->value == false) {
                $value->value = "";
            }
            
            return $value->value;
            
        }
        
    // Sets data with a particular name for a particular widget ID
    
        function adash_set_data($name, $ident, $value) {
            $data = new stdClass;
            $data->name = $name;
            $data->widget = $ident;
            $data->value = $value;
            if ($existing = adash_get_data($name,$ident)) {
                $data->ident = $existing->ident;
                return update_record('dashboard_data',$data);
            } else {
                return insert_record('dashboard_data',$data);
            }
        }
        
    // Removes data for a particular widget ID
    
        function adash_remove_data($ident) {
            delete_records('dashboard_data','widget',$ident);
        }
        
    // Removes a widget
        
        function adash_widget_destroy($ident) {
            
            delete_records('dashboard_widgets','ident',$ident);
            adash_remove_data($ident);
            
        }
        
    // Returns HTML for a particular widget
    // (Assumes a widget object taken from the database)
    
        function adash_widget_display($widget) {
            
            global $CFG;
            global $PAGE;
            
            // If the dashboard handler for displaying this particular widget type exists,
            // run it - otherwise display nothing
            
                if (!empty($CFG->widgets->display[$widget->widget_type])) {
                    $body = $CFG->widgets->display[$widget->widget_type]($widget);
                } else {
                    $body = "";
                }
                $body = "<div class=\"dashboard_widget_content\">$body</div>";
                
            // If we have permission, display edit buttons
                
                if (isloggedin() && run("permissions:check","profile")) {
                    
                    $body .= "<p class=\"dashboard_widget_menu\">";
                    $body .= "<a href=\"" . $CFG->wwwroot . "mod/adash/edit.php?widget=" . $widget->ident . "\">" . __gettext("Edit widget") . "</a> | ";
                    $body .= "<a href=\"" . $CFG->wwwroot . "mod/adash/delete.php?widget=" . $widget->ident . "\">" . __gettext("Delete widget") . "</a> | ";
                    $body .= "<a href=\"" . $CFG->wwwroot . "mod/adash/move.php?move=up&widget=" . $widget->ident . "\">" . __gettext("Move up") . "</a> | ";
                    $body .= "<a href=\"" . $CFG->wwwroot . "mod/adash/move.php?move=down&widget=" . $widget->ident . "\">" . __gettext("Move down") . "</a> ";
                    $body .= "</p>";
                    
                }
                
            // Return HTML
            
                return $body;
            
        }
        
    // Reorders widgets for a particular user
    
        function adash_widgets_reorder($owner) {
            
            $widgets = adash_widgets_get($owner);
            if (is_array($widgets) && !empty($widgets)) {
                $order = array();
                $i = 1;
                foreach($widgets as $widget) {
                    $order[$widget->ident] = $i * 10;
                    $i++;
                }
                foreach($order as $ident => $display_order) {
                    $widget = new StdClass;
                    $widget->display_order = $display_order;
                    $widget->ident = $ident;
                    update_record('dashboard_widgets',$widget);
                }
            }
            
        }
        
    // Move a widget up
        function adash_widget_moveup($widget) {
            $widget->display_order = $widget->display_order - 11;
            update_record('dashboard_widgets',$widget);
            adash_widgets_reorder($widget->owner);
        }

    // Move a widget down
        function adash_widget_movedown($widget) {
            $widget->display_order = $widget->display_order + 11;
            update_record('dashboard_widgets',$widget);
            adash_widgets_reorder($widget->owner);
        }
        
    // Returns HTML for the edit screen on a particular widget
    // (Assumes a widget object taken from the database)
    
        function adash_widget_edit($widget) {
            
            global $CFG;
            global $PAGE;
            
            // If the dashboard handler for displaying this particular widget type exists,
            // run it - otherwise display nothing
            
                if (isloggedin() && run("permissions:check","profile")) {
                
                    if (!empty($CFG->widgets->edit[$widget->widget_type])) {
                        $body = $CFG->widgets->edit[$widget->widget_type]($widget);
                    } else {
                        $body = "";
                    }
                    
                }
                
            // Stick it in an appropriate form for saving
                $body = "<form action=\"" . $CFG->wwwroot . "mod/adash/edit.php\" method=\"post\">\n" . $body;
                
                $body .= "<p>" . __gettext("Access for this widget:") . "</p><p>" . run("display:access_level_select",array("dashboard_access",$widget->access)) . "</p>";
                
                $body .= "<p><input type=\"hidden\" name=\"action\" value=\"dashboard:widget:save\" />\n";
                $body .= "<input type=\"hidden\" name=\"widget\" value=\"" . $widget->ident . "\" />\n";
                $body .= "<input type=\"submit\" value=\"" . __gettext("Save widget") . "\" /></p>\n</form>\n";
                
            // Return HTML
            
                return $body;
            
        }
        
    // Functions to display and edit plain text widgets
    
        function adash_text_widget_display($widget) {
            
            global $CFG;
            
            $adash_text_title = adash_get_data("adash_text_title",$widget->ident);
            $adash_text_body = adash_get_data("adash_text_body",$widget->ident);
            
            if (empty($adash_text_body)) {
                $adash_text_body = __gettext("This text box is undefined. If you are the widget owner, click 'edit widget' to add your own content.");
            }
            
            $body = "<h2>" . $adash_text_title . "</h2>" . "<p>" . nl2br($adash_text_body) . "</p>";
            
            return $body;
            
        }
        
        function adash_text_widget_edit($widget) {
            
            global $CFG, $page_owner;
            
            $adash_text_title = adash_get_data("adash_text_title",$widget->ident);
            $adash_text_body = adash_get_data("adash_text_body",$widget->ident);

            $body = "<h2>" . __gettext("Text box") . "</h2>";
            $body .= "<p>" . __gettext("This widget displays the text content of your choice. All you need to do is enter the title and body below:") . "</p>";

            $body .= "<p>" . display_input_field(array("dashboard_data[adash_text_title]",$adash_text_title,"text")) . "</p>";
            $body .= "<p>" . display_input_field(array("dashboard_data[adash_text_body]",$adash_text_body,"longtext")) . "</p>";
            
            return $body;
            
        }
        
?>