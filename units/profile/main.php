<?php

    // ELGG Profile system

    // Profile initialisation
        $function['profile:init'][] = $CFG->dirroot . "units/profile/function_init.php";
        // $function['profile:init'][] = $CFG->dirroot . "units/profile/function_editfield_defaults.php";
        $function['profile:init'][] = $CFG->dirroot . "units/profile/function_upload_foaf.php";
    
    // Initialisation for the search function
        $function['search:init'][] = $CFG->dirroot . "units/profile/function_init.php";
        // $function['search:init'][] = $CFG->dirroot . "units/profile/function_editfield_defaults.php";
        $function['search:all:tagtypes'][] = $CFG->dirroot . "units/profile/function_search_all_tagtypes.php";
        $function['search:all:tagtypes:rss'][] = $CFG->dirroot . "units/profile/function_search_all_tagtypes_rss.php";
        
    // Function to search through profiles
        $function['search:display_results'][] = $CFG->dirroot . "units/profile/function_search.php";
        $function['search:display_results:rss'][] = $CFG->dirroot . "units/profile/function_search_rss.php";
        
    // Functions to view and edit individual profile fields        
        $function['profile:editfield:display'][] = $CFG->dirroot . "units/profile/function_editfield_display.php";
        $function['profile:field:display'][] = $CFG->dirroot . "units/profile/function_field_display.php";
    
    // Function to edit all profile fields
        $function['profile:edit'][] = $CFG->dirroot . "units/profile/function_edit.php";
        
    // Function to view all profile fields
        $function['profile:view'][] = $CFG->dirroot . "units/profile/function_view.php";
        
    // Function to display user's name
        $function['profile:display:name'][] = $CFG->dirroot . "units/profile/function_display_name.php";
        
        $function['profile:user:info'][] = $CFG->dirroot . "units/profile/profile_user_info.php";
    
    // Descriptive text
        $function['content:profile:edit'][] = $CFG->dirroot . "units/profile/content_edit.php";

    // Establish permissions
        $function['permissions:check'][] = $CFG->dirroot . "units/profile/permissions_check.php";
        
    // FOAF
        $function['foaf:generate:fields'][] = $CFG->dirroot . "units/profile/generate_foaf_fields.php";
        $function['vcard:generate:fields:adr'][] = $CFG->dirroot . "units/profile/generate_vcard_adr_fields.php";
                
    // Actions to perform when an access group is deleted
        $function['groups:delete'][] = $CFG->dirroot . "units/profile/groups_delete.php";
        
    // Publish static RSS file of posts and files
        $function['profile:rss:publish'][] = $CFG->dirroot . "units/profile/function_rss_publish.php";
?>