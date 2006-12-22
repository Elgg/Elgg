<?php

    require_once(dirname(__FILE__)."/includes.php");
    templates_page_setup();    
    if (logged_on) {
        $body = run("content:mainindex");
    } else {
        $body = run("content:mainindex:loggedout");
    }
    
    echo templates_page_draw( array(
                    sitename,
                    templates_draw(array(
                                                    'body' => $body,
                                                    'title' => __gettext("Main Index"),
                                                    'context' => 'contentholder'
                                                )
                                                )
            )
            );
            
?>