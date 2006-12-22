<?php

    //    ELGG profile search page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/includes.php");
        
        run("profile:init");
        
        $title = __gettext("Search Profiles");
        templates_page_setup();
        $body = run("content:profile:search");
        $body .= run("profile:search");
        
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