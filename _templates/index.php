<?php

    //    ELGG template create / select page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/includes.php");
        
        protect(1);
        
        run("profile:init");
        run("templates:init");
        
        define("context", "account");
        templates_page_setup();        
        $title = run("profile:display:name") . " :: ". __gettext("Select / Create Themes");
        
        $body = run("content:templates:view");
        $body .= run("templates:view");
        $body .= run("content:templates:add");
        $body .= run("templates:add");
        
        $body = templates_draw(array(
                        'context' => 'contentholder',
                        'title' => $title,
                        'body' => $body
                    )
                    );
        
        echo templates_page_draw( array(
                        $title, $body
                    )
                    );

?>