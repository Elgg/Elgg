<?php

    global $page_owner;

    $title = run("profile:display:name", $page_owner) . " :: ". __gettext("Friends who have linked to you") ."";

    $body = run("content:friends:of:manage");
    $body .= run("friends:of:edit",array($page_owner));
    
    $run_result .= $body;

?>