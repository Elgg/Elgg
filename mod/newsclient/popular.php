<?php

    require_once(dirname(dirname(__FILE__))."/../includes.php");
    
    run("profile:init");
    run("weblogs:init");
    run("rss:init");
    
    define('context','resources');
    global $page_owner;
    templates_page_setup();    
    $title = __gettext("Popular feeds");
    
    $body = run("rss:subscriptions:popular");

    templates_page_output($title, $body);

?>