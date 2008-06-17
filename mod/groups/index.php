<?php

    //    ELGG manage groups page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/../includes.php");
        
    // Initialise functions for user details, icon management and profile management
        run("userdetails:init");
        run("profile:init");
        run("friends:init");
        run("groups:init");
        
        require_login();

        define("context", "network");
        templates_page_setup();
        
        $title = run("profile:display:name") . " :: ". __gettext("Access Controls");
        $body = run('groups:editpage');

        templates_page_output($title, $body);

?>