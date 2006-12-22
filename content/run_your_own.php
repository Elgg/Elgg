<?php
    
    // Run includes
        define("context","external");
        require_once(dirname(dirname(__FILE__))."/includes.php");
        templates_page_setup();

    // Draw page
        echo templates_page_draw( array(
                    sprintf(__gettext("Running Your Own %s"),sitename),
                    templates_draw(array(
                                                    'body' => run("content:run_your_own"),
                                                    'name' => sprintf(__gettext("Running Your Own %s"), sitename),
                                                    'context' => 'contentholder'
                                                )
                                                )
            )
            );
        
?>