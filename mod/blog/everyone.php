<?php

    //    ELGG weblog view page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/../includes.php");

        run("profile:init");
        run("friends:init");
        run("weblogs:init");

        $extensionContext = trim(optional_param('extension','weblog'));

        define("context", $extensionContext);
        templates_page_setup();

        $type = gettext("Blog");
        if(is_array($CFG->weblog_extensions[$extensionContext]) &&array_key_exists('name',$CFG->weblog_extensions[$extensionContext])){
          $type = $CFG->weblog_extensions[$extensionContext]['name'];
        }
        $title = $type." :: ".__gettext("All");

        $body = run("content:weblogs:view");

        $body .= "<p>" . __gettext("You can filter this page to certain types of posts:") . "</p>";
        $body .= '<ul>';
        //url . $weblog_name . "/{$extensionContext}/archive
        $body .= '<li><a href="'.url.$extensionContext.'/everyone/people">' . __gettext('Personal blog posts') . '</a></li>';
        $body .= '<li><a href="'.url.$extensionContext.'/everyone/communities">' . __gettext('Community blog posts') . '</a></li>';
        $body .= '<li><a href="'.url.$extensionContext.'/everyone/commented">' . __gettext('Posts with comments') . '</a></li>';
        $body .= '<li><a href="'.url.$extensionContext.'/everyone/uncommented">' . __gettext('Posts with no comments') . '</a></li>';
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