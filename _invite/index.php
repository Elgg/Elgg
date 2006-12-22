<?php

    //    ELGG invite-a-friend page

    // Run includes
        define("context","external");
        require_once(dirname(dirname(__FILE__))."/includes.php");
        
        run("profile:init");
        run("invite:init");
        
        define("context", "network");
        templates_page_setup();

    // You must be logged on to view this!
        if (logged_on && $CFG->publicinvite == true) {
        
        $title = __gettext("Invite a Friend");
        
        $body = run("content:invite:invite");
        $body .= run("invite:invite");
        
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
                } else {
                    header("Location: " . $CFG->wwwroot);
                }

?>