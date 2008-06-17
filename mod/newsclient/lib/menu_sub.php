<?php

    global $page_owner;

    $rss_username = user_info('username', $page_owner);
    
    if (context=="resources") {
    
        if ($page_owner != -1) {

                
                    $run_result .= templates_draw(array(
                                'context' => 'submenuitem',
                                'name' => __gettext("Feeds"),
                                'location' =>  url . $rss_username . "/newsclient/"
                            )
                            );
                        

                    $run_result .= templates_draw(array(
                                        'context' => 'submenuitem',
                                        'name' => __gettext("View aggregator"),
                                        'location' =>  url . $rss_username . "/newsclient/all/"
                                    )
                                    );
                                
            }

            $run_result .= templates_draw(array(
                            'context' => 'submenuitem',
                            'name' => __gettext("Popular feeds"),
                            'location' =>  url . "_rss/popular.php"
                        )
                        );

             /* $run_result .= templates_draw(array(
                            'context' => 'submenuitem',
                            'name' => __gettext("Page help"),
                            'location' => url . 'help/feeds_help.php'
                        )
                        ); */

                    
    }

?>