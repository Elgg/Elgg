<?php
    
    // Run includes
        require_once(dirname(dirname(__FILE__))."/includes.php");
    
        define("context", "network");
        templates_page_setup();    
    // Draw page
        echo templates_page_draw( array(
                    "Network help ",
                    templates_draw(array(
                                                    'body' => run("help:network"),
                                                    'title' => __gettext("'Your Network' help"),
                                                    'context' => 'contentholder'
                                                )
                                                )
            )
            );
        
?>