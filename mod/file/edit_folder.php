<?php

    //    ELGG manage files page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/../includes.php");
        
    // Initialise functions for user details, icon management and profile management
        run("userdetails:init");
        run("profile:init");
        run("files:init");
        
        define("context", "files");
        templates_page_setup();

    // Whose files are we looking at?

        global $page_owner;
        $title = user_info("name", page_owner()) . " :: " . gettext("Edit Folder");

        $body = run("content:folders:edit");
        $body .= run("files:folder:edit");
        
        templates_page_output($title, $body);
                
?>
