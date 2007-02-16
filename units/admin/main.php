<?php

    // Elgg administration utilities
    // Ben Werdmuller, September 2005

    // These utilities allow users tagged with the 'administration' flag
    // to perform tasks on other users' accounts, including editing posts,
    // banning or deleting accounts, adding accounts in bulk and so on.

        global $CFG;    
    
    // Permissions check
    // Establishes permissions; if the question is 'does this admin user
    // have permissions', the answer is 'yes'
        $function['permissions:check'][] = $CFG->dirroot . "units/admin/permissions_check.php";
                
    // Main admin panel screen
        $function['admin:main'][] = $CFG->dirroot . "units/admin/admin_main.php";
        
    // Content flagging system
        $function['profile:view'][] = $CFG->dirroot . "units/admin/display_content_flag_form.php";
        $function['weblogs:posts:view:individual'][] = $CFG->dirroot . "units/admin/display_content_flag_form.php";
        $function['files:folder:view'][] = $CFG->dirroot . "units/admin/display_content_flag_form.php";
        
    // Content flag administration
        $function['admin:contentflags'][] = $CFG->dirroot . "units/admin/admin_contentflags.php";

    // Extra administration of user details
        $function['userdetails:edit:details'][] = $CFG->dirroot . "units/admin/admin_userdetails.php";
    // Menu to view all users
        $function['admin:users'][] = $CFG->dirroot . "units/admin/admin_users.php";
        
    // Bulk user addition screen
        $function['admin:users:add'][] = $CFG->dirroot . "units/admin/admin_users_add.php";
        
    // Display a user control panel when given a database row from elgg.users
        $function['admin:users:panel'][] = $CFG->dirroot . "units/admin/admin_users_panel.php";
        
    // Anti-spam
        $function['admin:spam'][] = $CFG->dirroot . "units/admin/admin_spam.php";
        $function['spam:check'][] = $CFG->dirroot . "units/admin/spam_check.php";
        
    // Admin-related actions
        $function['init'][] = $CFG->dirroot . "units/admin/admin_actions.php";
    

?>