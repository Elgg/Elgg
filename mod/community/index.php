<?php

    //    ELGG manage community memberships page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/../includes.php");

    // Initialise functions for user details, icon management and profile management
        run("userdetails:init");
        run("profile:init");
        run("friends:init");
        run("communities:init");

        $context = (defined('COMMUNITY_CONTEXT'))?COMMUNITY_CONTEXT:"network";

        define("context", $context);
        templates_page_setup();

        $title = run("profile:display:name") . " :: " . __gettext("Communities");
        $body = run('communities:editpage');

        templates_page_output($title, $body);

?>