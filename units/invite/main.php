<?php

    // Invite a friend
    
        global $CFG;
    
    // Actions
        $function['invite:init'][] = $CFG->dirroot . "units/invite/invite_actions.php";
    
    // Introductory text
        $function['content:invite:invite'][] = $CFG->dirroot . "content/invite/invite.php";
        
    // Allow user to invite a friend
        $function['invite:invite'][] = $CFG->dirroot . "units/invite/invite.php";
        $function['invite:join'][] = $CFG->dirroot . "units/invite/invite_join.php";
        
    // Allow a new user to sign up
        $function['join:no_invite'][] = $CFG->dirroot . "units/invite/join_noinvite.php";

    // Allow the user to request a new password
        $function['invite:password:request'][] = $CFG->dirroot . "units/invite/password_request.php";
        $function['invite:password:new'][] = $CFG->dirroot . "units/invite/new_password.php";
         
?>