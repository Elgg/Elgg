<?php

    require_once(dirname(dirname(__FILE__))."/../includes.php");
    
    global $page_owner, $profile_id;

    run("weblogs:init");
    run("profile:init");
    
    $username = trim(optional_param('profile_name',''));
    $user_id = user_info_username("ident", $username);
    if (empty($user_id)) {
        $user_id = $page_owner;
    } else {
        $page_owner = $user_id;
        $profile_id = $user_id;
    }
    
    run("rss:init");
    
    define('context','resources');

    templates_page_setup();    

    $title = run("profile:display:name") ." :: " . __gettext("Publish feeds to blog");
    
    $body = run("rss:subscriptions:publish:blog");
    
    templates_page_output($title, $body);

?>