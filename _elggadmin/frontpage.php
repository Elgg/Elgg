<?php

    // Elgg administration panel
    
    // Load library functions
        require("lib.php");
        
        global $CFG, $messages;
        
    // Initialise the admin panel. If we get past this point, we're logged in.
        elggadmin_init();
        elggadmin_frontpage_init();
        
    // Draw page header
        elggadmin_header();
        
    // Draw navigation
        elggadmin_navigation("frontpage");
        
        elggadmin_begin_content();
        
        echo "<table width=\"100%\" border=\"0\"><tr><td valign=\"top\">";
        
        elggadmin_frontpage_main();
        
        echo "</td><td valign=\"top\" width=\"200\" >";
        
        echo "<h2>" . ("Special keywords") . "</h2>";
        echo "<p>&nbsp;</p>";
        echo "<p>" . ("You can insert these into your pageshell for special functionality:") . "</p>";
        echo "<p><b>{{url}}</b> " . ("The address of your site.") . "</p>";
        echo "<p><b>{{sitename}}</b> " . ("The name of your site.") . "</p>";
        echo "<p><b>{{tagline}}</b> " . ("Your site's tagline.") . "</p>";
        echo "<p><b>{{username}}</b> " . ("The current user's username.") . "</p>";
        echo "<p><b>{{userfullname}}</b> " . ("The current user's full name.") . "</p>";
        echo "<p><b>{{populartags}}</b> " . ("A list of the most popular tags.") . "</p>";
        echo "<p><b>{{randomusers}}</b> " . ("A list of random users who have filled in their profiles, if some exist.") . "</p>";
        echo "<p><b>{{people:interests:foo:5}}</b> " . ("Lists five people interested in 'foo' in a horizontal table.") . "</p>";
        echo "<p><b>{{toptags:town}}</b> " . ("Lists the top tags of type 'town' (or select weblog, file or the profile field of your choice).") . "</p>";
        
        echo "</td></tr></table>";
        
        elggadmin_end_content();
        
    // Draw page footer
        elggadmin_footer();

?>