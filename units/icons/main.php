<?php

    /*
    *    Icons plug-in
    */

        global $CFG;
    
    // Actions
        $function["icons:init"][] = $CFG->dirroot . "units/icons/function_actions.php";
    
    // Icon management
        $function["icons:edit"][] = $CFG->dirroot . "units/icons/function_edit_icons.php";    
        $function["icons:add"][] = $CFG->dirroot . "units/icons/function_add_icons.php";
        
    // Icon retrieval
        $function["icons:get"][] = $CFG->dirroot . "units/icons/function_get_icon.php";
    
    // Permissions check
        $function["permissions:check"][] = $CFG->dirroot . "units/icons/permissions_check.php";
        
?>