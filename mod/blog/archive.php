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

        $type = blog_get_extension($extensionContext, 'name');

        $title = run("profile:display:name") . " :: ".$type ." :: ". __gettext("Archives");

        $body = run("content:weblogs:archives:view");
        $body .= run("weblogs:archives:view");

        templates_page_output($title, $body);

?>