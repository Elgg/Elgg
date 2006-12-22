<?php

    /*
    *    Friends plug-in
    */

    // Functions to perform upon initialisation
        $function['friends:init'][] = path . "units/friends/friends_init.php";
        $function['friends:init'][] = path . "units/friends/friends_actions.php";
    
    // Get list of friends
        $function['friends:get'][] = path . "units/friends/get_friends.php";
        
    // 'Friends' aspect to user profiles
        $function['users:infobox:menu:text'][] = path . "units/friends/user_info_menu_text.php";
    
    // 'Friends' list in the portfolio view
        $function['profile:log_on_pane'][] = path . "units/friends/profile_friends.php";
        $function['display:sidebar'][] = path . "units/friends/profile_friends.php";
        
    // Friends full view / edit section
        $function['friends:editpage'][] = path . "units/friends/friends_edit_wrapper.php";
        $function['friends:edit'][] = path . "units/friends/friends_edit.php";

    // Friendship requests
        $function['friends:requests:view'][] = path . "units/friends/user_friendship_requests.php";
        
    // 'Friends of' full view / edit section
        $function['friends:of:editpage'][] = path . "units/friends/friends_of_edit_wrapper.php";
        $function['friends:of:edit'][] = path . "units/friends/friends_of_edit.php";

    // FOAF file
        $function['foaf:generate'][] = path . "units/friends/generate_foaf.php";
            
?>