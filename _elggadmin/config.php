<?php
        
    /*
    
        Elgg admin panel
        Configuration options
        
        Assumes main Elgg config.php exists.
    
    */

    global $ADMINCFG;
    
    // Location of main Elgg installation root (relative to this file or absolute)
            $ADMINCFG->admin->elggdir = "../";
            
    // Username for admin panel
            $ADMINCFG->admin->username = "admin";
            
    // Password for admin panel
    // (Admin panel will not work if this is left blank!)
            $ADMINCFG->admin->password = "";
            
    // Include the names of any configuration parameters here that you
    // don't want to be editable
            $ADMINCFG->admin->noedit = array(
                                            );
    
    // Filename of main Elgg config file (99.99% of the time this will be config.php)
            $ADMINCFG->admin->configfile = "config.php";

?>