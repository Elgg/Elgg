<?php

    //    ELGG manage friends and groups page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/../includes.php");

    // Initialise functions for user details, icon management and profile management
        run("userdetails:init");
        run("profile:init");
        run("friends:init");

        define("context", "network");

    // Whose friends are we looking at?
        global $page_owner;

    // You must be logged on to view this!
    //    protect(1);
        templates_page_setup();
        $title = run("profile:display:name") . " :: ". __gettext("Friends I have linked to");
        $body = run('friends:editpage');

        templates_page_output($title, $body);

?>