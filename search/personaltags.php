<?php

    //    ELGG display popular tags page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/includes.php");
        
        run("search:init");
        run("profile:init");
        
        define("context","profile");
        
//         global $page_owner;
        
        $username = trim(optional_param('profile_name',''));
        $user_id = user_info_username("ident",$username);
        if (!$user_id) {
            $user_id = $page_owner;
        } else {
            $page_owner = $user_id;
            $profile_id = $user_id;
        }
        
        templates_page_setup();
        
        $title = user_name($user_id) . " :: " . __gettext("Tags");

        $body = run("content:tags");
        $body .= run("search:tags:personal:display", $user_id);
        
        $body = templates_draw(array(
                        'context' => 'contentholder',
                        'title' => $title,
                        'body' => $body
                    )
                    );
                    
        echo templates_page_draw( array(
                    $title, $body
                )
                );

?>