<?php

    //    ELGG template create / select page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/../includes.php");
        
        require_login();

        run("profile:init");
        templates_actions();
        
        define("context", "account");
        templates_page_setup();        
        $title = run("profile:display:name") . " :: ". __gettext("Select / Create Themes");
        
        $body = templates_view();
        $body .= templates_add();
        
        templates_page_output($title, $body);

?>