<?php

    //    ELGG get new password page

    // Run includes
        define("context","external");
        require_once(dirname(dirname(__FILE__))."/includes.php");
        
        run("invite:init");
        templates_page_setup();                
        $title = sprintf(__gettext("Get new %s password"), sitename);
        
        $body = run("invite:password:new");
        
        $body .= templates_draw(array(
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