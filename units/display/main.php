<?php

    // Display functions
    
        global $CFG;
    
    // Initialise
        $function['init'][] = $CFG->dirroot . "units/display/function_init.php";
    
    // Top of page
        $function['display:topofpage'][] = $CFG->dirroot . "units/display/function_topofpage.php";
        $function['display:topofpage'][] = $CFG->dirroot . "units/display/function_messages.php";
        
    // Bottom of page
        $function['display:bottomofpage'][] = $CFG->dirroot . "units/display/function_bottomofpage.php";
        
    // Log on pane
        $function['display:log_on_pane'][] = $CFG->dirroot . "units/display/function_log_on_pane.php";
        $function['display:sidebar'][] = $CFG->dirroot . "units/display/function_log_on_pane.php";
        
    // Form elements
        $function['display:input_field'][] = $CFG->dirroot . "units/display/function_input_field_display.php";
        $function['display:output_field'][] = $CFG->dirroot . "units/display/function_output_field_display.php";

    // TEMPLATING ---
    
    // Adds data to the various strings used in templating
        $function['display:addstring'][] = $CFG->dirroot . "units/display/function_display_addstring.php";
        
?>