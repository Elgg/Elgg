<?php

    //    ELGG add community a community
    //error_log("en _communities/addgroup");
    // Run includes
        require_once(dirname(dirname(__FILE__))."/../includes.php");

    // Initialise functions for user details, icon management and profile management
        run("userdetails:init");
        run("profile:init");
        run("friends:init");
       // run("communities:init");

        require_login();

        define("context", "network");
        templates_page_setup();

        $title = user_info("name", page_owner())  . " :: ". __gettext("Invite a Community");
        $body = run('communities:communities', array($page_owner));

        templates_page_output($title, $body);

?>