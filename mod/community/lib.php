<?php

function community_pagesetup() {
    // register links -- 
    global $profile_id;
    global $PAGE;
    global $CFG;

    $page_owner = $profile_id;
    
    $usertype = user_type($page_owner);

    if ($usertype == "community") {
            
        if (defined("context") && context == "profile") {

            if (run("permissions:check", "profile")) {
            
                $PAGE->menu_sub[] = array( 'name' => 'profile:edit', 
                                       'html' => '<a href="'.$CFG->wwwroot.'profile/edit.php?profile_id='.$page_owner.'">'
                                       . __gettext("Edit this profile") . '</a>');
                
                $PAGE->menu_sub[] = array( 'name' => 'community:pic',
                                           'html' => a_href("{$CFG->wwwroot}_icons/?context=profile&amp;profile_id=$page_owner" ,
                                                              __gettext("Community site picture")));
    
                $PAGE->menu_sub[] = array( 'name' => 'community:edit',
                                           'html' => a_href("{$CFG->wwwroot}_userdetails/?context=profile&amp;profile_id=$page_owner" ,
                                                             __gettext("Edit community details")));

            }
            
        }
        if (defined("context") && (context == "profile" || context == "network")) {
            $PAGE->menu_sub[] = array( 'name' => 'community:requests',
                                           'html' => a_href("{$CFG->wwwroot}_communities/requests.php?profile_id=$page_owner",
                                                             __gettext("View membership requests")));
        }
        
        /*$PAGE->menu_sub[] = array( 'name' => 'community:members',
                                   'html' => a_href("{$CFG->wwwroot}_communities/members.php?owner=$page_owner" ,
                                                      __gettext("Community Members")));*/
                                                      
        
    } else if ($usertype == "person") {
    
        if (defined("context") && context == "network") {
    
            $PAGE->menu_sub[] = array( 'name' => 'community',
                                       'html' => a_href("{$CFG->wwwroot}_communities/?owner=$page_owner" ,
                                                          __gettext("Communities"))); 
                            
            $PAGE->menu_sub[] = array( 'name' => 'community:owned',
                                       'html' => a_href("{$CFG->wwwroot}_communities/owned.php?owner=$page_owner" ,
                                                          __gettext("Owned Communities")));

        }
        
    }
    
    $PAGE->search_menu[] = array( 'name' => __gettext("Communities"),
                                  'user_type' => 'community');

}

?>
