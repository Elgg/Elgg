<?php
    
    // Run includes
        define("context","external");
        require_once(dirname(dirname(__FILE__))."/includes.php");
        templates_page_setup();

    // Draw page
        echo templates_page_draw( array(
                    sprintf(__gettext("About %s"), sitename),
                    templates_draw(array(
                                                    'body' => run("content:about"),
                                                    'name' => sprintf(__gettext("About %s"), sitename),
                                                    'context' => 'contentholder'
                                                )
                                                )
            )
            );
        
?>