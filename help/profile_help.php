<?php
    
    // Run includes
        require_once(dirname(dirname(__FILE__))."/includes.php");

        define("context", "profile");
        templates_page_setup();
// Draw page
        echo templates_page_draw( array(
                    "Profile help",
                    templates_draw(array(
                                                    'body' => run("help:profile"),
                                                    'title' => __gettext("'Your Profile' help"),
                                                    'context' => 'contentholder'
                                                )
                                                )
            )
            );
        
?>