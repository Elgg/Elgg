<?php

    //    ELGG weblog edit / add post page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/../includes.php");

        run("weblogs:init");
        run("profile:init");
        run("friends:init");

        require_login();

        global $page_owner;
        if (!logged_on) {
            $page_owner = -1;
        } else {
            if (!run("permissions:check", "weblog")) {
                $page_owner = $_SESSION['userid'];
            }
        }

        $extensionContext = trim(optional_param('extension','weblog'));

        define("context", $extensionContext);
        templates_page_setup();

        $type = blog_get_extension($extensionContext, 'name');

        $title = run("profile:display:name") . " :: " . $type;

        $body = run("content:weblogs:edit");
        $body .= run("weblogs:edit");

        templates_page_output($title, $body);

?>
