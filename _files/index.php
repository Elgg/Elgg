<?php

    //    ELGG manage files page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/includes.php");
        
    // Initialise functions for user details, icon management and profile management
        run("userdetails:init");
        run("profile:init");
        run("files:init");
        
        define("context", "files");
        templates_page_setup();

    // Whose files are we looking at?

        global $page_owner;
        $title = run("profile:display:name") . " :: ". __gettext("Files") ."";

        $body = run("content:files:view");
        $body .= run("files:view");
        
        echo templates_page_draw( array(
                    $title,
                    templates_draw(array(
                            'context' => 'contentholder',
                            'title' => $title,
                            'body' => $body
                        )
                        )
                )
                );
                
?>