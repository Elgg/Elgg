<?php

global $page_owner;

$title = user_name($page_owner) . " :: ". __gettext("Communities") ."";

$body = run("content:communities:manage");
$body .= run("communities:edit",array($page_owner));

$run_result = $body;
                    
?>