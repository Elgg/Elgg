<?php

        global $page_owner;
        
        $title = run("profile:display:name") . " :: ". __gettext("Friends I have linked to") ."";

        $body = run("content:friends:manage");
        $body .= run("friends:edit",array($page_owner));
        
        $run_result .= $body;
                    
?>