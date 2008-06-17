<?php

    //    ELGG invite-a-friend page

    // Run includes
        define("context","external");
        require_once(dirname(dirname(__FILE__))."/../includes.php");
        
        run("invite:init");
        templates_page_setup();
        
        $title = sprintf(__gettext("Join %s"),sitename);
        
        $body = run("content:invite:join");
        $body .= run("invite:join");
        
        templates_page_output($title, $body);

?>