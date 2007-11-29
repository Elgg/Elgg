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

        $type = gettext("Blog");
        if(is_array($CFG->weblog_extensions[$extensionContext]) &&array_key_exists('name',$CFG->weblog_extensions[$extensionContext])){
          $type = $CFG->weblog_extensions[$extensionContext]['name'];
        }

        $title = run("profile:display:name") . " :: ".$type ." :: ". __gettext("Archives");

        $body = run("content:weblogs:archives:view");
        $body .= run("weblogs:archives:view");

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