<?php

    //    ELGG weblog view page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/includes.php");
        
        run("profile:init");
        run("friends:init");
        run("weblogs:init");
        
        define("context", "weblog");
        templates_page_setup();
        
        $title = __gettext("All blogs");        

        $body = run("content:weblogs:view");
        
        $body .= "<p>" . __gettext("You can filter this page to certain types of posts:") . "</p>";
        $body .= '<ul>';
        $body .= '<li><a href="everyone.php?filter=people">' . __gettext('Personal blog posts') . '</a></li>';
        $body .= '<li><a href="everyone.php?filter=communities">' . __gettext('Community blog posts') . '</a></li>';
        $body .= '<li><a href="everyone.php?filter=commented">' . __gettext('Posts with comments') . '</a></li>';
        $body .= '<li><a href="everyone.php?filter=uncommented">' . __gettext('Posts with no comments') . '</a></li>';
        $body .= '</ul>';

        $body .= run("weblogs:everyone:view");
        
        $body = templates_draw(array(
                        'context' => 'contentholder',
                        'title' => $title,
                        'body' => "<div id=\"view_all_blog_posts\">" . $body . "</div>"
                    )
                    );
                    
        echo templates_page_draw( array(
                        $title, $body
                    )
                    );

?>