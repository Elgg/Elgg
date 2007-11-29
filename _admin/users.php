<?php

    //    ELGG content flagging admin panel page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/includes.php");
        
    // Initialise functions for user details, icon management and profile management
        run("admin:init");

        define("context", "admin");
        templates_page_setup();
        
        $flag = optional_param("flag");
        switch($flag) {
            case "admin":
                            $body = run("admin:users:admin");
                            break;
            case "banned":
                            $body = run("admin:users:banned");
                            break;
            default:
                            $body = run("admin:users");
                            break;
        }
        
    // You must be logged on to view this!
                                
        echo templates_page_draw( array(
                    __gettext("Manage users"),
                    templates_draw(array(
                        'context' => 'contentholder',
                        'title' => __gettext("Manage users"), 
                        'body' => $body
                    )
                    )
                )
                );

?>