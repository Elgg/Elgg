<?php

    /*
    *    Friends plug-in
    */

        global $CFG;
    
    // Functions to perform upon initialisation
        $function['friends:init'][] = $CFG->dirroot . "units/friends/friends_init.php";
        $function['friends:init'][] = $CFG->dirroot . "units/friends/friends_actions.php";
    
    // Get list of friends
        $function['friends:get'][] = $CFG->dirroot . "units/friends/get_friends.php";
        
    // 'Friends' aspect to user profiles
        $function['users:infobox:menu:text'][] = $CFG->dirroot . "units/friends/user_info_menu_text.php";
    
    // 'Friends' list in the portfolio view
        $function['profile:log_on_pane'][] = $CFG->dirroot . "units/friends/profile_friends.php";
        $function['display:sidebar'][] = $CFG->dirroot . "units/friends/profile_friends.php";
        
    // Friends full view / edit section
        $function['friends:editpage'][] = $CFG->dirroot . "units/friends/friends_edit_wrapper.php";
        $function['friends:edit'][] = $CFG->dirroot . "units/friends/friends_edit.php";

    // Friendship requests
        $function['friends:requests:view'][] = $CFG->dirroot . "units/friends/user_friendship_requests.php";
        
    // 'Friends of' full view / edit section
        $function['friends:of:editpage'][] = $CFG->dirroot . "units/friends/friends_of_edit_wrapper.php";
        $function['friends:of:edit'][] = $CFG->dirroot . "units/friends/friends_of_edit.php";

    // FOAF file
        $function['foaf:generate'][] = $CFG->dirroot . "units/friends/generate_foaf.php";
            
?>