<?php

    // ELGG system includes

    /***************************************************************************
    *    INSERT SYSTEM UNITS HERE
    *    You should ideally not edit this file.
    ****************************************************************************/
    
    // Plug-in engine (must be loaded first)
        require($CFG->dirroot . "units/engine/main.php");
    // Language / internationalisation
        require($CFG->dirroot . "units/gettext/main.php");
    // Display
        require($CFG->dirroot . "units/display/main.php");
    // Users
        require($CFG->dirroot . "units/users/main.php");
    // Templates
        require($CFG->dirroot . "units/templates/main.php");
    // Edit permissions
        require($CFG->dirroot . "units/permissions/main.php");
        
    // User icons
        include($CFG->dirroot . "units/icons/main.php");
    // Profiles
        include($CFG->dirroot . "units/profile/main.php");
        
    // Weblog
        include($CFG->dirroot . "units/weblogs/main.php");
    
    // File repository
        include($CFG->dirroot . "units/files/main.php");
                
    // Communities
        require($CFG->dirroot . "units/communities/main.php");
        
    // Friends
        include($CFG->dirroot . "units/friends/main.php");
    // Friend groups
        include($CFG->dirroot . "units/groups/main.php");
        
    // Search
        require($CFG->dirroot . "units/search/main.php");
        
    // Invite-a-friend
        require($CFG->dirroot . "units/invite/main.php");
        
    // Admin system
        require($CFG->dirroot . "units/admin/main.php");
        
    // XML parsing
        require($CFG->dirroot . "units/xml/main.php");
        
    // Your Resources
        require($CFG->dirroot . "units/magpie/main.php");
        
?>
