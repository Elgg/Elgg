<?php

    //    ELGG manage community membership requests page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/../includes.php");

    // Initialise functions for user details, icon management and profile management
        run("userdetails:init");
        run("profile:init");
        run("communities:init");

        $context = (defined('COMMUNITY_CONTEXT'))?COMMUNITY_CONTEXT:"network";

        define("context", $context);
        templates_page_setup();

    // Whose friends are we looking at?
        global $page_owner;

    // You must be logged on to view this!
    //    protect(1);

        $title = run("profile:display:name") . " :: ". __gettext("Membership requests");

        echo templates_page_draw(array(
                    $title, run("communities:requests:view")
                )
                );

?>