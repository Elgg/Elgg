<?php

    //    ELGG join-with-no-invite page

    // Run includes
        define("context","external");
        require_once(dirname(dirname(__FILE__))."/includes.php");
        
        run("invite:init");
        templates_page_setup();        
        $title = sprintf(__gettext("Join %s"), sitename);

        $body = run("content:invite:join");
        $body .= run("join:no_invite");
        
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