<?php

    // Elgg administration panel
    
    // Load library functions
        require("lib.php");
        
        global $CFG, $messages;
        
    // Initialise the admin panel. If we get past this point, we're logged in.
        elggadmin_init();
        
    // Draw page header
        elggadmin_header();
        
    // Draw navigation
        elggadmin_navigation("plugins");
        
        elggadmin_begin_content();
        
        echo "<p>" . ("Be patient! This functionality is coming soon.") . "</p>";
        
        elggadmin_end_content();
        
    // Draw page footer
        elggadmin_footer();

?>