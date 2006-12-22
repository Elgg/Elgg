<?php

    global $CFG;
    /*
    *    Users plug-in
    */
    
    // Actions to perform on initialisation
        $function['init'][] = path . "units/users/function_session_start.php";
        $function['init'][] = path . "units/users/function_session_actions.php";
        $function['init'][] = path . "units/users/function_default_access_levels.php";
        $function['init'][] = path . "units/users/function_define_ownership.php";

    // User details initialisation
        $function['userdetails:init'][] = path . "units/users/userdetails_actions.php";
                
    // Actions to perform when we log on
        $function['users:log_on'][] = path . "units/users/function_log_on.php";
        
    // Actions to perform when we log off
        $function['users:log_off'][] = path . "units/users/function_log_off.php";

    // Functions to turn a username into a user ID and vice versa
        $function['users:name_to_id'][] = path . "units/users/function_name_to_id.php";// DEPRECATED - use user_info_username("ident", $username)
        $function['users:id_to_name'][] = path . "units/users/function_id_to_name.php";// DEPRECATED - use user_info("username", $user_id)
        
    // Userinfo box
        $function['users:infobox'][] = path . "units/users/user_info.php";
        
    // User count underneath the logon pane
        // $function['display:log_on_pane'][] = path . "units/users/current_user_info.php";
        $function['display:log_on_pane'][] = path . "units/users/function_number_of_users.php";    
        // $function['display:sidebar'][] = path . "units/users/current_user_info.php";
        $function['display:sidebar'][] = path . "units/users/function_number_of_users.php";    
        
    // Access level select
        $function['display:access_level_select'][] = path . "units/users/function_access_level_select.php";
        
    // Check access levels
        $function['users:access_level_check'][] = path . "units/users/function_access_level_check.php";
        
    // Obtain SQL "where" string for access levels
        $function['users:access_level_sql_where'][] = path . "units/users/function_access_level_sql_where.php";
        
    // Display a user's name, given a user ID
        $function['users:display:name'][] = path . "units/users/function_display_name.php";// DEPRECATED - use run("profile:display:name", $ident) or user_info("name", $ident)
        
    // User details edit screen
        $function['userdetails:edit'][] = path . "units/users/userdetails_edit.php";
        
    // Get user type
        $function['users:type:get'][] = path . "units/users/get_type.php";// DEPRECATED - use user_type($user_id) or user_info("user_type", $ident)
        
    // Permissions checker
        $function['permissions:check'][] = path . "units/users/permissions_check.php";
        
    // Flag functions:
    // Check the value of a flag
        $function['users:flags:get'][] = path . "units/users/flag_get.php";// DEPRECATED - use user_flag_get($flag_name, $user_id)
    // Set the value of a flag
        $function['users:flags:set'][] = path . "units/users/flag_set.php";// DEPRECATED - use user_flag_set($flag_name, $value, $user_id)
    // Remove a flag
        $function['users:flags:unset'][] = path . "units/users/flag_unset.php";// DEPRECATED - use user_flag_unset($flag_name, $user_id)
        
?>
