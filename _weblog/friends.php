<?php

    //    ELGG weblog view page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/includes.php");
        
        run("profile:init");
        run("weblogs:init");
        run("friends:init");
        
        define("context", "weblog");
        templates_page_setup();
        
        $title = run("profile:display:name") . " :: ". __gettext("Friends blog");

        $body = run("content:weblogs:view");
        $body .= run("weblogs:friends:view");
        
        $body = templates_draw(array(
                        'context' => 'contentholder',
                        'title' => $title,
                        'body' => "<div id=\"view_friends_blogs\">" . $body . "</div>"
                    )
                    );
                    
        echo templates_page_draw( array(
                        $title, $body
                    )
                    );

?>