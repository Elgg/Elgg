<?php

function profile_pagesetup() {
    // register links -- 
    global $profile_id;
    global $PAGE;
    global $CFG;

    // don't clobber $page_owner, use a 
    // local $pgowner instead for clarity
    $pgowner = $profile_id;

    if (isloggedin() && user_info("user_type",$_SESSION['userid']) != "external") {
        if (defined("context") && context == "profile" && $pgowner == $_SESSION['userid']) {
            $PAGE->menu[] = array( 'name' => 'profile', 
                                   'html' => '<li><a href="'.$CFG->wwwroot.$_SESSION['username'].'/profile/" class="selected">'.__gettext("Your Profile").'</a></li>');
        } else {
            $PAGE->menu[] = array( 'name' => 'profile',
                                   'html' => '<li><a href="'.$CFG->wwwroot.$_SESSION['username'].'/profile/">'.__gettext("Your Profile").'</a></li>');
        }

        if (profile_permissions_check("profile") && defined("context") && context == "profile") {

            if (user_type($pgowner) == "person") {
                $PAGE->menu_sub[] = array( 'name' => 'profile:edit', 
                                           'html' => '<a href="'.$CFG->wwwroot.'profile/edit.php?profile_id='.$pgowner.'">'
                                           . __gettext("Edit this profile") . '</a>');

                $PAGE->menu_sub[] = array( 'name' => 'profile:picedit', 
                                           'html' => '<a href="'.$CFG->wwwroot.'_icons/?context=profile&amp;profile_id='.$pgowner.'">'
                                           . __gettext("Change site picture") . '</a>');
            }
        }
    }
    
    $PAGE->search_menu[] = array( 'name' => __gettext("People"),
                                  'user_type' => 'person');

}

function profile_init() {
    
    global $CFG, $messages, $function;
    
    // Check to see if the profile config file doesn't exist
    if (!isset($CFG->profilelocation)) {
        $CFG->profilelocation = $CFG->dirroot . "mod/profile/";
    } else {
    
        if (!file_exists($CFG->profilelocation . "profile.config.php")) {
            if (!copy($CFG->dirroot . "mod/profile/profile.config.php",$CFG->profilelocation . "profile.config.php")) {
                $CFG->profilelocation = $CFG->dirroot . "mod/profile/";
            }
        }
    
    }
    
    $function['search:init'][] = $CFG->profilelocation . "profile.config.php";
    
    // Add items to the dashboard if it exists
    $CFG->widgets->display['profile'] = "profile_widget_display";
    $CFG->widgets->edit['profile'] = "profile_widget_edit";
    $CFG->widgets->list[] = array(
                                        'name' => __gettext("Profile widget"),
                                        'description' => __gettext("Displays the contents of a profile field."),
                                        'id' => "profile"
                                );
    
}

function profile_permissions_check ($object) {
    global $page_owner;
    
    if ($object === "profile" && ($page_owner == $_SESSION['userid'] || user_flag_get("admin", $_SESSION['userid']))) {
        return true;
    }
    return false;
}


function profile_widget_display($widget) {
    
    global $CFG, $profile_id, $data, $page_owner, $db;
    static $profile;
    
    $profile_id = $page_owner;
    
    require_once($CFG->dirroot . 'profile/profile.class.php');
    
    $profile_field = adash_get_data("profile_widget_field",$widget->ident);
    $profile_id = $widget->owner;
    
    $title = __gettext("Profile widget");
    $body = "<p>" . __gettext("This profile box is undefined.") . "</p>";
    
    if (!isset($profile)) {
        $profile = new ElggProfile($profile_id);
    }
    
    $field = null;
    
    $user_type = user_info("user_type",$widget->owner);
    
    foreach($data['profile:details'] as $field_row) {
        if ($field_row->internal_name == $profile_field && (!isset($field_row->user_type) || $field_row->user_type == $user_type)) {
            $field = $field_row;
        }
    }
    
    $title = $field->name;
    $value = get_record_sql("select * from ".$CFG->prefix."profile_data where owner = ".$widget->owner." and name = " . $db->qstr($field->internal_name));
    $body = display_output_field(array($value->value,$field->field_type,$field->internal_name,$field->name,$value->ident));
    
    return "<h2>$title</h2>$body";
    
}

function profile_widget_edit($widget) {
    
    global $CFG, $profile_id, $data, $page_owner;
    static $profile;
    
    $profile_id = $page_owner;
    
    require_once($CFG->dirroot . 'profile/profile.class.php');
    
    $profile_field = adash_get_data("profile_widget_field",$widget->ident);
    
    if (!isset($profile)) {
        $profile = new ElggProfile($profile_id);
    }

    $body = "<h2>" . __gettext("Profile widget") . "</h2>";
    $body .= "<p>" . __gettext("Select a profile field below; the widget will then display the profile content from this field.") . "</p>";
    
    $body .= "<select name=\"dashboard_data[profile_widget_field]\">";

    $user_type = user_info("user_type",$widget->owner);
        
    foreach($data['profile:details'] as $field_row) {
        
        if (!isset($field_row->user_type) || $field_row->user_type == $user_type) {
            if ($field_row->internal_name == $profile_field ) {
                $selected = "selected=\"selected\"";
            } else {
                $selected = "";
            }
            
            $body .= "<option value=\"" . $field_row->internal_name . "\">" . $field_row->name . "</option>\n";
        }    
    }
    
    $body .= "</select>";
    
    return $body;
    
}
    function profile_page_owner() {
        if ($profile_name = optional_param('profile_name')) {
            if ($profile_id = user_info_username('ident', $profile_name)) {
                return $profile_id;
            }
        }
        if ($profile_id = optional_param("profile_id",0,PARAM_INT)) {
            return $profile_id;
        }
        
    }

?>