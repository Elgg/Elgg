<?php

    // Communities module
    
    /*
    
        A brief explanation:
        
        Communities are a specialisation of users. Each community is just another
        row in the users table, albeit with user_type set to 'community', which
        allows it to have all the features of a regular user.
        
        Friendships are stored in the same way too, but displayed as memberships.
        The 'owner' field of the users table stores the moderator for a community
        (for regular users it's set to -1).
        
        TO DO:
        
            - Allow a moderator to restrict access to communities
            - Allow moderators to delete all weblog postings and file uploads
    
    */

        global $CFG;
        
    // Add communities to access levels
        $function['init'][] = $CFG->dirroot . "units/communities/communities_access_levels.php";
        $function['userdetails:init'][] = $CFG->dirroot . "units/communities/userdetails_actions.php";
    
    // Communities actions
        $function['communities:init'][] = $CFG->dirroot . "units/communities/communities_actions.php";
        
    // Communities modifications of friends actions
        $function['friends:init'][] = $CFG->dirroot . "units/communities/communities_actions.php";
    
    // Communities bar down the right hand side
        $function['display:sidebar'][] = $CFG->dirroot . "units/communities/communities_owned.php";
        $function['display:sidebar'][] = $CFG->dirroot . "units/communities/community_memberships.php";

    // 'Communities' aspect to the little menus beneath peoples' icons
        $function['users:infobox:menu:text'][] = $CFG->dirroot . "units/communities/user_info_menu_text.php";
        
    // Permissions for communities
        $function['permissions:check'][] = $CFG->dirroot . "units/communities/permissions_check.php";
        
    // View community memberships
        $function['communities:editpage'][] = $CFG->dirroot . "units/communities/communities_edit_wrapper.php";
        $function['communities:edit'][] = $CFG->dirroot . "units/communities/communities_edit.php";
        $function['communities:members'][] = $CFG->dirroot . "units/communities/communities_members.php";
        $function['communities:owned'][] = $CFG->dirroot . "units/communities/communities_moderator_of.php";
        $function['communities:owned'][] = $CFG->dirroot . "units/communities/communities_create.php";

    // Membership requests
        $function['communities:requests:view'][] = $CFG->dirroot . "units/communities/communities_membership_requests.php";
        
    // Check access levels
        $function['users:access_level_check'][] = $CFG->dirroot . "units/communities/communities_access_level_check.php";
        
    // Obtain SQL "where" string for access levels
        $function['users:access_level_sql_where'][] = $CFG->dirroot . "units/communities/communities_access_level_sql_check.php";
                
    // Link to edit icons
        $function['profile:edit:link'][] = $CFG->dirroot . "units/communities/profile_edit_link.php";
        
    // Edit profile details
        $function['userdetails:edit'][] = $CFG->dirroot . "units/communities/userdetails_edit.php";
                
?>