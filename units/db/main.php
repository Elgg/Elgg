<?php

    /*
    *    MySQL database plug-in
    */

    // Configuration
        require_once(dirname(__FILE__)."/conf.php");
        
    // Library functions
        require_once(dirname(__FILE__)."/library.php");
        
    // On initialisation, run the connect script
        $function['init'][] = path . "units/db/function_connect.php";
    
?>