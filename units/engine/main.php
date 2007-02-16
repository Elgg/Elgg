<?php

    /*
    *    Plug-in engine
    */

    // Library functions
        require_once(dirname(__FILE__)."/library.php");
    
        global $CFG;
        
    // Initialise variables etc on startup
        $function['init'][] = $CFG->dirroot . "units/engine/function_init.php";
        
?>