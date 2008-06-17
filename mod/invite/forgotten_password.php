<?php

    //    ELGG generate-a-new-password page

    // Run includes
        define("context","external");
        require_once(dirname(dirname(__FILE__))."/../includes.php");
        
        run("profile:init");
        run("invite:init");
        
        templates_page_setup();

        $title = __gettext("Generate a New Password");
        
        $body = run("invite:password:request");
        
        templates_page_output($title, $body);

?>