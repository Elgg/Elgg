<?php

    //    ELGG display popular tags page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/includes.php");
        
        run("search:init");
        
        define("context","search");
        
        $title = __gettext("Some Tags");
templates_page_setup();
        $body = run("content:tags");
        $body .= run("search:tags:display");
        
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