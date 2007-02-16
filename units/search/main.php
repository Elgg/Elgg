<?php

    // ELGG Keyword search system

    // Parse REQUEST field
        $function['search:display'][] = $CFG->dirroot . "units/search/function_display.php";        
        $function['search:all:display'][] = $CFG->dirroot . "units/search/function_search_all_display.php";
        $function['search:all:display:rss'][] = $CFG->dirroot . "units/search/function_search_all_display_rss.php";
        $function['search:tags:display'][] = $CFG->dirroot . "units/search/tags_display.php";        
        $function['search:tags:personal:display'][] = $CFG->dirroot . "units/search/tags_display_personal.php";        
        
    // Suggest tags
        $function['search:tags:suggest'][] = $CFG->dirroot . "units/search/search_suggest_tags.php";
        
    // Suggest users
        $function['search:users:suggest'][] = $CFG->dirroot . "units/search/search_suggest_users.php";
        
    // Suggest RSS
        $function['search:rss:suggest'][] = $CFG->dirroot . "units/search/search_suggest_rss.php";
        
    // Log on bar down the right hand side
        // $function['display:sidebar'][] = $CFG->dirroot . "units/search/search_user_info_menu.php";
    
    // Actions to perform when an access group is deleted
        $function['groups:delete'][] = $CFG->dirroot . "units/search/groups_delete.php";
        
?>