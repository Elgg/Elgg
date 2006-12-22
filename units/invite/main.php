<?php

    // Invite a friend
    
    // Actions
        $function['invite:init'][] = path . "units/invite/invite_actions.php";
    
    // Introductory text
        $function['content:invite:invite'][] = path . "content/invite/invite.php";
        
    // Allow user to invite a friend
        $function['invite:invite'][] = path . "units/invite/invite.php";
        $function['invite:join'][] = path . "units/invite/invite_join.php";
        
    // Allow a new user to sign up
        $function['join:no_invite'][] = path . "units/invite/join_noinvite.php";

    // Allow the user to request a new password
        $function['invite:password:request'][] = path . "units/invite/password_request.php";
        $function['invite:password:new'][] = path . "units/invite/new_password.php";
         
?>