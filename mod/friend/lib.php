<?php

function friend_pagesetup() {
    // register links -- 
    global $profile_id;
    global $PAGE;
    global $CFG;

    $page_owner = $profile_id;

    if (isloggedin()) {
        if (defined("context") && context == "network" && $page_owner == $_SESSION['userid']) {
            
            $PAGE->menu[] = array( 'name' => 'friends',
                                   'html' => "<li><a href=\"{$CFG->wwwroot}{$_SESSION['username']}/friends/\" class=\"selected\" >" .__gettext("Your Network").'</a></li>');
            } else {
                $PAGE->menu[] = array( 'name' => 'friends',
                                       'html' => "<li><a href=\"{$CFG->wwwroot}{$_SESSION['username']}/friends/\" >" .__gettext("Your Network").'</a></li>');
            }
    }        

    if (defined("context") && context == "network") {
        
        if (user_type($page_owner) == "person" || user_type($page_owner) == "external") {
        
            $friends_username = user_info('username', $page_owner);
            
            $PAGE->menu_sub[] = array( 'name' => 'friend',
                                       'html' => a_href("{$CFG->wwwroot}{$friends_username}/friends/" ,
                                                          __gettext("Friends"))); 
            
            $PAGE->menu_sub[] = array( 'name' => 'friend:of',
                                       'html' => a_href( "{$CFG->wwwroot}_friends/friendsof.php?owner=$page_owner",
                                                          __gettext("Friend of"))); 

            $PAGE->menu_sub[] = array( 'name' => 'friend:requests',
                                       'html' => a_href( "{$CFG->wwwroot}_friends/requests.php?owner=$page_owner",
                                                          __gettext("Friendship requests")));
            
            $PAGE->menu_sub[] = array( 'name' => 'friend:foaf',
                                       'html' => a_href( "{$CFG->wwwroot}{$friends_username}/foaf/",
                                                          __gettext("FOAF"))); 

            if (isloggedin()) {
                $PAGE->menu_sub[] = array( 'name' => 'friend:accesscontrols',
                                           'html' => a_href( "{$CFG->wwwroot}_groups/",
                                                              __gettext("Access controls")));

                if ($CFG->publicinvite == true && ($CFG->maxusers == 0 || (count_users('person') < $CFG->maxusers))) {
                    $PAGE->menu_sub[] = array( 'name' => 'friend:invite',
                                               'html' => a_href( "{$CFG->wwwroot}_invite/",
                                                                  __gettext("Invite a friend"))); 
                }
                
            }
            
        }
    }

}

    function friend_page_owner() {
        
        $friends_name = optional_param('friends_name');
        if (!empty($friends_name)) {
            return user_info_username('ident', $friends_name);
        }
        
    }

?>
