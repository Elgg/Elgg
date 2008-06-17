<?php

    require_once(dirname(dirname(__FILE__))."/../includes.php");
    
//    global $page_owner;
    
    run("weblogs:init");
    run("profile:init");
    
    $username = trim(optional_param('profile_name',''));
    $user_id = user_info_username("ident", $username);
    if (!$user_id) {
        $user_id = $page_owner;
    } else {
        $page_owner = $user_id;
        $profile_id = $user_id;
    }
    
    run("rss:init"); // down here cos it sends $page_owner to rss function_actions.php
    
    define('context','resources');
    templates_page_setup();
    
    $title = run("profile:display:name", $page_owner) ." :: " . __gettext("Feeds");
    
    $body = run("rss:subscriptions", $user_id);

    templates_page_output($title, $body);

?>