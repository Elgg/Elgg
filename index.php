<?php

    global $CFG;

    require_once(dirname(__FILE__)."/includes.php");
    templates_page_setup();
    if (logged_on) {
        $body = templates_draw(array(
                                        'context' => 'frontpage_loggedin'
                                )   
                                );
    } else {
        $body = templates_draw(array(
                                        'context' => 'frontpage_loggedout'
                                )   
                                );
    }
    
    echo templates_page_draw( array(
                    $CFG->sitename,
                    $body
            )
            );
            
?>