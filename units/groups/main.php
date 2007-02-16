<?php

    /*
    *    Groups plug-in
    */
    
        global $CFG;

    // Functions to perform upon initialisation
        $function['groups:init'][] = $CFG->dirroot . "units/groups/groups_init.php";
        $function['groups:init'][] = $CFG->dirroot . "units/groups/groups_actions.php";
        
    // Add user-owned groups to access levels
        $function['init'][] = $CFG->dirroot . "units/groups/groups_access_levels.php";

    // Function to retrieve groups
        $function['groups:get'][] = $CFG->dirroot . "units/groups/get_groups.php";
        $function['groups:get:external'][] = $CFG->dirroot . "units/groups/get_groups_external.php";
        $function['groups:getmembership'][] = $CFG->dirroot . "units/groups/get_groups_membership.php";
        
    // Group view / edit screen
        // $function['groups:editpage'][] = $CFG->dirroot . "units/groups/groups_display_membership.php";
        $function['groups:editpage'][] = $CFG->dirroot . "units/groups/groups_explanation.php";
        $function['groups:editpage'][] = $CFG->dirroot . "units/groups/groups_create.php";
        $function['groups:editpage'][] = $CFG->dirroot . "units/groups/groups_edit_existing.php";
        
    // Individual group editing function
        $function['groups:edit:display'][] = $CFG->dirroot . "units/groups/groups_edit_display.php";

    // Check access levels
        $function['users:access_level_check'][] = $CFG->dirroot . "units/groups/group_access_level_check.php";
    
    // Obtain SQL "where" string for access levels
        $function['users:access_level_sql_where'][] = $CFG->dirroot . "units/groups/function_access_level_sql_where.php";
        
?>