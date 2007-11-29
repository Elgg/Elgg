<?php

    //    ELGG weblog view page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/../includes.php");

        run("profile:init");
        run("weblogs:init");
        run("friends:init");

        $extensionContext = trim(optional_param('extension','weblog'));

        define("context", $extensionContext);
        templates_page_setup();

        $type = __gettext("Friends blog");
        if(is_array($CFG->weblog_extensions[$extensionContext]) &&array_key_exists('name',$CFG->weblog_extensions[$extensionContext])){
          $type = $CFG->weblog_extensions[$extensionContext]['name']." :: ".  __gettext("Friends");
        }

        $title = run("profile:display:name") . " :: ". $type;

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