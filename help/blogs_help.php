<?php

    // Run includes
        require_once(dirname(dirname(__FILE__))."/includes.php");

        define("context", "weblog");
        templates_page_setup();

    // Draw page
        echo templates_page_draw( array(
                    "Blog help",
                    templates_draw(array(
                                                    'body' => run("help:blogs"),
                                                    'title' => __gettext("'Your blog' help"),
                                                    'context' => 'contentholder'
                                                )
                                                )
            )
            );
        
?>
