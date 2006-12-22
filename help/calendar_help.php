<?php

    // Run includes
        require_once(dirname(dirname(__FILE__))."/includes.php");

       define("context", "calendar");

    // Draw page
        echo templates_page_draw( array(
                    __gettext("Calendar help"),
                    templates_draw( array(
                                                    'body' => run("help:calendar"),
                                                    'title' => __gettext("'Your calendar' help"),
                                                    'context' => 'contentholder'
                                                )
                                                )
            )
            );
        
?>
