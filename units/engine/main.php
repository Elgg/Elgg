<?php

    /*
    *    Plug-in engine
    */

    // Library functions
        require_once(dirname(__FILE__)."/library.php");
    
    // Initialise variables etc on startup
        $function['init'][] = path . "units/engine/function_init.php";
        
?>