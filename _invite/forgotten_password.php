<?php

    //    ELGG generate-a-new-password page

    // Run includes
        define("context","external");
        require_once(dirname(dirname(__FILE__))."/includes.php");
        
        run("profile:init");
        run("invite:init");
        
        templates_page_setup();

        $title = __gettext("Generate a New Password");
        
        $body = run("invite:password:request");
        
        $body = templates_draw(array(
                        'context' => 'contentholder',
                        'title' => $title,
                        'body' => $body
                    )
                    );
        
        echo templates_page_draw( array(
                                        $title, $body, '&nbsp;'
                    )
                    );

?>