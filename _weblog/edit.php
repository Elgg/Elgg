<?php

    //    ELGG weblog edit / add post page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/includes.php");
        
        run("weblogs:init");
        run("profile:init");
        run("friends:init");
        
        global $page_owner;
        if (!logged_on) {
            $page_owner = -1;
        } else {
            if (!run("permissions:check", "weblog")) {
                $page_owner = $_SESSION['userid'];
            }
        }
        
        define("context", "weblog");
        templates_page_setup();
        
        $title = run("profile:display:name") . " :: " . __gettext("Blog");
        
        $body = run("content:weblogs:edit");
        $body .= run("weblogs:edit");

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