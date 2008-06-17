<?php

function friend_pagesetup() {
    // register links --
    global $profile_id;
    global $PAGE;
    global $CFG;
    global $metatags;

    require_once (dirname(__FILE__)."/default_template.php");
    require_once (dirname(__FILE__)."/lib/friends_config.php");

    $metatags .= "<link rel=\"stylesheet\" href=\"" . $CFG->wwwroot . "mod/friend/css.css\" type=\"text/css\" media=\"screen\" />";

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
                                       'html' => a_href( "{$CFG->wwwroot}{$friends_username}/friendsof/",
                                                          __gettext("Friend of")));

            if(isloggedin() && $page_owner == $_SESSION['userid']){
              $PAGE->menu_sub[] = array( 'name' => 'friend:requests',
                                       'html' => a_href( "{$CFG->wwwroot}{$friends_username}/friends/requests",
                                                          __gettext("Friendship requests")));

            }

            if(FRIENDS_FOAF){
              $PAGE->menu_sub[] = array( 'name' => 'friend:foaf',
                                       'html' => a_href( "{$CFG->wwwroot}{$friends_username}/foaf/",
                                                          __gettext("FOAF")));
            }
        }
    }

}

    function friend_init() {
        global $CFG,$function;

        // Functions to perform upon initialisation
            $function['friends:init'][] = $CFG->dirroot . "mod/friend/lib/friends_init.php";
            $function['friends:init'][] = $CFG->dirroot . "mod/friend/lib/friends_actions.php";

        // Get list of friends
            $function['friends:get'][] = $CFG->dirroot . "mod/friend/lib/get_friends.php";

        // 'Friends' aspect to user profiles
            $function['users:infobox:delete'][] = $CFG->dirroot . "mod/friend/lib/user_info_menu.php";
            $function['users:infobox:menu:text'][] = $CFG->dirroot . "mod/friend/lib/user_info_menu_text.php";

        // 'Friends' list in the portfolio view
            $function['profile:log_on_pane'][] = $CFG->dirroot . "mod/friend/lib/profile_friends.php";
            $function['display:sidebar'][] = $CFG->dirroot . "mod/friend/lib/profile_friends.php";

        // Friends full view / edit section
            $function['friends:editpage'][] = $CFG->dirroot . "mod/friend/lib/friends_edit_wrapper.php";
            $function['friends:edit'][] = $CFG->dirroot . "mod/friend/lib/friends_edit.php";

        // Friendship requests
            $function['friends:requests:view'][] = $CFG->dirroot . "mod/friend/lib/user_friendship_requests.php";

        // 'Friends of' full view / edit section
            $function['friends:of:editpage'][] = $CFG->dirroot . "mod/friend/lib/friends_of_edit_wrapper.php";
            $function['friends:of:edit'][] = $CFG->dirroot . "mod/friend/lib/friends_of_edit.php";

        // FOAF file
            $function['foaf:generate'][] = $CFG->dirroot . "mod/friend/lib/generate_foaf.php";

        // Delete users
            listen_for_event("user","delete","friend_user_delete");
    }

    function friend_page_owner() {
        $friends_name = optional_param('friends_name');
        if (!empty($friends_name)) {
            return user_info_username('ident', $friends_name);
        }
    }

    function isfriend($user, $friendof) {
        global $CFG;
        if (empty($user) || $user == -1) {
            return false;
        }
        if ($result = get_records_sql("select ident from {$CFG->prefix}friends where owner = {$friendof} and friend = {$user}")) {
            return true;
        }
        return false;
    }
    
    function friend_user_delete($object_type, $event, $object) {
        if (!empty($object->ident) && $object_type == "user" && $event == "delete") {
            delete_records('friends','owner',$object->ident);
            delete_records('friends','friend',$object->ident);
        }
        return $object;
    }

?>
