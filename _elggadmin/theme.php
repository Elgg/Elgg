<?php

    // Elgg administration panel
    
    // Load library functions
        require("lib.php");
        
        global $CFG, $messages;
        
    // Initialise the admin panel. If we get past this point, we're logged in.
        elggadmin_init();
        elggadmin_theme_init();
        
    // Draw page header
        elggadmin_header();
        
    // Draw navigation
        elggadmin_navigation("theme");
        
        elggadmin_begin_content();
        
        echo "<table width=\"100%\" border=\"0\"><tr><td valign=\"top\">";
        
        elggadmin_theme_main();
        
        echo "</td><td valign=\"top\" width=\"200\" >";
        
        echo "<h2>" . ("Special keywords") . "</h2>";
        echo "<p>&nbsp;</p>";
        echo "<p>" . ("You can insert these into your pageshell for special functionality:") . "</p>";
        echo "<p><b>{{url}}</b> " . ("The address of your site.") . "</p>";
        echo "<p><b>{{sitename}}</b> " . ("The name of your site.") . "</p>";
        echo "<p><b>{{tagline}}</b> " . ("Your site's tagline.") . "</p>";
        echo "<p><b>{{title}}</b> " . ("The page title.") . "</p>";
        echo "<p><b>{{topmenu}}</b> " . ("The top menu.") . "</p>";
        echo "<p><b>{{messageshell}}</b> " . ("Any system messages.") . "</p>";
        echo "<p><b>{{menu}}</b> " . ("The main menu.") . "</p>";
        echo "<p><b>{{mainbody}}</b> " . ("The main body of the page.") . "</p>";
        echo "<p><b>{{sidebar}}</b> " . ("The page sidebar.") . "</p>";
        
        echo "</td></tr></table>";
        
        elggadmin_end_content();
        
    // Draw page footer
        elggadmin_footer();

?>